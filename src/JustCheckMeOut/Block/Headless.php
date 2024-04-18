<?php

namespace PerspectiveTeam\JustCheckMeOut\Block;

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
}
