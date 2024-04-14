<?php

namespace PerspectiveTeam\JustCheckMeOut\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use PerspectiveTeam\SsrGraphql\Model\SsrResolver;

class SsrGraphqlViewModel implements ArgumentInterface
{

    public function __construct(
        private readonly SsrResolver $resolver,
    )
    {
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
window.psteamJustCheckMeOutApiV1.fn.createSsrGqlStub($query, $variables, $response, $options)
JS;
    }
}
