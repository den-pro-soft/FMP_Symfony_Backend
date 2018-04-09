<?php

namespace AdminBundle\Form;

use RestBundle\Entity\Questions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('work_authorization', ChoiceType::class, [
                'choices'  => [
                    'United States Citizen or Permanent Resident' => 'United States Citizen or Permanent Resident',
                    'US Work Visa Holder' => 'US Work Visa Holder',
                    'No Visa: Seeking Work Authorization' => 'No Visa: Seeking Work Authorization'
                ],
                'multiple' => false,
                'expanded' => true
            ])
            ->add('gender', ChoiceType::class, [
                'multiple' => false,
                'expanded' => true,
                'choices'  => [
                    'I don\'t wish to answer' => 'I don\'t wish to answer',
                    'Female' => 'Female',
                    'Male' => 'Male'
                ]])
            ->add('veteran_status', ChoiceType::class, [
                'multiple' => false,
                'expanded' => true,
                'choices'  => [
                    'I am not a protected veteran' => 'I am not a protected veteran',
                    'I identify as one or more of the classifications of a protected veteran' => 'I identify as one or more of the classifications of a protected veteran',
                    'I don\'t wish to answer' => 'I don\'t wish to answer'
                ]])
            ->add('disability_status', ChoiceType::class, [
                'multiple' => false,
                'expanded' => true,
                'choices'  => [
                    'Yes, I have a disability (or previously have a disability)' => 'Yes, I have a disability (or previously have a disability)',
                    'No, I don\'t have a disability' => 'No, I don\'t have a disability',
                    'I don\'t wish to answer' => 'I don\'t wish to answer'
                ]])
            ->add('race_ethnicity', TextType::class, [
                'attr' => ['class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Questions::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return 'admin_bundle_questions_type';
    }
}
