<?php

namespace Softspring\CmsBundle\Twig\Extension;

use Psr\Log\LoggerInterface;
use Softspring\CmsBundle\Manager\RouteManagerInterface;
use Softspring\CmsBundle\Model\Route;
use Softspring\CmsBundle\Model\RouteInterface;
use Softspring\CmsBundle\Model\RoutePathInterface;
use Softspring\CmsBundle\Model\SiteInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RouterExtension extends AbstractExtension
{
    protected RequestStack $requestStack;
    protected RouteManagerInterface $routeManager;
    protected ?LoggerInterface $cmsLogger;

    /**
     * @param RequestStack          $requestStack
     * @param RouteManagerInterface $routeManager
     */
    public function __construct(RequestStack $requestStack, RouteManagerInterface $routeManager, ?LoggerInterface $cmsLogger)
    {
        $this->requestStack = $requestStack;
        $this->routeManager = $routeManager;
        $this->cmsLogger = $cmsLogger;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('sfs_cms_url', [$this, 'getUrl']),
            new TwigFunction('sfs_cms_path', [$this, 'getPath']),
            new TwigFunction('sfs_cms_route_attr', [$this, 'getRouteAttributes']),
        ];
    }

    /**
     * @param string|RouteInterface $routeOrName
     *
     * @throws \Exception
     */
    public function getUrl($routeOrName): string
    {
        $route = $routeOrName instanceof RouteInterface ? $routeOrName : $this->getRoute($routeOrName);

        if (!$route) {
            return $this->isPreview() ? 'javascript:confirm(\'Esto es una previsualización!\')' : '#';
        }

        $site = $this->getSite();

        return $this->getRoutePath($route, $site);
    }

    /**
     * @param string|RouteInterface $routeOrName
     *
     * @throws \Exception
     */
    public function getPath($routeOrName): string
    {
        $route = $routeOrName instanceof RouteInterface ? $routeOrName : $this->getRoute($routeOrName);

        if (!$route) {
            return $this->isPreview() ? 'javascript:confirm(\'Esto es una previsualización!\')' : '#';
        }

        $site = $this->getSite();

        return $this->getRoutePath($route, $site);
    }

    /**
     * @param string|RouteInterface $routeOrName
     *
     * @throws \Exception
     */
    public function getRouteAttributes($routeOrName): string
    {
        $route = $routeOrName instanceof RouteInterface ? $routeOrName : $this->getRoute($routeOrName);

        if (!$route) {
            return '';
        }

        return ''; // TODO check page to return noindex and nofollow attributes
    }


    protected function getRoutePath(Route $route, $site): string
    {
        /** @var RoutePathInterface $path */
        $path = $route->getPaths()->first();

        return $path->getPath();
    }

    protected function getRoute($routeName): ?RouteInterface
    {
        if ($this->isPreview()) {
            return null;
        }

        if (!$routeName) {
            $this->cmsLogger && $this->cmsLogger->error('Empty route');
            return null;
        }

        $route = $this->routeManager->getRepository()->findOneById($routeName);

        if (!$route) {
            $this->cmsLogger && $this->cmsLogger->error(sprintf('Route %s not found', $routeName));
        }

        return $route;
    }

    protected function getSite(): ?SiteInterface
    {
        $request = $this->requestStack->getCurrentRequest();

        /** @var SiteInterface $site */
        $site = $request->attributes->get('_site');

//        if (!$site) {
//            throw new \Exception('Can not generate route because no site is selected');
//        }

        return $site;
    }

    protected function isPreview(): bool
    {
        $request = $this->requestStack->getCurrentRequest();

        return $request->attributes->has('_cms_preview');
    }
}