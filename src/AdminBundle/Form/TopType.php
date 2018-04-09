<?php

namespace AdminBundle\Form;

use Doctrine\ORM\EntityRepository;
use RestBundle\Entity\Blog;
use RestBundle\Entity\Top;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TopType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $category = $options['data']->getCategory() === 'all' ? '' : $options['data']->getCategory();

        $fields = [
            'first',
            'second',
            'third',
            'fourth',
            'fifth',
            'sixth'
        ];

        $builder
            ->add('category', HiddenType::class);

        foreach ($fields as $field) {
            $builder
                ->add($field, EntityType::class, [
                    'attr' => ['class' => 'form-control col-sm-6'],
                    'class' => Blog::class,
                    'empty_data' => null,
                    'empty_value' => '',
                    'required' => false,
                    'query_builder' => function (EntityRepository $er) use ($category) {
                        $qb = $er->createQueryBuilder('b');

                        if ($category === 'job-search') {
                            $qb->andWhere('b.title LIKE :category OR b.title LIKE :sub_category OR b.description LIKE :category OR b.description LIKE :sub_category')
                                ->setParameters([
                                    'category' => '%job%',
                                    'sub_category' => '%search%'
                                ]);
                        } elseif ($category === 'resume') {
                            $qb->andWhere('b.title LIKE :category OR b.title LIKE :sub_category OR b.description LIKE :category OR b.description LIKE :sub_category')
                                ->setParameters([
                                    'category' => '%resume%',
                                    'sub_category' => '%cover letter%'
                                ]);
                        } else {
                            if ($category === 'interviewing') {
                                $category = 'interview';
                            }

                            $qb->andWhere('b.title LIKE :category OR b.description LIKE :category')
                                ->setParameters([
                                    'category' => '%' . $category . '%'
                                ]);
                        }

                        return $qb->orderBy('b.post_date', 'DESC');
                    },
                    'choice_label' => 'title'
                ]);
        }

        $builder
            ->add('submit', SubmitType::class, [
                'label' => 'Save Changes',
                'attr' => ['class' => 'btn btn-primary center-block']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Top::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return 'top_all_blogs';
    }
}
