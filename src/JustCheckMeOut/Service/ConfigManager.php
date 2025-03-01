<?php

namespace PerspectiveTeam\JustCheckMeOut\Service;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Store\Model\StoreManagerInterface;

class ConfigManager
{

    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly StoreManagerInterface $storeManager,
        private readonly SerializerInterface $serializer
    )
    {
    }

    public function get(string $path)
    {
        return $this->scopeConfig->getValue(
            "neutromelabs_justcheckmeout/$path",
            'store',
            $this->storeManager->getStore()->getId()
        );
    }

    public function isEnabled(): bool
    {
        return $this->get('general/enable') ?? false;
    }

    public function replaceDefault(): bool
    {
        return $this->get('general/replace_default') ?? false;
    }

    public function getTheme(): string
    {
        return $this->get('general/theme') ?? 'default';
    }

    public function isSsr(): bool
    {
        return $this->get('general/ssr') ?? false;
    }

    public function getComponentConfig(string $name, string $key, bool $unserialize = false): mixed
    {
        $v = $this->get("component_$name/$key");
        return $unserialize && !!$v ? $this->serializer->unserialize($v) : $v;
    }
}
