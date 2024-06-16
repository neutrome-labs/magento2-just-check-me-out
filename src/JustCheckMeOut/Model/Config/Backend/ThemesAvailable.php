<?php

namespace PerspectiveTeam\JustCheckMeOut\Model\Config\Backend;

use Magento\Framework\Data\OptionSourceInterface;
use PerspectiveTeam\HeadlessComponents\Api\ThemeInterface;
use PerspectiveTeam\HeadlessComponents\Model\Theme;

class ThemesAvailable implements OptionSourceInterface
{

    public function __construct(
        private readonly array $themes = [
            'default' => new Theme([
                ThemeInterface::PARENT => null,
                ThemeInterface::SLUG => 'default',
                ThemeInterface::MODULE => 'PerspectiveTeam_JustCheckMeOut'
            ]),
        ],
    )
    {
    }

    public function find(string $slug): ?ThemeInterface
    {
        return array_key_exists($slug, $this->themes) ? $this->themes[$slug] : null;
    }

    public function toOptionArray()
    {
        return $this->themes;
    }
}
