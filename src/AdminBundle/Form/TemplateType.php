<?php

namespace AdminBundle\Form;

use RestBundle\Entity\Template;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemplateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('type', ChoiceType::class, [
            'attr' => ['class' => 'form-control'],
            'choices'  => [
                'Resume' => 'Resume',
                'Cover Letter' => 'Cover Letter',
                'Other' => 'Other'
            ]
        ])
        ->add('template', FileType::class)
        ->add('submit', SubmitType::class, [
            'label' => 'Upload',
            'attr' => ['class' => 'btn btn-primary center-block']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Template::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return 'admin_bundle_template_type';
    }
}
