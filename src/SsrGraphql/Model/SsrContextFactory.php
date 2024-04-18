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
            ->get(\Magento\GraphQl\Model\Query\ContextFactory::class)
            ->create();
    }
}
