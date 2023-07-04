<?php

namespace PriNikApp\FrontTest\Action;

use PriNikApp\FrontTest\Service\AccessService;
use PriNikApp\FrontTest\Service\UserService;
use PriNikApp\FrontTest\Service\PageService;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use PriNikApp\FrontTest\Domain\ServiceStatusMessage as Message;
use Exception;

final class AccessListAction
{
    /**
     * @param Environment $view
     * @param UserService $userService
     * @param PageService $pageService
     * @param AccessService $accessService
     */
    public function __construct(
        protected readonly Environment $view,
        private readonly UserService $userService,
        private readonly PageService $pageService,
        private readonly AccessService $accessService
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

        $response->getBody()->write($this->getBody($uri));
        return $response;
    }

    /**
     * @throws Exception
     */
    public function setAccess(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $post = $request->getParsedBody();
        if (!isset($post['action']) or $post['action'] !== 'setAccess') {
            return $response->withStatus(404);
        }

        $this->accessService->setAccess($post);
        $response->getBody()->write(json_encode(['status' => Message::AccessUpdated->value]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getAccess(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $post = $request->getParsedBody();
        if (!isset($post['action']) or $post['action'] !== 'getAccess') {
            return $response->withStatus(404);
        }

        $accessList = $this->accessService->getAccessIdList($post['idUser']);
        $response->getBody()->write(json_encode($accessList));
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * @param $uri
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function getBody($uri): string
    {
        return $this->view->render(
            $uri . '.html.twig',
            [
                'title' => $this->pageService->getTitle($uri),
                'menu' => $this->pageService->getMenuTree(),
                'users' => $this->userService->getUsersTree(),
                'pages' => $this->pageService->getPagesTree()
            ]
        );
    }
}
