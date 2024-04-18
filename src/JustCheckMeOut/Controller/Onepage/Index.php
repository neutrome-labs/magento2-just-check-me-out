<?php

namespace PerspectiveTeam\JustCheckMeOut\Controller\Onepage;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;

class Index implements HttpGetActionInterface
{

    private PageFactory $pageFactory;

    public function __construct(
        PageFactory $pageFactory
    )
    {
        $this->pageFactory = $pageFactory;
    }

    /** @inheirtDoc */
    public function execute()
    {
        $page = $this->pageFactory->create();
        $page->getLayout()->getUpdate();

        $theme = 'minimal'; // todo: from config
        $page->getLayout()->getUpdate()->addHandle("psteamjustcheckmeout_onepage_theme_$theme");

        return $page;
    }
}
