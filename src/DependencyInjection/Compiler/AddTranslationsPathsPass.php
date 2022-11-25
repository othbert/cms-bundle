<?php

namespace Softspring\CmsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Finder\Finder;

class AddTranslationsPathsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $translatorDefinition = $container->getDefinition('translator.default');
//        $loaderIds = $translatorDefinition->getArgument(4);

        foreach ($container->getParameter('sfs_cms.collections') as $collectionPath) {
            // add modules translations if exists
            $modulesPath = $container->getParameter('kernel.project_dir').'/'.trim($collectionPath, '/').'/modules';
            if (!is_dir($modulesPath)) {
                continue;
            }

            foreach ((new Finder())->directories()->in("$modulesPath")->in('*')->name('translations') as $transDirectory) {
                foreach ((new Finder())->in($transDirectory->getRealPath())->files() as $file) {
                    $fileNameParts = explode('.', $file->getBasename());
                    $format = array_pop($fileNameParts);
                    $locale = array_pop($fileNameParts);
                    $domain = implode('.', $fileNameParts);
                    $translatorDefinition->addMethodCall('addResource', [$format, $file->getRealPath(), $locale, $domain]);

//                    $loaderIds['resource_files'][$locale][] = $file->getRealPath();
                }

//                $loaderIds['scanned_directories'][] = $transDirectory->getRealPath();
//                $loaderIds['cache_vary']['scanned_directories'][] = $transDirectory->getRealPath();
            }
        }

//        $translatorDefinition->replaceArgument(4, $loaderIds);
    }
}