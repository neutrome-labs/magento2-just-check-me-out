<?php

namespace PerspectiveTeam\SsrGraphql\Model\Framework;

use Magento\Framework\App\ObjectManagerFactory;

class SsrGraphqlObjectManagerFactory extends ObjectManagerFactory
{

    protected $envFactoryClassName = SsrGraphqlEnvironmentFactory::class;
}
