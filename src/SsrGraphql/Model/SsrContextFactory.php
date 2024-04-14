<?php

namespace PerspectiveTeam\SsrGraphql\Model;

use Magento\Customer\Model\Authorization\CustomerSessionUserContext;
use Magento\Framework\App\ObjectManager;
use Magento\GraphQl\Model\Query\ContextInterface;
use Magento\Webapi\Model\Authorization\GuestUserContext;
use Magento\Webapi\Model\Authorization\TokenUserContext;

class SsrContextFactory
{

    public function __construct()
    {
    }

    public function create(): ContextInterface
    {
        // todo: arguments via di.xml
        return ObjectManager::getInstance()
            ->create(\Magento\GraphQl\Model\Query\ContextFactory::class, [
                'contextParametersProcessors' => [
                    ObjectManager::getInstance()->get(\Magento\StoreGraphQl\Model\Context\AddStoreInfoToContext::class),
                    ObjectManager::getInstance()->create(\Magento\CustomerGraphQl\Model\Context\AddUserInfoToContext::class, [
                        'userContext' => ObjectManager::getInstance()->create(\Magento\Authorization\Model\CompositeUserContext::class, [
                            'userContexts' => [
                                'tokenUserContext' => [
                                    'type' => ObjectManager::getInstance()->get(TokenUserContext::class),
                                    'sortOrder' => 10,
                                ],
                                'customerSessionUserContext' => [
                                    'type' => ObjectManager::getInstance()->get(CustomerSessionUserContext::class),
                                    'sortOrder' => 20,
                                ],
                                'guestUserContext' => [
                                    'type' => ObjectManager::getInstance()->get(GuestUserContext::class),
                                    'sortOrder' => 100,
                                ],
                            ]
                        ]),
                    ]),
                    ObjectManager::getInstance()->get(\Magento\CustomerGraphQl\Model\Context\AddCustomerGroupToContext::class),
                ]
            ])
            ->create();
    }
}
