<?php

declare(strict_types=1);

namespace NeutromeLabs\JustCheckMeOut\ViewModel;

use Magento\Customer\Api\AddressMetadataInterface;
use Magento\Customer\Helper\Address as AddressHelper;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Provides address field configuration for checkout forms.
 *
 * Aggregates Magento's scattered address configuration into a single,
 * JSON-serializable structure for frontend consumption.
 */
class AddressConfigProvider implements ArgumentInterface
{
    /**
     * Cached config array to avoid recomputation
     */
    private ?array $configCache = null;

    public function __construct(
        private readonly DirectoryHelper $directoryHelper,
        private readonly AddressHelper $addressHelper,
        private readonly AddressMetadataInterface $addressMetadata,
        private readonly SerializerInterface $serializer
    ) {
    }

    /**
     * Get all address field configuration as an associative array.
     *
     * Configuration keys:
     * - streetLines: Number of street address lines (1-4, default 2)
     * - optionalZipCountries: ISO2 country codes where postcode is optional
     * - requiredRegionCountries: ISO2 country codes where region/state is required
     * - displayAllRegions: Whether to show region field for all countries
     * - defaultCountry: Default country ISO2 code
     * - topCountries: Most used country codes (shown at top of dropdown)
     * - showVatField: Whether to display VAT/Tax ID field
     * - showCompanyField: Whether company field is visible
     * - showTelephoneField: Whether telephone field is visible
     * - showFaxField: Whether fax field is visible
     * - showPrefixField: Whether name prefix field is visible
     * - showSuffixField: Whether name suffix field is visible
     * - showMiddlenameField: Whether middle name field is visible
     *
     * @return array<string, mixed>
     */
    public function getConfig(): array
    {
        if ($this->configCache !== null) {
            return $this->configCache;
        }

        $this->configCache = [
            // Street field configuration
            'streetLines' => $this->getStreetLines(),

            // Postcode/ZIP configuration
            'optionalZipCountries' => $this->directoryHelper->getCountriesWithOptionalZip(),

            // Region/State configuration
            'requiredRegionCountries' => $this->directoryHelper->getCountriesWithStatesRequired(),
            'displayAllRegions' => $this->directoryHelper->isShowNonRequiredState(),

            // Country defaults
            'defaultCountry' => $this->directoryHelper->getDefaultCountry(),
            'topCountries' => $this->directoryHelper->getTopCountryCodes(),

            // Field visibility (from customer/address configuration)
            'showVatField' => $this->addressHelper->isVatAttributeVisible(),
            'showCompanyField' => $this->isAttributeVisible('company'),
            'showTelephoneField' => $this->isAttributeVisible('telephone'),
            'showFaxField' => $this->isAttributeVisible('fax'),
            'showPrefixField' => $this->isAttributeVisible('prefix'),
            'showSuffixField' => $this->isAttributeVisible('suffix'),
            'showMiddlenameField' => $this->isAttributeVisible('middlename'),
        ];

        return $this->configCache;
    }

    /**
     * Get configuration as JSON string for JavaScript consumption.
     *
     * @return string JSON-encoded configuration
     */
    public function getConfigJson(): string
    {
        return $this->serializer->serialize($this->getConfig());
    }

    /**
     * Get the number of street address lines.
     *
     * Configured via customer_address EAV attribute 'street' multiline_count.
     * Defaults to 2 lines, max 20.
     *
     * @return int Number of street lines (1-20)
     */
    public function getStreetLines(): int
    {
        try {
            return $this->addressHelper->getStreetLines();
        } catch (NoSuchEntityException|LocalizedException $e) {
            return 2; // Magento default
        }
    }

    /**
     * Check if postcode is optional for a specific country.
     *
     * @param string $countryCode ISO2 country code
     * @return bool True if postcode is optional
     */
    public function isPostcodeOptional(string $countryCode): bool
    {
        return $this->directoryHelper->isZipCodeOptional($countryCode);
    }

    /**
     * Check if region/state is required for a specific country.
     *
     * @param string $countryCode ISO2 country code
     * @return bool True if region is required
     */
    public function isRegionRequired(string $countryCode): bool
    {
        return $this->directoryHelper->isRegionRequired($countryCode);
    }

    /**
     * Check if an address attribute is visible.
     *
     * @param string $attributeCode Attribute code (e.g., 'company', 'telephone')
     * @return bool True if attribute is visible
     */
    private function isAttributeVisible(string $attributeCode): bool
    {
        try {
            return $this->addressHelper->isAttributeVisible($attributeCode);
        } catch (NoSuchEntityException|LocalizedException $e) {
            // Default visibility for standard attributes
            return match ($attributeCode) {
                'telephone' => true,
                'company', 'fax', 'prefix', 'suffix', 'middlename' => false,
                default => true,
            };
        }
    }
}
