<?php

namespace PerspectiveTeam\JustCheckMeOut\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use PerspectiveTeam\JustCheckMeOut\Service\AdditionalViewRenderer;
use PerspectiveTeam\JustCheckMeOut\Service\ShippingMethodAdditionalViewRegistry;

class ShippingMethodDetailsHtml implements ResolverInterface
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
