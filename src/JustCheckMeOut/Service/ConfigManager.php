<?php

namespace PerspectiveTeam\JustCheckMeOut\Service;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\SerializerInterface;

class ConfigManager
{

    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly SerializerInterface $serializer
    )
    {
    }

    public function isEnabled(): bool
    {
        return true;
    }

    public function replaceDefault(): bool
    {
        return false;
    }

    public function getTheme(): string
    {
        return 'default';
    }

    public function getComponentConfig(string $name, string $key): mixed
    {
        $config = [
            'shipping-method-list' => [
                'optimistic' => true,
            ],
            'payment-method-list' => [
                'optimistic' => true,
                'deferred' => true,
            ],
        ];
        return $config[$name][$key] ?? null;
        return $this->scopeConfig->getValue("justcheckmeout/component_$name/$key");
    }
}
