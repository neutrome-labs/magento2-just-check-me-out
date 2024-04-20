<?php

namespace PerspectiveTeam\JustCheckMeOut\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use PerspectiveTeam\HeadlessComponents\Service\HeadlessComponentRendererFactory;
use PerspectiveTeam\JustCheckMeOut\Service\HeadlessThemeManager;

class HeadlessComponentRenderer implements ArgumentInterface
{

    private ?\PerspectiveTeam\HeadlessComponents\Service\HeadlessComponentRenderer $renderer = null;

    public function __construct(
        private readonly HeadlessThemeManager $themeManager,
        private readonly HeadlessComponentRendererFactory $rendererFactory
    )
    {
    }

    public function getRenderer(): \PerspectiveTeam\HeadlessComponents\Service\HeadlessComponentRenderer
    {
        if (!$this->renderer) {
            $this->renderer = $this->rendererFactory->create([
                'themeManager' => $this->themeManager,
            ]);
        }

        return $this->renderer;
    }

    public function render(
        string  $template,
        array   $data = [],
        ?string $slug = null,
        int     $cacheLifetime = 60 * 60 * 24
    ): string
    {
        return $this->getRenderer()->render($template, $data, $slug, $cacheLifetime);
    }
}
