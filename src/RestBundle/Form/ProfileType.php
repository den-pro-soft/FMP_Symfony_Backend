<?php

namespace RestBundle\Form;

use RestBundle\Entity\Profile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phone_number', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false
            ])
            ->add('street_address', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false
            ])
            ->add('city', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false
            ])
            ->add('state', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false
            ])
            ->add('postal_code', IntegerType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false
            ])
            ->add('birth_date', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false
            ])
            ->add('linkedin_url', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false
            ])
            ->add('new_gmail_email', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false
            ])
            ->add('new_gmail_password', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false
            ])
            ->add('linkedin_email', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false
            ])
            ->add('linkedin_password', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return 'rest_bundle_profile_type';
    }
}
