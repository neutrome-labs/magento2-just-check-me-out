<?php

namespace NeutromeLabs\JustCheckMeOut\Plugin;

use Magento\Checkout\Controller\Index\Index;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use NeutromeLabs\JustCheckMeOut\Service\ConfigManager;

class CheckoutControllerPlugin
{

    public const FORWARD = 'justcheckmeout/index/index';

    public function __construct(
        private readonly ConfigManager $configManager,
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
        if (!$this->configManager->replaceDefault()) {
            return $proceed();
        }

        [$module, $controller, $action] = explode('/', self::FORWARD);
        $resultForward = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);

        return $resultForward->setModule($module)->setController($controller)->forward($action);
    }
}
