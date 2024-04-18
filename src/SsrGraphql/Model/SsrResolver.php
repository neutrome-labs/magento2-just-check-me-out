<?php

namespace PerspectiveTeam\SsrGraphql\Model;

use GuzzleHttp\ClientFactory;

class SsrResolver
{

    public function __construct(
        private readonly ClientFactory $clientFactory,
    )
    {
    }

    /**
     * @param string $query
     * @param array $variables
     * @return array
     */
    public function resolve(string $query, array $variables = []): array
    {
        $client = $this->clientFactory->create(['config' => [
            'base_uri' => 'https://app.demo-just-check-me-out.test'
        ]]);

        $response = $client->request(
            'POST',
            '/graphql',
            [
                'json' => [
                    'query' => $query,
                    'variables' => $variables
                ]
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }
}
