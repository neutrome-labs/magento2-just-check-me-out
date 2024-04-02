<?php

namespace PerspectiveTeam\JustCheckMeOut\Model;

use Magento\Authorization\Model\UserContextInterface;
use Magento\Customer\Model\Session;
use Magento\GraphQl\Model\Query\Context;
use Magento\GraphQl\Model\Query\ContextExtensionFactory;
use Magento\GraphQl\Model\Query\ContextInterface;
use Magento\Store\Model\StoreManagerInterface;

class SsrGraphqlContextFactory
{

    public function __construct(
        private readonly Session                 $customerSession,
        private readonly StoreManagerInterface   $storeManager,
        private readonly ContextExtensionFactory $contextExtensionFactory
    )
    {
    }

    public function create(): ContextInterface
    {
        return new Context(
            $this->customerSession->isLoggedIn()
                ? UserContextInterface::USER_TYPE_CUSTOMER
                : null,
            $this->customerSession->getCustomerId(),
            $this->contextExtensionFactory->create(['data' => [
                'store' => $this->storeManager->getStore(),
                'is_customer' => true,
                'customer_group_id' => 1
            ]])
        );
    }
}
