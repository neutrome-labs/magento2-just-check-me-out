<?php

namespace PerspectiveTeam\JustCheckMeOut\Plugin;

use Magento\Checkout\Controller\Index\Index;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

class CheckoutControllerPlugin
{

    public const FORWARD_URL_PATH = 'psteamjustcheckmeout/onepage/index';

    public function __construct(
        private readonly ResultFactory $resultFactory
    )
    {
    }

    /**
     * @param Index $subject
     * @param callable $proceed
     * @return ResultInterface
     */
    public function aroundExecute(Index $subject, callable $proceed): ResultInterface
    {
        [$module, $controller, $action] = explode('/', self::FORWARD_URL_PATH);
        $resultForward = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);

        return $resultForward->setModule($module)->setController($controller)->forward($action);
    }
}
