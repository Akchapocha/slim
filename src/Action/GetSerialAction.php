<?php

namespace PriNikApp\FrontTest\Action;

use PriNikApp\FrontTest\Service\PageService;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class GetSerialAction
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
     */
    public function __invoke(
        RequestInterface $request,
        ResponseInterface $response,
        array $args = []
    ): ResponseInterface {
        $uri = substr($request->getUri()->getPath(), 1);

        if (!$this->pageService->checkAccess($uri)) {
            $body = $this->view->render('error.html.twig');
            $response->getBody()->write($body);
            return $response->withStatus(404);
        }

        $body = $this->view->render(
            $uri . '.html.twig',
            [
                'title' => $this->pageService->getTitle($uri),
                'menu' => $this->pageService->getMenuTree()
            ]
        );
        $response->getBody()->write($body);
        return $response;
    }
}
