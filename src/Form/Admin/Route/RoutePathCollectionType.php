<?php

namespace Softspring\CmsBundle\Form\Admin\Route;

use Softspring\CmsBundle\Entity\RoutePath;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;

class RoutePathCollectionType extends AbstractType
{
    public function getParent(): ?string
    {
        return CollectionType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'route_path_collection';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'entry_type' => RoutePathType::class,
            'required' => false,
            'constraints' => new Count(['min' => 1]),
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
        ]);
    }
}
