<?php

namespace PerspectiveTeam\JustCheckMeOut\Api;

interface AdditionalViewInterface
{

    public const KIND_RENDER = 'render';

    public const KIND_BLOCK = 'block';

    public const KIND_PLAIN_HTML = 'plain_html';

    public function getKind(): string;

    public function getArgument(): mixed;
}
