<?php

namespace AdminBundle\Form;

use AdminBundle\Entity\Admin;
use Doctrine\ORM\EntityRepository;
use RestBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var User $user */
        $user = $options['data'];

        $builder
            ->add('full_name', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'disabled' => true
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control'],
                'disabled' => true
            ])
            ->add('profile', ProfileType::class, [
                'label' => false,
                'disabled' => true,
                'data' => $user->getProfile()
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return 'profile';
    }
}
