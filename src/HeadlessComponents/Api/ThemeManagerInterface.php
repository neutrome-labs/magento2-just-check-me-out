<?php

namespace PerspectiveTeam\HeadlessComponents\Api;

interface ThemeManagerInterface
{

    public function current(): ThemeInterface;

    public function find(string $slug): ?ThemeInterface;

    public function list(): array;
}
