<?php

namespace PriNikApp\FrontTest\Infrastructure\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 *
 */
class AssetExtension extends AbstractExtension
{

    /**
     * @param array $serverParams
     * @param TwigFunctionFactory $twigFunctionFactory
     */
    public function __construct(
        private readonly array $serverParams,
        private readonly TwigFunctionFactory $twigFunctionFactory
    ) {
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            $this->twigFunctionFactory->create(
                'url',
                [$this, 'getUrl']
            ),
            $this->twigFunctionFactory->create(
                'relative_url',
                [$this, 'getRelativeUrl']
            ),
            $this->twigFunctionFactory->create(
                'base_url',
                [$this, 'getBaseUrl']
            ),
            $this->twigFunctionFactory->create(
                'asset_img',
                [$this, 'assetImg']
            ),
            $this->twigFunctionFactory->create(
                'asset_js',
                [$this, 'assetJS']
            ),
            $this->twigFunctionFactory->create(
                'asset_css',
                [$this, 'assetCSS']
            ),
        ];
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return ($this->serverParams['REQUEST_SCHEME'] ?? 'http') . '://'
            . $this->serverParams['HTTP_HOST'] . '/';
    }

    /**
     * @param string $url
     *
     * @return string
     */
    public function getRelativeUrl(string $url): string
    {
        return $this->getBaseUrl() .
            ltrim($this->serverParams['REQUEST_URI'], "/")
            . '/' . ltrim($url, "/");
    }


    /**
     * @param string $url
     *
     * @return string
     */
    public function getUrl(string $url): string
    {
        return $this->getBaseUrl() . ltrim($url, "/");
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function assetImg(string $path): string
    {
        return $this->getUrl('img/' . ltrim($path, "/"));
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function assetCSS(string $path): string
    {
        return $this->getUrl('css/' . ltrim($path, "/"));
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function assetJS(string $path): string
    {
        return $this->getUrl('js/' . ltrim($path, "/"));
    }
}
