<?php

namespace Softspring\CmsBundle\Form\Admin\Content;

use Doctrine\ORM\EntityManagerInterface;
use Softspring\CmsBundle\Form\Admin\LayoutContentType;
use Softspring\CmsBundle\Form\Type\LayoutType;
use Softspring\CmsBundle\Model\ContentVersionInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotEqualTo;

class ContentContentForm extends AbstractType implements ContentContentFormInterface
{
    protected EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContentVersionInterface::class,
            'label_format' => 'admin_content.form.%name%.label',
            'validation_groups' => ['Default', 'create'],
            'translation_domain' => 'sfs_cms_admin',
            'layout' => null,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('layout', LayoutType::class);
        $builder->add('data', LayoutContentType::class, ['layout' => $options['layout']]);
        $builder->add('_ok', HiddenType::class, [
            'mapped' => false,
            'constraints' => new NotEqualTo(1),
        ]);
    }
}
