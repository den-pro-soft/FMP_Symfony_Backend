<?php

namespace AdminBundle\Form;

use Doctrine\ORM\EntityRepository;
use RestBundle\Entity\Service;
use RestBundle\Entity\Testimonial;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestimonialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('service', EntityType::class, [
                'attr' => ['class' => 'form-control col-md-6'],
                'class' => Service::class,
                'choice_label'  => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t');
                },
                'placeholder'   => 'Choose service',
            ])
            ->add('onHomepage', CheckboxType::class, [
                'label'    => 'Show on homepage',
                'required' => false
            ])
            ->add('avatar', FileType::class, [
                'required' => false
            ])
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control col-md-6']
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control col-md-6']
            ])
            ->add('title', TextType::class, [
                'attr' => ['class' => 'form-control col-md-6']
            ])
            ->add('age', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control col-md-6',
                    'min' => 18,
                    'max' => 100
                ]
            ])
            ->add('detail', TextareaType::class, [
                'attr' => ['class' => 'form-control col-md-6']
            ])
            ->add('industry', TextType::class, [
                'attr' => ['class' => 'form-control col-md-6']
            ])
            ->add('rating', ChoiceType::class, [
                'attr' => ['class' => 'form-control col-md-6'],
                'choices_as_values' => true,
                'choices'  => [
                    '5' => 5,
                    '4' => 4,
                    '3' => 3,
                    '2' => 2,
                    '1' => 1,
                ]
            ])
            ->add('date', DateType::class, [
                'attr' => ['class' => 'form-control col-md-6'],
                'widget' => 'choice',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Save Changes',
                'attr' => ['class' => 'btn btn-primary center-block']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Testimonial::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return 'admin_bundle_testimonial_type';
    }
}
