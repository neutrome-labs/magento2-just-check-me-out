<?php

namespace PerspectiveTeam\SsrGraphql\Model;

use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

class Resolver
{

    public function __construct(
        private readonly StoreManagerInterface $storeManager,
        private readonly ClientFactory $clientFactory,
        private readonly RequestInterface $request,
        private readonly Json $json
    )
    {
    }

    public function getGraphqlBaseUrl(): string
    {
        // todo: separate config
        return 'https://app.demo-just-check-me-out.test/';
        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_WEB);
    }

    public function prepareHeaders(): array
    {
        $headers = [];
        foreach ($this->request->getHeaders() as $header) {
            $headers[strtolower($header->getFieldName())] = $header->getFieldValue();
        }

        $headers = array_diff_key($headers, array_flip([
            'content-type',
            'content-length',
        ]));

        return array_merge($headers, [
            'content-type' => 'application/json'
        ]);
    }

    /**
     * @param string $query
     * @param array $variables
     * @return array
     * @throws GuzzleException
     */
    public function resolve(string $query, array $variables = []): array
    {
        $client = $this->clientFactory->create(['config' => [
            'base_uri' => $this->getGraphqlBaseUrl(),
        ]]);

        $response = $client->request(
            'POST',
            '/graphql',
            [
                // 'headers' => $this->prepareHeaders(),
                'json' => [
                    'query' => $query,
                    'variables' => $variables
                ]
            ]
        );

        return $this->json->unserialize($response->getBody()->getContents());
    }
}
