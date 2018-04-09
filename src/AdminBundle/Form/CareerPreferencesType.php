<?php

namespace AdminBundle\Form;

use RestBundle\Entity\CareerPreferences;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CareerPreferencesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var CareerPreferences $object */
        $object = $options['data'];

        if (!$object instanceof CareerPreferences) {
            $object = new CareerPreferences();
        }

        $builder
            ->add('industry', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('job_titles', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'data' => is_array($object->getJobTitles())
                    ? implode(', ', $object->getJobTitles())
                    : $object->getJobTitles()
            ])
            ->add('job_types', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'data' => is_array($object->getJobTypes())
                    ? implode(', ', $object->getJobTypes())
                    : $object->getJobTypes()
            ]);

        if ($object->getRelocationValue()) {
            $builder
                ->add('relocation_value', CheckboxType::class)
                ->add('relocation_type', ChoiceType::class, [
                    'multiple' => false,
                    'expanded' => true,
                    'choices' => [
                        'anywhere' => 'Anywhere',
                        'specific_location' => 'Specific Location'
                    ]
                ]);

            if ($object->getRelocationType() !== 'anywhere') {
                $builder
                    ->add('relocation_location', TextType::class, [
                        'attr' => ['class' => 'form-control'],
                        'data' => is_array($object->getRelocationLocation())
                            ? implode(', ', $object->getRelocationLocation())
                            : $object->getRelocationLocation()
                    ]);
            }
        }

        $builder
        ->add('experience', TextType::class, [
            'attr' => ['class' => 'form-control']
        ])
        ->add('education', TextType::class, [
            'attr' => ['class' => 'form-control']
        ])
        ->add('desire_salary_value', TextType::class, [
            'attr' => ['class' => 'form-control'],
            'label' => 'Desired salary value'
        ])
        ->add('desire_salary_type', TextType::class, [
            'attr' => ['class' => 'form-control'],
            'label' => 'Desired salary type'
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
        return 'admin_bundle_job_type';
    }
}
