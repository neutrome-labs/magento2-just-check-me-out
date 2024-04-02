<?php

namespace PerspectiveTeam\JustCheckMeOut\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\Element\TemplateFactory;

class HeadlessComponentRenderer implements ArgumentInterface
{

    public function __construct(
        private readonly TemplateFactory $blockFactory
    )
    {
    }

    public function render(string $template, array $data = []): string
    {
        $template = !str_contains($template, '::')
            ? "PerspectiveTeam_JustCheckMeOut::component/headless/$template.phtml"
            : $template;

        return $this->blockFactory
            ->create()
            ->setTemplate($template)
            ->setData(array_merge([
                'headlessComponentRenderer' => $this
            ], $data))
            ->toHtml();
    }
}
