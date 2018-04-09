<?php

namespace AdminBundle\Form;

use RestBundle\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control col-md-6']
            ])
            ->add('description', TextType::class, [
                'attr' => ['class' => 'form-control col-md-6']
            ]);

        if (($options['data'])->getPriceSenior() !== null){
            $builder
                ->add('priceSenior', IntegerType::class, [
                    'attr' => ['class' => 'form-control col-md-6']
                ]);
        }
        
        if (($options['data'])->getPriceExecutive() !== null){
            $builder
                ->add('priceExecutive', IntegerType::class, [
                    'attr' => ['class' => 'form-control col-md-6']
                ]);
        }

        $builder
            ->add('submit', SubmitType::class, [
                'label' => 'Save Changes',
                'attr' => ['class' => 'btn btn-primary center-block']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return 'admin_bundle_servuce_type';
    }
}
