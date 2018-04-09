<?php

namespace AdminBundle\Form;

use RestBundle\Entity\UserReference;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReferenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('job_title', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('company', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('phone_number', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('relationship', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('reference_text', TextType::class, [
                'attr' => ['class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserReference::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return 'admin_bundle_reference_type';
    }
}
