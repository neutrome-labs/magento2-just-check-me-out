<?php

namespace PerspectiveTeam\JustCheckMeOut\ViewModel;

use Magento\Checkout\Model\Session;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteIdToMaskedQuoteId;

class QuoteViewModel implements ArgumentInterface
{


    public function __construct(
        private readonly Session                $checkoutSession,
        private readonly QuoteIdToMaskedQuoteId $quoteIdToMaskedQuoteId
    )
    {
    }

    /**
     * @return Quote|null
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getQuote(): ?Quote
    {
        return $this->checkoutSession->getQuote();
    }

    public function getMaskedQuoteId(): string
    {
        return $this->quoteIdToMaskedQuoteId->execute($this->getQuote()->getId());
    }
}
