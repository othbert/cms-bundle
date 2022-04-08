<?php

namespace Softspring\CmsBundle\Form\Admin\Block;

use Softspring\Component\CrudlController\Form\EntityListFilterForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlockListFilterForm extends EntityListFilterForm
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'translation_domain' => 'sfs_cms_blocks',
            'label_format' => 'admin_blocks.list.filter_form.%name%.label',
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
    }

    public static function orderValidFields(): array
    {
        return ['name', 'type'];
    }

    public static function orderDefaultField(): string
    {
        return 'name';
    }
}
