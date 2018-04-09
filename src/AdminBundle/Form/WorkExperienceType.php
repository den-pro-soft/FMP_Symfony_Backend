<?php

namespace AdminBundle\Form;

use RestBundle\Entity\WorkExperience;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;

class WorkExperienceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('employer', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('job_title', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('salary_earned', IntegerType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('reason_for_leaving', TextType::class, [
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
            'data_class' => WorkExperience::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'admin_bundle_work_experience_type';
    }
}
