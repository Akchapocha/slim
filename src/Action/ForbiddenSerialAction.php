<?php

namespace PriNikApp\FrontTest\Action;

use Exception;
use PriNikApp\FrontTest\Domain\Serial;
use PriNikApp\FrontTest\Domain\ServiceStatusMessage;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;
use PriNikApp\FrontTest\Service\PageService;
use PriNikApp\FrontTest\Service\SerialService;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class ForbiddenSerialAction
{
    /**
     * @var Serial[]
     */
    private array $serials;

    public function __construct(
        protected readonly Environment $view,
        private readonly PageService $pageService,
        private readonly SerialService $serialService
    ) {
        $this->serials = $this->serialService->getForbiddenSerialsArray();
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
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
                'menu'  => $this->pageService->getMenuTree(),
                'serials' => $this->serials
            ]
        );
        $response->getBody()->write($body);
        return $response;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function refreshForbidden(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $post = $request->getParsedBody();
        if (!isset($post['action']) or $post['action'] != 'refreshForbidden') {
            return $response->withStatus(404);
        }

        $response->getBody()->write(
            json_encode(
                [
                    'forbidden' => $this->getFreeSerialsHtml(
                        $this->serials['forbidden']
                    ),
                    'staged' => $this->getSerialsWithProductHtml(
                        $this->serials['staged']
                    )
                ]
            )
        );
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * @throws Exception
     */
    public function addForbidden(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $post = $request->getParsedBody();
        if (!isset($post['action']) or $post['action'] != 'addForbidden') {
            return $response->withStatus(404);
        }

        $this->serialService->addSerial($post['serial']);
        $response->getBody()->write(
            json_encode(['status' => ServiceStatusMessage::SerialAdded])
        );
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * @throws Exception
     */
    public function deleteForbidden(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $post = $request->getParsedBody();
        if (!isset($post['action']) or $post['action'] != 'deleteForbidden') {
            return $response->withStatus(404);
        }

        $this->serialService->deleteSerial($post['serial']);
        $response->getBody()->write(
            json_encode(
                ['status' => ServiceStatusMessage::SerialDeleted->value]
            )
        );
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    private function getFreeSerialsHtml(array $serials): string
    {
        return $this->view->render(
            'forbidden_serials/free_serials.html.twig',
            ['serials' => $serials]
        );
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    private function getSerialsWithProductHtml(array $serials): string
    {
        return $this->view->render(
            'forbidden_serials/serials_with_product.html.twig',
            ['serials' => $serials]
        );
    }
}
