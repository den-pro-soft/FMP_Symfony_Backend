<?php

namespace RestBundle\Form;

use RestBundle\Entity\Document;
use RestBundle\Entity\Profile;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'attr' => ['class' => 'form-control col-md-6'],
                'choices_as_values' => true,
                'choices'  => [
                    'Resume' => 'Resume',
                    'Cover Letter' => 'Cover Letter'
                ]
            ])
            ->add('document', VichFileType::class)
            ->add('addedBy', TextType::class)
            ->add('profile', EntityType::class, [
                'class' => Profile::class
            ])
            ->add('name', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return 'admin_bundle_document_type';
    }
}
