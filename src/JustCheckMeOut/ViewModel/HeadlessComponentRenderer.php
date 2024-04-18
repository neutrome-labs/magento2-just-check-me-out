<?php

namespace PerspectiveTeam\JustCheckMeOut\ViewModel;

use Magento\Framework\Exception\ValidatorException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\LayoutInterface;
use PerspectiveTeam\JustCheckMeOut\Block\Headless;
use PerspectiveTeam\JustCheckMeOut\Block\HeadlessFactory;

class HeadlessComponentRenderer implements ArgumentInterface
{

    public function __construct(
        private readonly LayoutInterface $layout,
        private readonly HeadlessFactory $blockFactory,
    )
    {
    }

    private function isShortTemplate(string $template): bool
    {
        return !str_contains($template, '::');
    }

    private function createBlockInstance(
        array  $data,
        string $slug,
        int    $cacheLifetime
    ): Headless
    {
        return $this->blockFactory
            ->create()
            ->setNameInLayout("psteam_justcheckmeout.headless.$slug")
            ->setData(array_merge([
                'cache_lifetime' => $cacheLifetime,
            ], $data));
    }

    private function tryRender(Headless $block, ?string $template): string
    {
        if ($template && $this->isShortTemplate($template)) {
            $fallbackModule = 'PerspectiveTeam_JustCheckMeOut';
            $currentThemeModule = 'PerspectiveTeam_JustCheckMeOutThemeMinimal' ?? $fallbackModule;

            $template = "$currentThemeModule::component/headless/$template.phtml";
            $fallbackTemplate = str_replace($currentThemeModule, $fallbackModule, $template);
        }

        try {
            if ($template) {
                $block->setTemplate($template);
            }
            return $block->toHtml();
        } catch (ValidatorException $e) { // todo: is not thrown in production mode
            if (str_starts_with($e->getMessage(), 'Invalid template file') && isset($fallbackTemplate)) {
                try {
                    return $block->setTemplate($fallbackTemplate)->toHtml();
                } catch (ValidatorException $syntheticE) {
                    if (!str_starts_with($e->getMessage(), 'Invalid template file')) {
                        throw $syntheticE;
                    }
                }
            }
            throw $e;
        }
    }

    public function render(
        string  $template,
        array   $data = [],
        ?string $slug = null,
        int     $cacheLifetime = 60 * 60 * 24
    ): string
    {
        $possibleScriptCompanionTemplate = $this->isShortTemplate($template)
            ?  "$template.script"
            : str_replace('.phtml', '.script.phtml', $template);

        if (!$slug) {
            $slug = uniqid();
            $cacheLifetime = 0;
        }

        $html = '';

        $scriptCompanionBlock = $this->createBlockInstance(
            $data,
            $slug . '-script',
            $cacheLifetime
        );

        // side effect: sets proper template before inserting into layout
        try {
            $canRenderCompanion = (bool)$this->tryRender($scriptCompanionBlock, $possibleScriptCompanionTemplate);
        } catch (ValidatorException $e) {
            if (str_starts_with($e->getMessage(), 'Invalid template file')) {
                $canRenderCompanion = false;
            } else {
                throw $e;
            }
        }

        if ($canRenderCompanion) {
            if (count($this->layout->getAllBlocks()) > 0) {
                if (!$this->layout->hasElement($scriptCompanionBlock->getNameInLayout())) {
                    $this->layout->addBlock(
                        $scriptCompanionBlock,
                        $scriptCompanionBlock->getNameInLayout(),
                        'checkout.onepage.api.after'
                    );
                }
            } else {
                $html .= $this->tryRender($scriptCompanionBlock, null);
            }
        }

        $html .= $this->tryRender($this->createBlockInstance(
            $data,
            $slug,
            $cacheLifetime,
        ), $template);

        return $html;
    }
}
