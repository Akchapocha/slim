<?php

namespace PriNikApp\FrontTest\Action;

use PriNikApp\FrontTest\Service\PageService;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Exception;

final class MainPageAction
{
    /**
     * @param Environment $view
     * @param PageService $pageService
     */
    public function __construct(
        protected readonly Environment $view,
        private readonly PageService $pageService
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
     * @throws Exception
     */
    public function __invoke(
        RequestInterface $request,
        ResponseInterface $response,
        array $args = []
    ): ResponseInterface {
        $body = $this->view->render(
            'main.html.twig',
            [
                'title' => 'Главная',
                'menu' => $this->pageService->getMenuTree()
            ]
        );
        $response->getBody()->write($body);
        return $response;
    }
}
