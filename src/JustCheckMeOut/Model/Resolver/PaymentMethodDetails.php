<?php

namespace PerspectiveTeam\JustCheckMeOut\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use PerspectiveTeam\JustCheckMeOut\Service\AdditionalViewRenderer;
use PerspectiveTeam\JustCheckMeOut\Service\PaymentMethodAdditionalViewRegistry;

class PaymentMethodDetails implements ResolverInterface
{

    public function __construct(
        private readonly PaymentMethodAdditionalViewRegistry $additionalViewRegistry,
        private readonly AdditionalViewRenderer $additionalViewRenderer,
    )
    {
    }

    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $paymentMethodCode = $value['code'];
        $additionalView = $this->additionalViewRegistry->get($paymentMethodCode);
        return $additionalView ? $this->additionalViewRenderer->render($additionalView) : null;
    }
}
