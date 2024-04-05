<?php

namespace PerspectiveTeam\JustCheckMeOut\Service;

use Magento\Store\Model\App\Emulation;
use Magento\Store\Model\StoreManagerInterface;
use PerspectiveTeam\JustCheckMeOut\Api\AdditionalViewInterface;
use PerspectiveTeam\JustCheckMeOut\ViewModel\HeadlessComponentRenderer;
use PerspectiveTeam\JustCheckMeOut\ViewModel\QuoteViewModel;

class AdditionalViewRenderer
{

    public function __construct(
        private readonly StoreManagerInterface $storeManager,
        private readonly Emulation $emulation,
        private readonly QuoteViewModel $quoteViewModel,
        private readonly HeadlessComponentRenderer $headlessComponentRenderer,
    )
    {
    }

    public function render(AdditionalViewInterface $view): string
    {
        switch ($view->getKind()) {
            case AdditionalViewInterface::KIND_RENDER:
                $classData = explode('::', $view->getArgument());
                return \Magento\Framework\App\ObjectManager::getInstance()
                    ->get($classData[1])
                    ->{$classData[1]}();
            case AdditionalViewInterface::KIND_BLOCK:
                $class = array_key_exists('block', $view->getArgument())
                    ? $view->getArgument()['block']
                    : \Magento\Framework\View\Element\Template::class;
                $template = array_key_exists('template', $view->getArgument())
                    ? $view->getArgument()['template']
                    : null;
                $data = array_key_exists('data', $view->getArgument())
                    ? $view->getArgument()['data']
                    : [];

                try {
                    $this->emulation->startEnvironmentEmulation($this->storeManager->getStore()->getId(), 'frontend');
                    return \Magento\Framework\App\ObjectManager::getInstance()
                        ->create($class)
                        ->setTemplate($template)
                        ->setData(array_merge([
                            'area' => 'frontend',
                            'quoteViewModel' => $this->quoteViewModel,
                            'headlessComponentRenderer' => $this->headlessComponentRenderer,
                        ], $data))
                        ->toHtml();
                } finally {
                    $this->emulation->stopEnvironmentEmulation();
                }
            case AdditionalViewInterface::KIND_PLAIN_HTML:
                return $view->getArgument() ?? '';
            default:
                return '';
        }
    }
}
