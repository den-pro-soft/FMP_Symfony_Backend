<?php

namespace AdminBundle\Form;

use JobApis\Jobs\Client\Schema\Entity\Text;
use RestBundle\Entity\Profile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Profile $profile */
        $profile = $options['data'];

        $builder
            ->add('phone_number', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('street_address', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('state', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('city', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('postal_code', IntegerType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('birthDate', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            // ->add('linkedin_url', TextType::class, [
            //     'attr' => ['class' => 'form-control']
            // ])
            ->add('new_gmail_email', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('new_gmail_password', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('linkedin_email', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('linkedin_password', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('education', CollectionType::class, [
                'entry_type' => EducationType::class,
                'disabled' => true
            ])
            ->add('work_experience', CollectionType::class, [
                'entry_type' => WorkExperienceType::class,
                'disabled' => true
            ])
            ->add('career_preferences', CareerPreferencesType::class, [
                'label' => false,
                'data' => $profile->getCareerPreferences()
            ])
            ->add('user_reference', CollectionType::class, [
                'entry_type' => ReferenceType::class,
                'disabled' => true
            ])
            ->add('questions', QuestionsType::class, [
                'label' => false,
                'disabled' => true
            ]);
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
        return 'admin_bundle_profile_type';
    }
}
