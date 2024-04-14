<?php

namespace PerspectiveTeam\SsrGraphql\Model;

use Magento\Framework\GraphQl\Query\QueryProcessor;
use Magento\Framework\GraphQl\Schema;
use Magento\Framework\GraphQl\Schema\SchemaGeneratorInterface;

class SsrResolver
{

    private ?Schema $_schema = null;

    public function __construct(
        private readonly SchemaGeneratorInterface $schemaGenerator,
        private readonly SsrContextFactory        $contextFactory,
        private readonly QueryProcessor           $queryProcessor,
    )
    {
    }

    private function getSchema(): Schema
    {
        if (!$this->_schema) {
            $this->_schema = $this->schemaGenerator->generate();
        }
        return $this->_schema;
    }

    /**
     * @param string $query
     * @param array $variables
     * @return array
     * @throws \Magento\Framework\GraphQl\Exception\GraphQlInputException
     */
    public function resolve(string $query, array $variables = []): array
    {
        return $this->queryProcessor->process(
            $this->getSchema(),
            $query,
            $this->contextFactory->create(),
            $variables
        );
    }
}
