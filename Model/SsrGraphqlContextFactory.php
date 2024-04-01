<?php

namespace PerspectiveTeam\JustCheckMeOut\Model;

use Magento\GraphQl\Model\Query\Context;
use Magento\GraphQl\Model\Query\ContextExtensionFactory;
use Magento\GraphQl\Model\Query\ContextInterface;
use Magento\Store\Model\StoreManagerInterface;

class SsrGraphqlContextFactory
{

    public function __construct(
        private readonly StoreManagerInterface $storeManager,
        private readonly ContextExtensionFactory $contextExtensionFactory
    )
    {
    }

    public function create(): ContextInterface
    {
        return new Context(null, 3200356, $this->contextExtensionFactory->create(['data' => [
            'store' => $this->storeManager->getStore(),
            'is_customer' => true,
            'customer_group_id' => 1
        ]]));
    }
}
