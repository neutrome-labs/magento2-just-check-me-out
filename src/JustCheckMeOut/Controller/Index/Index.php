<?php

namespace PerspectiveTeam\JustCheckMeOut\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\View\Result\PageFactory;
use PerspectiveTeam\JustCheckMeOut\Service\ConfigManager;
use PerspectiveTeam\JustCheckMeOut\ViewModel\QuoteViewModel;

class Index implements HttpGetActionInterface
{

    public function __construct(
        private readonly ConfigManager $configManager,
        private readonly QuoteViewModel $quoteViewModel,
        private readonly  RedirectFactory $resultRedirectFactory,
        private readonly PageFactory $pageFactory
    )
    {
    }

    /** @inheirtDoc */
    public function execute()
    {
        if (!$this->configManager->isEnabled()) {
            // return 404;
        }

        if (!$this->quoteViewModel->getQuote() || !$this->quoteViewModel->getQuote()->getItemsCount()){
            return $this->resultRedirectFactory->create()->setPath('checkout/cart');
        }

        $page = $this->pageFactory->create();
        $page->getLayout()->getUpdate();

        $theme = $this->configManager->getTheme();
        $page->getLayout()->getUpdate()->addHandle("justcheckmeout_theme_$theme");

        return $page;
    }
}
