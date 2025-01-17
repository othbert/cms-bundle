<?php

namespace Softspring\CmsBundle\Twig\Extension;

use Softspring\CmsBundle\Model\RoutePathInterface;
use Softspring\CmsBundle\Routing\UrlGenerator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TranslateExtension extends AbstractExtension
{
    protected RequestStack $requestStack;
    protected array $enabledLocales;
    protected UrlGenerator $cmsUrlGenerator;
    protected UrlGeneratorInterface $symfonyUrlGenerator;

    public function __construct(RequestStack $requestStack, array $enabledLocales, UrlGenerator $cmsUrlGenerator, UrlGeneratorInterface $symfonyUrlGenerator)
    {
        $this->requestStack = $requestStack;
        $this->enabledLocales = $enabledLocales;
        $this->cmsUrlGenerator = $cmsUrlGenerator;
        $this->symfonyUrlGenerator = $symfonyUrlGenerator;
    }

    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('sfs_cms_trans', [$this, 'translate'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('sfs_cms_available_locales', [$this, 'getAvailableLocales']),
            new TwigFunction('sfs_cms_alternate_urls', [$this, 'getAlternateUrls']),
            new TwigFunction('sfs_cms_locale_paths', [$this, 'getLocalePaths']),
        ];
    }

    public function translate(?array $translatableText): string
    {
        if (null === $translatableText) {
            return '';
        }

        $request = $this->requestStack->getCurrentRequest();

        if (isset($translatableText[$request->getLocale()])) {
            return $translatableText[$request->getLocale()];
        }

        if (isset($translatableText[$request->getDefaultLocale()])) {
            return $translatableText[$request->getDefaultLocale()];
        }

        return '';
    }

    public function getAvailableLocales(): array
    {
        return $this->enabledLocales;
    }

    public function getAlternateUrls(): array
    {
        $request = $this->requestStack->getCurrentRequest();

        /** @var ?RoutePathInterface $routePath */
        $routePath = $request->attributes->get('routePath');

        $alternates = [];

        foreach ($this->enabledLocales as $locale) {
            if ($routePath) {
                $hasLocalizedRoutePath = (bool) $routePath->getRoute()->getPaths()->filter(fn (RoutePathInterface $routePath) => $routePath->getLocale() == $locale)->count();

                if (!$hasLocalizedRoutePath) {
                    continue;
                }

                $url = $this->cmsUrlGenerator->getUrl($routePath->getRoute(), $locale);

                if ('#' === $url) {
                    continue;
                }

                $alternates[$locale] = $url;
            } else {
                $routeName = $request->attributes->get('_route');
                $routeParams = $request->attributes->get('_route_params');
                $routeParams['_locale'] = $locale;
                unset($routeParams['_sfs_cms_locale']);
                unset($routeParams['_sfs_cms_locale_path']);

                $alternates[$locale] = $routeName ? $this->symfonyUrlGenerator->generate($routeName, $routeParams, UrlGeneratorInterface::ABSOLUTE_URL) : '#';
            }
        }

        return $alternates;
    }

    public function getLocalePaths(string $defaultRoute = null): array
    {
        $request = $this->requestStack->getCurrentRequest();

        /** @var ?RoutePathInterface $routePath */
        $routePath = $request->attributes->get('routePath');

        $localePaths = [];

        foreach ($this->enabledLocales as $locale) {
            if ($routePath) {
                if (isset($localePaths[$locale])) {
                    continue;
                }

                $hasLocalizedRoutePath = (bool) $routePath->getRoute()->getPaths()->filter(fn (RoutePathInterface $routePath) => $routePath->getLocale() == $locale)->count();

                if ($hasLocalizedRoutePath) {
                    $localePaths[$locale] = $this->cmsUrlGenerator->getPath($routePath->getRoute(), $locale);
                } else {
                    $localePaths[$locale] = $defaultRoute ? $this->cmsUrlGenerator->getPath($defaultRoute, $locale) : '#';
                }
            } else {
                $routeName = $request->attributes->get('_route');
                $routeParams = $request->attributes->get('_route_params');
                $routeParams['_locale'] = $locale;
                unset($routeParams['_sfs_cms_locale']);
                unset($routeParams['_sfs_cms_locale_path']);

                $localePaths[$locale] = $this->symfonyUrlGenerator->generate($routeName, $routeParams, UrlGeneratorInterface::ABSOLUTE_PATH);
            }
        }

        return $localePaths;
    }
}
