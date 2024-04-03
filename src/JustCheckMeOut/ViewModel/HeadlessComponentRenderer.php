<?php

namespace PerspectiveTeam\JustCheckMeOut\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\Element\TemplateFactory;

class HeadlessComponentRenderer implements ArgumentInterface
{

    public function __construct(
        private readonly TemplateFactory $blockFactory,
    )
    {
    }

    public function render(string $template, array $data = [], ?string $slug = null): string
    {
        $template = !str_contains($template, '::')
            ? "PerspectiveTeam_JustCheckMeOut::component/headless/$template.phtml"
            : $template;

        if (!$slug) {
            $slug = uniqid($template);
        }

        return $this->blockFactory
            ->create()
            ->setTemplate($template)
            ->setNameInLayout("psteam_justcheckmeout.headless.$slug")
            ->setData(array_merge([
                'headlessComponentRenderer' => $this
            ], $data))
            ->toHtml();
    }
}
