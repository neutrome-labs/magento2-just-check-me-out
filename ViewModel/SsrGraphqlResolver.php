<?php

namespace PerspectiveTeam\JustCheckMeOut\ViewModel;

use Magento\Framework\GraphQl\Query\Fields as QueryFields;
use Magento\Framework\GraphQl\Query\QueryProcessor;
use Magento\Framework\GraphQl\Schema;
use Magento\Framework\GraphQl\Schema\SchemaGeneratorInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use PerspectiveTeam\JustCheckMeOut\Model\SsrGraphqlContextFactory;

class SsrGraphqlResolver implements ArgumentInterface
{

    private ?Schema $_schema = null;

    public function __construct(
        private readonly QueryFields $queryFields,
        private readonly SchemaGeneratorInterface $schemaGenerator,
        private readonly SsrGraphqlContextFactory $contextFactory,
        private readonly QueryProcessor $queryProcessor,
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
        $this->queryFields->setQuery($query, $variables); // originated in module-graphq\Contoroller\GraphQl
        return $this->queryProcessor->process(
            $this->schemaGenerator->generate(),
            $query,
            $this->contextFactory->create(),
            $variables
        );
    }

    public function createJsSnippet(string $query, array $variables = []): string
    {
        $ssrVariables = json_encode($variables);
        $ssrResponse = json_encode($this->resolve($query, $variables));
        return <<<JS
window.psteamJustCheckMeOut.fn.makeSsrGql(
    `$query`,
    $ssrVariables,
    false,
    $ssrResponse
)
JS;
    }
}
