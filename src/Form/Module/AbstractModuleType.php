<?php

namespace Softspring\CmsBundle\Form\Module;

use Softspring\Component\PolymorphicFormType\Form\Type\Node\AbstractNodeType;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractModuleType extends AbstractNodeType
{
    protected array $enabledLocales;

    public function __construct(array $enabledLocales)
    {
        $this->enabledLocales = $enabledLocales;
    }

    public function configureChildOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'module_id' => null,
            'translation_domain' => 'sfs_cms_modules',
            'content_type' => null,
            'row_class' => '',
            'locale_filter' => true,
        ]);

        $resolver->setRequired('module_id');
        $resolver->setAllowedTypes('module_id', ['string']);

        $resolver->setRequired('content_type');
        $resolver->setAllowedTypes('content_type', ['string']);

        $resolver->setDefault('form_template', null);
        $resolver->setAllowedTypes('form_template', ['null', 'string']);

        $resolver->setDefault('edit_template', null);
        $resolver->setAllowedTypes('edit_template', ['null', 'string']);
    }

    public function buildChildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['form_template'] = $options['form_template'];
        $view->vars['edit_template'] = $options['edit_template'];
        $view->vars['row_class'] = $options['row_class'];
    }

    protected function buildChildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['locale_filter'] && sizeof($this->enabledLocales) > 1) {
            $builder->add('locale_filter', ChoiceType::class, [
                'multiple' => true,
                'expanded' => true,
                'block_prefix' => 'locale_filter',
                'choice_translation_domain' => false,
                'choices' => array_combine($this->enabledLocales, $this->enabledLocales),
            ]);

            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (PreSetDataEvent $event) {
                if (null === $event->getData()) {
                    $event->setData(['locale_filter' => $this->enabledLocales]);
                }
            });
        }
    }
}
