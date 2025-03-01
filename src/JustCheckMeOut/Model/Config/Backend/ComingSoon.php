<?php

namespace PerspectiveTeam\JustCheckMeOut\Model\Config\Backend;

use Magento\Framework\Data\OptionSourceInterface;

class ComingSoon implements OptionSourceInterface
{

    public function toOptionArray()
    {
        return [
            ['value' => 0, 'label' => __('Coming Soon')]
        ];
    }
}
