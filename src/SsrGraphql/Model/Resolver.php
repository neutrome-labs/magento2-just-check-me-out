<?php

namespace PerspectiveTeam\SsrGraphql\Model;

use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Async\DeferredInterface;
use Magento\Framework\HTTP\AsyncClient\Request;
use Magento\Framework\HTTP\AsyncClientInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

class Resolver
{

    public function __construct(
        private readonly StoreManagerInterface $storeManager,
        private readonly ClientFactory         $clientFactory,
        private readonly AsyncClientInterface  $asyncClient,
        private readonly RequestInterface      $request,
        private readonly Json                  $json
    )
    {
    }

    public function getGraphqlBaseUrl(): string
    {
        // todo: separate config
        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_WEB);
    }

    public function prepareHeaders(): array
    {
        $headers = [];
        foreach ($this->request->getHeaders()->toArray() as $h => $v) {
            $headers[strtolower($h)] = $v;
        }

        $headers = array_diff_key($headers, array_flip([
            'content-type',
            'content-length',
        ]));

        return array_merge($headers, [
            'content-type' => 'application/json'
        ]);
    }

    public function deferResolve(string $query, array $variables = []): DeferredInterface
    {
        return $this->asyncClient->request(
            new Request(
                $this->getGraphqlBaseUrl() . 'graphql',
                'POST',
                [
                    'content-type' => 'application/json',
                ], // todo: fix 10x speed degrade $this->prepareHeaders(),
                $this->json->serialize([
                    'query' => $query,
                    'variables' => $variables
                ])
            )
        );
    }
}
