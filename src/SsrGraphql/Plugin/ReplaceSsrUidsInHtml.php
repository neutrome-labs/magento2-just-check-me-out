<?php

namespace PerspectiveTeam\SsrGraphql\Plugin;

use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\LayoutInterface;
use PerspectiveTeam\SsrGraphql\ViewModel\SsrGraphqlViewModel;

class ReplaceSsrUidsInHtml
{

    public function __construct(
        private readonly SsrGraphqlViewModel $viewModel,
        private readonly Json $json
    )
    {
    }

    public function afterGetOutput(LayoutInterface $layout, string $output): string
    {
        return preg_replace_callback(
            '/%(PSTEAM_GQL_.*?)%/',
            function ($matches) {
                try {
                    $data = $this->json->unserialize($this->viewModel->getDeferred($matches[1])->get()->getBody());
                } catch (\Exception $e) {
                    $data = [
                        'errors' => [
                            [
                                'message' => "An error occurred while resolving the query on the server: {$e->getMessage()}",
                            ]
                        ]
                    ];
                }

                return $this->json->serialize($data);
            },
            $output
        );
    }
}
