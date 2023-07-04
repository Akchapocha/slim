<?php

namespace PriNikApp\FrontTest\Action;

use PriNikApp\FrontTest\Service\PageService;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use PriNikApp\FrontTest\Domain\ServiceStatusMessage as Message;

final class AddUsersAction
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
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
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


    public function addUser(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $post = $request->getParsedBody();
        if (!isset($post['action'])) {
            return $response->withStatus(404);
        }

        $response->getBody()->write(json_encode(['status' => Message::NeedConnection]));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
