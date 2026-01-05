<?php

namespace NeutromeLabs\JustCheckMeOut\ViewModel;

use Magento\Checkout\Model\Session;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteIdMaskFactory;
use Magento\Quote\Model\QuoteIdToMaskedQuoteId;
use Magento\Quote\Model\ResourceModel\Quote\QuoteIdMask as QuoteIdMaskResourceModel;

class QuoteViewModel implements ArgumentInterface
{

    private ?Quote $quote = null;

    public function __construct(
        private readonly Session                  $checkoutSession,
        private readonly QuoteIdMaskFactory       $quoteIdMaskFactory,
        private readonly QuoteIdToMaskedQuoteId   $quoteIdToMaskedQuoteId,
        private readonly QuoteIdMaskResourceModel $quoteIdMaskResourceModel
    )
    {
    }

    public function getQuote(): ?Quote
    {
        if (!$this->quote) {
            try {
                $this->quote = $this->checkoutSession->getQuote();
            } catch (\Exception $e) {
                return null;
            }
        }
        return $this->quote;
    }

    public function getMaskedQuoteId(): ?string
    {
        $quote = $this->getQuote();
        if (!$quote || !$quote->getId()) {
            return null;
        }

        try {
            $maskedId = $this->quoteIdToMaskedQuoteId->execute($quote->getId());
        } catch (NoSuchEntityException $e) {
            $maskedId = '';
        }
        if ($maskedId === '') {
            $quoteIdMask = $this->quoteIdMaskFactory->create();
            $quoteIdMask->setQuoteId($quote->getId());
            $this->quoteIdMaskResourceModel->save($quoteIdMask);
            $maskedId = $this->quoteIdToMaskedQuoteId->execute($quote->getId());
        }

        return $maskedId;
    }
}
