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
        return $this->pageFactory->create();
    }
}
