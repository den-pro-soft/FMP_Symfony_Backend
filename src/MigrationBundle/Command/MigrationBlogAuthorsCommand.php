<?php

namespace MigrationBundle\Command;

use RestBundle\Entity\Blog;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrationBlogAuthorsCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('rest:migrate:blog_authors')
            ->setDescription('Create new authors for each blog')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $blogs = $em->getRepository(Blog::class)->findAll();

        foreach ($blogs as $blog) {
            $newAuthor = clone $blog->getAuthor();
            $blog->setAuthor($newAuthor);

            $em->persist($newAuthor);
        }

        $em->flush();
    }
}
