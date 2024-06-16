<?php

namespace PerspectiveTeam\SsrGraphql\Api;

interface ResolverInterface
{

    public function resolve(string $query, array $variables = []): array;
}
