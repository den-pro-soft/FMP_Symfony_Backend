<?php

namespace AdminBundle\Form;

use RestBundle\Entity\Resource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('resource', FileType::class, [
                'label' => 'Add document'
            ])
            ->add('preview', FileType::class, [
                'label' => 'Add preview(723 * 1024) '
            ])
            ->add('type', ChoiceType::class, array(
                'choices'  => array(
                    'cover_letters' => 'Cover Letters',
                    'follow_up_emails' => 'Follow-Up Emails',
                    'thank_you_emails' => 'Thank You Emails',
                ),))
            ->add('submit', SubmitType::class, [
                'label' => 'Save Changes',
                'attr' => ['class' => 'btn btn-primary']]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Resource::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return 'admin_bundle_resource_type';
    }
}
