<?php

namespace NeutromeLabs\JustCheckMeOut\Block;

use Magento\Framework\View\Element\Template;
use NeutromeLabs\JustCheckMeOut\Service\ConfigManager;
use NeutromeLabs\JustCheckMeOut\ViewModel\HeadlessComponentRenderer;
use NeutromeLabs\JustCheckMeOut\ViewModel\QuoteViewModel;
use NeutromeLabs\SsrGraphql\ViewModel\SsrGraphqlViewModel;

class Stateful extends Template
{

    public function __construct(
        public readonly ConfigManager $configManager,
        public readonly QuoteViewModel $quoteViewModel,
        public readonly SsrGraphqlViewModel $ssrGraphqlViewModel,
        public readonly HeadlessComponentRenderer $headlessComponentRenderer,
        Template\Context $context,
        array $data = []
    )
    {
        parent::__construct($context, $data);
    }

    public function getStore(): \Magento\Store\Api\Data\StoreInterface
    {
        return $this->_storeManager->getStore();
    }

    public function getCacheLifetime()
    {
        if ($this->configManager->isSsr()) {
            return null;
        }

        return 24 * 60 * 60;
    }
}
