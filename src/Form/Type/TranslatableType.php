<?php

namespace Softspring\CmsBundle\Form\Type;

use Softspring\CmsBundle\Form\DynamicFormTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslatableType extends AbstractType
{
    use DynamicFormTrait;

    protected string $defaultLocale;
    protected array $enabledLocales;

    public function __construct(string $defaultLocale, array $enabledLocales)
    {
        $this->defaultLocale = $defaultLocale;
        $this->enabledLocales = $enabledLocales;
    }

    public function getBlockPrefix(): string
    {
        return 'translatable';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required' => false,
            'languages' => $this->enabledLocales,
            'default_language' => $this->defaultLocale,
            'children_attr' => [],
            'type' => null,
            'type_options' => [],
        ]);

        $resolver->setRequired('languages');
        $resolver->setAllowedTypes('languages', 'array');
        $resolver->setRequired('default_language');
        $resolver->setAllowedTypes('default_language', 'string');
        $resolver->setRequired('type');
        $resolver->setAllowedTypes('type', 'string');
        $resolver->setAllowedTypes('type_options', 'array');
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['languages'] as $lang) {
            $builder->add($lang, $this->getFieldType($options['type']), [
                'required' => $lang == $options['default_language'],
                'label' => $lang,
                'translation_domain' => false,
                'block_prefix' => 'translatable_element',
            ] + $options['type_options']
            + [
                'attr' => $options['children_attr'] + ($options['type_options']['attr'] ?? []),
            ]);
        }
    }
}
