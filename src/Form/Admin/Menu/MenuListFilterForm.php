<?php

namespace Softspring\CmsBundle\Form\Admin\Menu;

use Softspring\Component\DoctrinePaginator\Form\PaginatorFiltersForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuListFilterForm extends PaginatorFiltersForm
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'translation_domain' => 'sfs_cms_admin',
            'label_format' => 'admin_menus.list.filter_form.%name%.label',
            'rpp_valid_values' => [20],
            'rpp_default_value' => 20,
            'order_valid_fields' => ['name', 'type'],
            'order_default_value' => 'name',
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
    }
}
