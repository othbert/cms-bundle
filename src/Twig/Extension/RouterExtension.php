<?php

namespace Softspring\CmsBundle\Twig\Extension;

use Softspring\CmsBundle\Router\UrlGenerator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RouterExtension extends AbstractExtension
{
    protected UrlGenerator $urlGenerator;

    public function __construct(UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            // old functions, to be deleted soon
            new TwigFunction('sfs_cms_url', [$this->urlGenerator, 'getUrl'], ['deprecated' => true, 'alternate' => 'url']),
            new TwigFunction('sfs_cms_path', [$this->urlGenerator, 'getPath'], ['deprecated' => true, 'alternate' => 'path']),
            new TwigFunction('sfs_cms_url_fixed', [$this->urlGenerator, 'getUrlFixed'], ['deprecated' => true, 'alternate' => 'sfs_cms_route_path_url']),
            new TwigFunction('sfs_cms_path_fixed', [$this->urlGenerator, 'getPathFixed'], ['deprecated' => true, 'alternate' => 'sfs_cms_route_path_path']),
            // valid functions
            new TwigFunction('sfs_cms_route_path_url', [$this->urlGenerator, 'getUrlFixed']),
            new TwigFunction('sfs_cms_route_path_path', [$this->urlGenerator, 'getPathFixed']),
            new TwigFunction('sfs_cms_route_attr', [$this->urlGenerator, 'getRouteAttributes']),
        ];
    }
}
