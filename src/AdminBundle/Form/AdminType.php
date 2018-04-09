<?php

namespace AdminBundle\Form;

use RestBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $admin = isset($options['data']) && $options['data'] instanceof User ? $options['data'] : null;

        $builder
            ->add('full_name', TextType::class, array(
                'attr' => array('class' => 'form-control')
            ))
            ->add('email', EmailType::class, array(
                'attr' => array('class' => 'form-control')
            ))
            ->add('title', ChoiceType::class, array(
                'attr' => array('class' => 'form-control'),
                'choices'  => array(
                    'Account Manager'   => 'Account Manager',
                    'Account Executive' => 'Account Executive'
                )
            ))
            ->add('role', ChoiceType::class, array(
                'attr' => array('class' => 'form-control'),
                'choices'  => array(
                    'ROLE_ADMIN_MANAGER'    => 'Admin Manager',
                    'ROLE_ADMIN'            => 'Account Executive',
                    'ROLE_SDR'              => 'SDR',
                    'ROLE_MANAGER_BLOG'     => 'Blog Manager'
                )
            ));

        if (!$admin) {
            $builder
                ->add('password', RepeatedType::class, array(
                    'required' => false,
                    'type' => PasswordType::class,
                    'options' => array('attr' => array('class' => 'form-control')),
                    'first_options' => array('label' => 'Password'),
                    'second_options' => array('label' => 'Repeat Password'),
                ));
        }

        $builder
            ->add('submit', SubmitType::class, array(
                'label' => 'Save Changes',
                'attr' => array('class' => 'btn btn-primary center-block')
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
            'csrf_protection' => false
        ));
    }

    public function getBlockPrefix()
    {
        return 'admin_bundle_admin_type';
    }
}
