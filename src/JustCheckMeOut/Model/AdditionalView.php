<?php

namespace NeutromeLabs\JustCheckMeOut\Model;

use Magento\Framework\DataObject;
use NeutromeLabs\JustCheckMeOut\Api\AdditionalViewInterface;

class AdditionalView extends DataObject implements AdditionalViewInterface
{

    public function __construct(?string $kind, mixed $argument, array $data = [])
    {
        parent::__construct(array_merge([
            'kind' => $kind,
            'argument' => $argument,
        ], $data));
    }

    public function isOptimistic(): bool
    {
        return (bool)$this->getData('optimistic');
    }

    public function getKind(): string
    {
        return $this->getData('kind');
    }

    public function getArgument(): mixed
    {
        return $this->getData('argument');
    }
}
