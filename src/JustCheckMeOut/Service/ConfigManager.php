<?php

namespace PerspectiveTeam\JustCheckMeOut\Service;

class ConfigManager
{

    public const CONFIG_KEY_THEME = 'justcheckmeout/theme/current';

    public function getTheme(): string
    {
        return 'minimal';
    }
}
