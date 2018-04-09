<?php

namespace MigrationBundle\Command;

use Doctrine\ORM\EntityManager;
use RestBundle\Entity\Blog;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('rest:update')
            ->setDescription('Hello PhpStorm');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        /** @var Blog[] $articles */
        $articles = $em->getRepository('RestBundle:Blog')->findAll();

        foreach ($articles as $article) {
            preg_match_all('/<img[^>]+>/i', $article->getContent(), $results);

            if (isset($results[0]) && !empty($results[0])) {
                foreach ($results[0] as $result) {
                    preg_match('/([-a-z0-9_\/:.]+\.(jpg|jpeg|png))/i', $result, $image);
                    if (isset($image[0])) {
                        $images[] = $image[0];
                    }

//                    $temp = explode('/', $image[0]);
//                    $filename = end($temp);
//                    copy($image[0], $this->getContainer()->getParameter('upload_dir') . '/../' . $filename);
                }
            }

            if (!empty($images)) {
                foreach ($images as $image) {
                    $temp = explode('/', $image);
                    unset($temp[count($temp) - 1]);

                    $url = implode('/', $temp);

                    $content = str_replace($url, '/uploads', $article->getContent());
                    $article->setContent($content);
                }

                $em->persist($article);
            }
        }

        $em->flush();
    }
}
