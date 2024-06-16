<?php

namespace PerspectiveTeam\SsrGraphql\Model;

use Magento\Framework\App\Area;
use Magento\Framework\App\ObjectManager\ConfigLoader;
use Magento\Framework\App\ObjectManagerFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\QueryProcessor;
use Magento\Framework\GraphQl\Schema;
use Magento\Framework\GraphQl\Schema\SchemaGeneratorInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\GraphQl\Model\Query\ContextFactory;
use PerspectiveTeam\SsrGraphql\Api\ResolverInterface;
use PerspectiveTeam\SsrGraphql\Service\ConfigManager;

class Resolver implements ResolverInterface
{

    private ?ObjectManagerInterface $graphQlObjectManager = null;

    private ?QueryProcessor $queryProcessor = null;

    private ?SchemaGeneratorInterface $schemaGenerator = null;

    private ?Schema $schema = null;

    private ?ContextFactory $contextFactory = null;


    public function __construct(
        private readonly ObjectManagerFactory $objectManagerFactory,
        private readonly ConfigLoader         $objectManagerConfigLoader,
        private readonly ConfigManager        $configManager
    )
    {
    }


    private function getGraphqlObjectManager(): ObjectManagerInterface
    {
        if (!$this->graphQlObjectManager) {
            $this->graphQlObjectManager = $this->objectManagerFactory->create([]);
            $this->graphQlObjectManager->configure($this->objectManagerConfigLoader->load(Area::AREA_GRAPHQL));
            $this->graphQlObjectManager->get(\Magento\Framework\App\State::class)->setAreaCode(Area::AREA_GRAPHQL);
        }

        return $this->graphQlObjectManager;
    }

    private function getQueryProcessor(): QueryProcessor
    {
        if (!$this->queryProcessor) {
            $this->queryProcessor = $this->getGraphqlObjectManager()->get(QueryProcessor::class);
        }
        return $this->queryProcessor;
    }

    private function getSchemaGenerator(): SchemaGeneratorInterface
    {
        if (!$this->schemaGenerator) {
            $this->schemaGenerator = $this->getGraphqlObjectManager()->get(SchemaGeneratorInterface::class);
        }
        return $this->schemaGenerator;
    }

    private function getSchema(): Schema
    {
        if (!$this->schema) {
            $this->schema = $this->getSchemaGenerator()->generate();
        }
        return $this->schema;
    }

    private function getContextFactory(): ContextFactory
    {
        if (!$this->contextFactory) {
            $this->contextFactory = $this->getGraphqlObjectManager()->get(ContextFactory::class);
        }
        return $this->contextFactory;
    }

    /**
     * @throws GraphQlInputException
     * @throws LocalizedException
     */
    public function resolve(string $query, array $variables = []): array
    {
        $microtime = microtime(true);

        $data = $this->getQueryProcessor()->process(
            $this->getSchema(),
            $query,
            $this->getContextFactory()->create(),
            $variables
        );

        if ($this->configManager->getDebug()) {
            $duration = microtime(true) - $microtime;
            $data['__debug'] = [
                'resolve_total_sec' => $duration,
            ];
        }

        return $data;
    }
}
