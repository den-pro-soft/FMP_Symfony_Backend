<?php

namespace RestBundle\Form;

use RestBundle\Entity\CareerPreferences;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CareerPreferencesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('industry', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('job_titles', CollectionType::class, [
                'attr' => ['class' => 'form-control'],
                'allow_add' => true,
                'allow_delete' => true
            ])
            ->add('job_types', CollectionType::class, [
                'attr' => ['class' => 'form-control'],
                'allow_add' => true,
                'allow_delete' => true
            ])
            ->add('relocation_value', CheckboxType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('relocation_type', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('relocation_location', CollectionType::class, [
                'attr' => ['class' => 'form-control'],
                'allow_add' => true,
                'allow_delete' => true
            ])
            ->add('experience', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('education', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('desire_salary_value', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('desire_salary_type', TextType::class, [
                'attr' => ['class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CareerPreferences::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return 'rest_bundle_job_type';
    }
}
