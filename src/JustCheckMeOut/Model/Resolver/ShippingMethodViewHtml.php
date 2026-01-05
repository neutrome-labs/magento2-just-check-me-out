<?php

namespace NeutromeLabs\JustCheckMeOut\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use NeutromeLabs\JustCheckMeOut\Service\AdditionalViewRenderer;
use NeutromeLabs\JustCheckMeOut\Service\ShippingMethodAdditionalViewRegistry;

class ShippingMethodViewHtml implements ResolverInterface
{

    public function __construct(
        private readonly ShippingMethodAdditionalViewRegistry $additionalViewRegistry,
        private readonly AdditionalViewRenderer $additionalViewRenderer,
    )
    {
    }

    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $additionalView = $this->additionalViewRegistry->get($value['carrier_code'] . '_' . $value['method_code']);
        return $additionalView ? $this->additionalViewRenderer->render($additionalView) : null;
    }
}
