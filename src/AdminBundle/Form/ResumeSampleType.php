<?php
/**
 * Created by LiuWebDev
 */

namespace AdminBundle\Form;

use Doctrine\ORM\EntityRepository;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use RestBundle\Entity\ResumeSample;


class ResumeSampleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'attr' => array('class' => 'form-control')
            ))
            ->add('image', VichImageType::class, array(
                'label' => '',
                'allow_delete' => false,
                'required' => true
            ))
            ->add('pdf', FileType::class, array(
                'label' => 'Document(PDF File)',
                'required' => true
            ))
            ->add('category', ChoiceType::class, array(
                'attr' => array('class' => 'form-control'),
                'choices' => array(
                    'senior' => 'Senior & Executive Resume Samples',
                    'entry'  => 'Entry & Mid Resume Samples'
                )
            ))
            ->add('status', ChoiceType::class, array(
                'attr' => array('class' => 'form-control'),
                'choices' => array(
                    'Draft' => 'Draft',
                    'Publish' => 'Publish'
                )
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Save',
                'attr' => array(
                    'class' => 'btn btn-primary center-block'
                )
            ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ResumeSample::class,
            'csrf_protection' => false,
            'disable_submit' => false
        ));
    }

    public function getBlockPrefix()
    {
        return 'admin_bundle_resume_sample_type';
    }


}


