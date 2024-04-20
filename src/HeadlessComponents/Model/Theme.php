<?php

namespace PerspectiveTeam\HeadlessComponents\Model;

use Magento\Framework\DataObject;
use PerspectiveTeam\HeadlessComponents\Api\ThemeInterface;

class Theme extends DataObject implements ThemeInterface
{

    public function getParent(): ?string
    {
        return $this->getData(self::PARENT);
    }

    public function getSlug(): ?string
    {
        return $this->getData(self::SLUG);
    }

    public function getModule(): ?string
    {
        return $this->getData(self::MODULE);
    }
}
