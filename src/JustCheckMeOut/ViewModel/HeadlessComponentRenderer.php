<?php

namespace PerspectiveTeam\JustCheckMeOut\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use NeutromeLabs\HeadlessComponents\Service\AttributesHtmlGenerator;
use NeutromeLabs\HeadlessComponents\Service\Renderer;
use NeutromeLabs\HeadlessComponents\Service\RendererFactory;
use PerspectiveTeam\JustCheckMeOut\Service\HeadlessThemeManager;

class HeadlessComponentRenderer implements ArgumentInterface
{

    private ?Renderer $renderer = null;

    public function __construct(
        private readonly HeadlessThemeManager $themeManager,
        private readonly RendererFactory      $rendererFactory,
        private readonly AttributesHtmlGenerator $attributesHtmlGenerator
    )
    {
    }

    public function getRenderer(): Renderer
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
        if ($this->getRenderer()->isShortTemplate($template)) {
            $template = 'component/headless/' . $template;
        }

        if (strlen($slug ?? '') > 0) {
            $slug = 'justcheckmeout.' . $slug;
        }

        return $this->getRenderer()->render($template, $data, $slug, $cacheLifetime, 'justcheckmeout.api.after');
    }

    public function renderAttributes(?array $attributes, ?callable $callback = null): string
    {
        return $this->attributesHtmlGenerator->generate($attributes, $callback);
    }
}
