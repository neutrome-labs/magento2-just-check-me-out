<?php

namespace PerspectiveTeam\HeadlessComponents\Block;

use Magento\Framework\View\Element\Template;
use PerspectiveTeam\JustCheckMeOut\ViewModel\HeadlessComponentRenderer;

class Headless extends Template
{

    public function __construct(
        public readonly HeadlessComponentRenderer $headlessComponentRenderer,
        Template\Context                          $context,
        array                                     $data = []
    )
    {
        parent::__construct($context, $data);
    }

    public function getSlug(): string
    {
        if (!$this->getData('slug')) {
            $this->setData('slug', uniqid());
            $this->setData('cache_lifetime', false);
        }

        return $this->getData('slug');
    }

    public function getNameInLayout()
    {
        return parent::getNameInLayout() ?? ("psteam_justcheckmeout.headless." . $this->getSlug());
    }

    public function getCacheKeyInfo()
    {
        $info = parent::getCacheKeyInfo();
        $info['headless'] = $this->getNameInLayout();
        return $info;
    }
}
