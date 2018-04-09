<?php

namespace AdminBundle\Form;

use Doctrine\ORM\EntityRepository;
use RestBundle\Entity\Discount;
use RestBundle\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DiscountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('type', ChoiceType::class, [
                'attr' => ['class' => 'form-control'],
                'choices'  => [
                    'fixed' => 'Fixed',
                    'percentage' => 'Percentage'
                ]
            ])
            ->add('value', IntegerType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('services', EntityType::class, [
                'class' => Service::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('service');
                },
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true
            ])
            ->add('count', IntegerType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('dateFrom', DateType::class)
            ->add('dateUndo', DateType::class)
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Discount::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return 'admin_bundle_discount_type';
    }
}
