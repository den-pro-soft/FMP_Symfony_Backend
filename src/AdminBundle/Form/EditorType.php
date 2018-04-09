<?php

namespace AdminBundle\Form;

use Doctrine\ORM\EntityRepository;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use RestBundle\Entity\Blog;
use RestBundle\Entity\Category; 
use RestBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

/**
 * Class EditorType
 * @package AdminBundle\Form
 */
class EditorType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'attr' => array('class' => 'form-control')
            ))
            ->add('seoTitle', TextType::class, array(
                'attr' => array('class' => 'form-control')
            ))
            ->add('description', TextType::class, array(
                'attr' => array('class' => 'form-control')
            ))
            ->add('category', EntityType::class, array(
                'class' => Category::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('category');
                },
                'choice_label'  => 'title',
                'expanded'      => true,
                'multiple'      => true
            ))
            ->add('content', CKEditorType::class, array(
                'config' => array(
                    'uiColor'   => '#ffffff',
                    'height'    => 400
                )
            ))
            ->add('author', AuthorType::class, array(
                'attr' => array('class' => 'form-control'),
            ))
            ->add('image', VichImageType::class, array(
                'label'         => 'Title image',
                'required'      => false,
                'allow_delete'  => false
            ))
            ->add('imageAlt', TextType::class, array(
                'attr'      => array('class' => 'form-control'),
                'label'     => 'Image alt-text',
                'required'  => false
            ))
            ->add('url', TextType::class, array(
                'attr'      => array('class' => 'form-control'),
                'label'     => 'Slug',
                'required'  => false
            ))
            ->add('status', ChoiceType::class, array(
                'attr'    => array('class' => 'form-control'),
                'choices' => array(
                    'Draft'     => 'Draft',
                    'Publish'   => 'Publish'
                )
            ));

        if (!$options["disable_submit"]) {
            $builder
                ->add('submit', SubmitType::class, array(
                    'label' => 'Save Changes',
                    'attr'  => array('class' => 'btn btn-primary center-block')));
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => Blog::class,
            'csrf_protection'   => false,
            'disable_submit'    => false
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'admin_bundle_editor_type';
    }
}
