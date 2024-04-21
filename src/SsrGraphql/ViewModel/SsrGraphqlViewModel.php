<?php

namespace PerspectiveTeam\SsrGraphql\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use PerspectiveTeam\SsrGraphql\Model\Resolver;

class SsrGraphqlViewModel implements ArgumentInterface
{

    public function __construct(
        private readonly Resolver $resolver,
    )
    {
    }

    public function getBaseUrl(): string
    {
        return $this->resolver->getGraphqlBaseUrl();
    }

    public function makeSsrGqlCall(string $query, array $variables = [], $options = null): string
    {
        try {
            $response = $this->resolver->resolve($query, $variables);
        } catch (\Exception $e) {
            $text = $e->getMessage();
            $response = [
                'errors' => [
                    [
                        'message' => "An error occurred while resolving the query on the server: $text",
                    ]
                ]
            ];
        }

        $response = json_encode($response);
        $query = json_encode($query);
        $variables = json_encode($variables);
        $options = $options !== null ? json_encode($options) : '';

        return <<<JS
window.createMagento2SsrGqlStub($query, $variables, $response, $options)
JS;
    }
}
