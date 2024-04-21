<?php

namespace PerspectiveTeam\HeadlessComponents\Service;

use Magento\Framework\View\LayoutInterface;
use PerspectiveTeam\HeadlessComponents\Api\ThemeInterface;
use PerspectiveTeam\HeadlessComponents\Api\ThemeManagerInterface;
use PerspectiveTeam\HeadlessComponents\Block\Headless;
use PerspectiveTeam\HeadlessComponents\Block\HeadlessFactory;

class Renderer
{

    public function __construct(
        private readonly LayoutInterface       $layout,
        private readonly HeadlessFactory       $blockFactory,
        private readonly ThemeManagerInterface $themeManager,
    )
    {
    }

    public function isShortTemplate(string $template): bool
    {
        return !str_contains($template, '::');
    }

    public function createBlockInstance(
        array   $data,
        ?string $slug,
        int     $cacheLifetime
    ): Headless
    {
        return $this->blockFactory
            ->create()
            ->setData(array_merge([
                'slug' => $slug,
                'cache_lifetime' => $cacheLifetime,
            ], $data));
    }

    private function renderRecursive(Headless $block, ?string $template, ?ThemeInterface $theme = null): string
    {
        $theme = $theme ?? $this->themeManager->current();

        if ($template && $this->isShortTemplate($template)) {
            $fullTemplate = $theme->getModule() . "::$template.phtml";
        }

        if (isset($fullTemplate) && $fullTemplate) {
            $block->setTemplate($fullTemplate);
        } else if ($template) {
            $block->setTemplate($template);
        }

        if (!$block->getTemplateFile()) {
            if (
                isset($fullTemplate)
                && $theme->getParent()
                && ($parentTheme = $this->themeManager->find($theme->getParent()))
            ) {
                return $this->renderRecursive($block, $template, $parentTheme);
            }
        }

        return $block->toHtml();
    }

    public function render(
        string  $template,
        array   $data = [],
        ?string $slug = null,
        int     $cacheLifetime = 60 * 60 * 24
    ): string
    {
        $possibleScriptCompanionTemplate = $this->isShortTemplate($template)
            ? "$template.script"
            : str_replace('.phtml', '.script.phtml', $template);

        $html = '';

        $scriptCompanionBlock = $this->createBlockInstance(
            $data,
            $slug . '-script',
            $cacheLifetime
        );

        // side effect: sets proper template before inserting into layout
        try {
            $canRenderCompanion = (bool)$this->renderRecursive($scriptCompanionBlock, $possibleScriptCompanionTemplate);
        } catch (\Exception $e) {
            $canRenderCompanion = false;
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
                $html .= $this->renderRecursive($scriptCompanionBlock, null);
            }
        }

        $html .= $this->renderRecursive($this->createBlockInstance(
            $data,
            $slug,
            $cacheLifetime,
        ), $template);

        return $html;
    }
}
