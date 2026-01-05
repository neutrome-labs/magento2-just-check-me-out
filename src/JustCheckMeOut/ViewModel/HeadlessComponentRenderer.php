<?php

declare(strict_types=1);

namespace NeutromeLabs\JustCheckMeOut\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use NeutromeLabs\HeadlessComponents\Service\AttributesHtmlGenerator;
use NeutromeLabs\HeadlessComponents\Service\Renderer;

class HeadlessComponentRenderer implements ArgumentInterface
{
    public function __construct(
        private readonly Renderer $renderer,
        private readonly AttributesHtmlGenerator $attributesHtmlGenerator
    ) {
    }

    public function render(
        string  $template,
        array   $data = [],
        ?string $slug = null
    ): string {
        if ($this->renderer->isShortTemplate($template)) {
            $template = 'component/headless/' . $template;
        }

        if (($slug ?? '') !== '') {
            $slug = 'justcheckmeout.' . $slug;
        }

        return $this->renderer->render($template, $data, $slug, 'justcheckmeout.api.after');
    }

    public function renderAttributes(?array $attributes, ?callable $callback = null): string
    {
        return $this->attributesHtmlGenerator->generate($attributes, $callback);
    }
}
