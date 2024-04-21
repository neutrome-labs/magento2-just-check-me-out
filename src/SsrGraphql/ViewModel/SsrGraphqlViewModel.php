<?php

namespace PerspectiveTeam\SsrGraphql\ViewModel;

use Magento\Framework\Async\DeferredInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\StoreManagerInterface;
use PerspectiveTeam\SsrGraphql\Model\Resolver;

class SsrGraphqlViewModel implements ArgumentInterface
{

    private array $deferred = [];

    public function __construct(
        private readonly StoreManagerInterface $storeManager,
        private readonly Resolver $resolver,
    )
    {
    }

    public function getDeferred(string $key): ?DeferredInterface
    {
        return array_key_exists($key, $this->deferred) ? $this->deferred[$key] : null;
    }

    public function getBaseUrl(): string
    {
        return $this->storeManager->getStore()->getBaseUrl();
    }

    public function makeSsrGqlCall(string $query, array $variables = [], $options = null)
    {
        $uid = uniqid('PSTEAM_GQL_');
        $this->deferred[$uid] = $this->resolver->deferResolve($query, $variables);

        $query = json_encode($query);
        $variables = json_encode($variables);
        $options = $options !== null ? json_encode($options) : '';

        return <<<JS
window.createMagento2SsrGqlStub($query, $variables, %{$uid}%, $options)
JS;
    }
}
