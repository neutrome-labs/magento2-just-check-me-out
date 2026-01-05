<?php

namespace NeutromeLabs\JustCheckMeOut\Service;

use NeutromeLabs\JustCheckMeOut\Api\AdditionalViewInterface;

class ShippingMethodAdditionalViewRegistry
{

    /**
     * @param array<string, AdditionalViewInterface> $items
     */
    public function __construct(
        private readonly array $items = []
    )
    {
    }

    public function get(string $code): ?AdditionalViewInterface
    {
        return array_key_exists($code, $this->items) ? $this->items[$code] : null;
    }
}
