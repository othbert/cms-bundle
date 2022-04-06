<?php

namespace Softspring\CmsBundle\Config\Model;

use Softspring\CmsBundle\Form\Admin\Content\ContentContentForm;
use Softspring\CmsBundle\Form\Admin\Content\ContentCreateForm;
use Softspring\CmsBundle\Form\Admin\Content\ContentUpdateForm;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Content implements ConfigurationInterface
{
    protected string $contentName;

    public function __construct(string $contentName)
    {
        $this->contentName = $contentName;
    }

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('content');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->integerNode('revision')->isRequired()->end()

                ->scalarNode('render_template')->defaultValue("@content/{$this->contentName}/render.html.twig")->end()
                ->scalarNode('edit_template')->defaultValue("@content/{$this->contentName}/edit.html.twig")->end()
                ->scalarNode('entity_class')->isRequired()->end()

                ->arrayNode('containers')
                    ->defaultValue([])
                    ->useAttributeAsKey('key')
                    ->arrayPrototype()
                    ->children()
                        ->booleanNode('required')->defaultFalse()->end()
                        ->arrayNode('allowed_modules')
                            ->scalarPrototype()->end()
                        ->end()
                    ->end()
                    ->end()
                ->end()

                ->arrayNode('extra_fields')
                    ->defaultValue([])
                    ->useAttributeAsKey('key')
                    ->arrayPrototype()
                    ->children()
                        ->scalarNode('type')->isRequired()->end()
                        ->arrayNode('type_options')
                            ->useAttributeAsKey('key')
                            ->prototype('variable')->end()
                        ->end()
                    ->end()
                    ->end()
                ->end()

                ->scalarNode('list_is_granted')->defaultValue('')->end()
                ->scalarNode('list_view')->defaultValue('@SfsCms/admin/content/list.html.twig')->end()
                ->scalarNode('create_is_granted')->defaultValue('')->end()
                ->scalarNode('create_view')->defaultValue('@SfsCms/admin/content/create.html.twig')->end()
                ->scalarNode('create_type')->defaultValue(ContentCreateForm::class)->end()
                ->scalarNode('create_success_redirect_to')->defaultValue('')->end()
                ->scalarNode('read_view')->defaultValue('@SfsCms/admin/content/read.html.twig')->end()
                ->scalarNode('preview_view')->defaultValue('@SfsCms/admin/content/preview.html.twig')->end()
                ->scalarNode('update_is_granted')->defaultValue('')->end()
                ->scalarNode('update_view')->defaultValue('@SfsCms/admin/content/update.html.twig')->end()
                ->scalarNode('update_type')->defaultValue(ContentUpdateForm::class)->end()
                ->scalarNode('update_success_redirect_to')->defaultValue('')->end()
                ->scalarNode('content_is_granted')->defaultValue('')->end()
                ->scalarNode('content_view')->defaultValue('@SfsCms/admin/content/content.html.twig')->end()
                ->scalarNode('content_type')->defaultValue(ContentContentForm::class)->end()
                ->scalarNode('content_success_redirect_to')->defaultValue('')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}