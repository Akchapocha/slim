<?php

namespace PriNikApp\FrontTest\Action;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class ErrorAction
{
    /**
     * @param Environment $view
     */
    public function __construct(
        protected readonly Environment $view
    ) {
    }

    /**
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array             $args
     *
     * @return ResponseInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(
        RequestInterface $request,
        ResponseInterface $response,
        array $args = []
    ): ResponseInterface {
        $body = $this->view->render(
            'error.html.twig'
        );
        $response->getBody()->write($body);
        return $response->withStatus(404);
    }
}
