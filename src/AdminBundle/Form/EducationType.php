<?php

namespace AdminBundle\Form;

use RestBundle\Entity\Education;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EducationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('institution', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('discipline', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('level', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('start_date', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('end_date', TextType::class, [
                'attr' => ['class' => 'form-control']
            ]);

        $builder->get('end_date')
            ->addModelTransformer(new CallbackTransformer(
                function ($date) {
                    if (!$date) {
                        return 'Present';
                    }
                    return (new \DateTime($date))->format('m/d/Y');
                },
                function ($date) {
                    return (new \DateTime($date))->format('m/d/Y');
                }
            ));

        $builder->get('start_date')
            ->addModelTransformer(new CallbackTransformer(
                function ($date) {
                    return (new \DateTime($date))->format('m/d/Y');
                },
                function ($date) {
                    return (new \DateTime($date))->format('m/d/Y');
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Education::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ]);
    }

    public function getBlockPrefix()
    {
        return 'admin_bundle_education';
    }
}
