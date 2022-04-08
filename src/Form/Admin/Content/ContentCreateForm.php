<?php

namespace Softspring\CmsBundle\Form\Admin\Content;

use Softspring\CmsBundle\Form\Admin\Route\RouteCollectionType;
use Softspring\CmsBundle\Form\Type\DynamicFormType;
use Softspring\CmsBundle\Model\ContentInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContentCreateForm extends AbstractType implements ContentCreateFormInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContentInterface::class,
            'validation_groups' => ['Default', 'create'],
            'translation_domain' => 'sfs_cms_contents',
            'content' => null,
        ]);

        $resolver->setNormalizer('label_format', function (Options $options, $value) {
            return "admin_{$options['content']['_id']}.form.%name%.label";
        });
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class);

        if (!empty($options['content']['extra_fields'])) {
            $builder->add('extraData', DynamicFormType::class, [
                'form_fields' => $options['content']['extra_fields'],
            ]);
        }

        $builder->add('routes', RouteCollectionType::class, [
            'allow_add' => false,
            'allow_delete' => false,
            'entry_options' => [
                'content_relative' => true,
                'label_format' => "admin_{$options['content']['_id']}.form.routes.%name%.label",
            ],
        ]);
    }
}
