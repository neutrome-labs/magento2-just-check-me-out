<?php

namespace PerspectiveTeam\JustCheckMeOut\Service;

use NeutromeLabs\HeadlessComponents\Api\ThemeInterface;
use NeutromeLabs\HeadlessComponents\Api\ThemeManagerInterface;
use PerspectiveTeam\JustCheckMeOut\Model\Config\Backend\ThemesAvailable;

class HeadlessThemeManager implements ThemeManagerInterface
{

    public function __construct(
        private readonly ConfigManager $configManager,
        private readonly ThemesAvailable $themesAvailable
    )
    {
    }

    public function current(): ThemeInterface
    {
        return $this->themesAvailable->find($this->configManager->getTheme());
    }

    public function find(string $slug): ?ThemeInterface
    {
        return $this->themesAvailable->find($slug);
    }

    public function list(): array
    {
        return $this->themesAvailable->toOptionArray();
    }
}
