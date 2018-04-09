<?php

namespace MigrationBundle\Command;

use Doctrine\Common\Persistence\ObjectManager;
use MigrationBundle\Entity\WpPostmeta;
use MigrationBundle\Entity\WpPosts;
use RestBundle\Entity\Author;
use RestBundle\Entity\Blog;
use RestBundle\Entity\Testimonial;
use RestBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('migration:migrate')
            ->setDescription('Hello PhpStorm');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var ObjectManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $this->addSuperAdmin($em);
        $this->addTestimonials($em);
        $this->addArticles($em);
        $this->updateCategories($em);
    }

    /**
     * @param ObjectManager $em
     */
    private function addSuperAdmin(ObjectManager $em)
    {
        $admin = $em->getRepository('RestBundle:User')->findOneBy(['username' => 'FMP']);

        if (!$admin instanceof User) {
            $admin = new User();
            $admin->setUsername('FMP');
            $admin->setFullName('The FMP Contributor');
            $admin->setEmail('admin@findmyprofession.com');
            $admin->setIsActive(true);
        }

        $admin->setPassword('X)uxvgx)7lolGM$M0eLoGNoC');
        $em->persist($admin);
        $em->flush();
    }

    /**
     * @param ObjectManager $em
     */
    private function addTestimonials(ObjectManager $em)
    {
//        $services = $em->getRepository('RestBundle:Service')->findAll();
//
//        foreach ($services as $service) {
//            $wp_testimonials = $em->getRepository('MigrationBundle:CpTestimonials')->findBy([
//                'deleted' => 0,
//                'status' => 1,
//                'helpWith' => $service->getName()
//            ]);
//
//            foreach ($wp_testimonials as $wp_testimonial) {
//                $testimonial = new Testimonial();
//                $testimonial->setName($wp_testimonial->getName());
//                $testimonial->setEmail($wp_testimonial->getEmail());
//                $testimonial->setTitle($wp_testimonial->getTitle());
//                $testimonial->setDate($wp_testimonial->getDate());
//                $testimonial->setAge($wp_testimonial->getAge());
//                $testimonial->setDetail($wp_testimonial->getDetail());
//                $testimonial->setIndustry($wp_testimonial->getIndustry());
//                $testimonial->setRating($wp_testimonial->getRating());
//                $testimonial->setService($service);
//
//                $em->persist($testimonial);
//            }
//        }
//
//        $em->flush();
    }

    /**
     * @param ObjectManager $em
     */
    private function addArticles(ObjectManager $em)
    {
        /** @var WpPosts[] $posts */
        $posts = $em->getRepository('MigrationBundle:WpPosts')->findBy(['postStatus' => 'publish', 'postType' => 'post']);

        foreach ($posts as $post) {
            $blog = $em->getRepository('RestBundle:Blog')->findOneBy(['url' => $post->getPostName()]);

            if (!$blog instanceof Blog) {
                $blog = new Blog();
            }

            /** @var WpPosts[] $avatars */
            $avatars = $em->getRepository('MigrationBundle:WpPosts')->getAuthorAvatar($post->getId());

            if (count($avatars) > 1 && $avatars[0] instanceof WpPosts) {
                $avatar = end($avatars);
                $temp = explode('/', $avatar->getGuid());
                $filename = end($temp);

                /** @var WpPostmeta $author_name */
                $author_name = $em->getRepository('MigrationBundle:WpPostmeta')->findOneBy([
                    'postId' => $avatar->getPostParent(),
                    'metaKey' => 'author_name'
                ]);

                /** @var WpPostmeta $author_content */
                $author_content = $em->getRepository('MigrationBundle:WpPostmeta')->findOneBy([
                    'postId' => $avatar->getPostParent(),
                    'metaKey' => 'author_content'
                ]);

                /** @var WpPostmeta $author_link */
                $author_link = $em->getRepository('MigrationBundle:WpPostmeta')->findOneBy([
                    'postId' => $avatar->getPostParent(),
                    'metaKey' => 'author_link'
                ]);

                $author = $em->getRepository('RestBundle:Author')->findOneBy(['name' => $author_name->getMetaValue()]);

                if (!$author instanceof Author) {
                    $author = new Author();
                    $author->setName($author_name->getMetaValue());
                    $author->setDescription($author_content->getMetaValue());
                    $author->setLink($author_link->getMetaValue());

                    copy($avatar->getGuid(), $this->getContainer()->getParameter('avatars') . '/' . $filename);
                    $author->setAvatar($filename);
                    $author->setAvatarName($filename);

                    $em->persist($author);
                    $em->flush();
                }

                $blog->setAuthor($author);
            }

            /** @var WpPosts $title_image */
            $title_image = $em->getRepository('MigrationBundle:WpPosts')->getPostTitle($post->getId());

            if ($title_image instanceof WpPosts) {
                $temp = explode('/', $title_image->getGuid());
                $filename = end($temp);

                copy($title_image->getGuid(), $this->getContainer()->getParameter('uploads') . '/' .$filename );
                $blog->setImage($filename);
            }

            $blog->setTitle($post->getPostTitle());
            $blog->setUrl($post->getPostName());
            $content = preg_replace('/\[(.*?)\]/is', '', $post->getPostContent());

            $blog->setContent($content);
            $blog->setPostDate($post->getPostDate());

            $post_description = $em->getRepository('MigrationBundle:WpPostmeta')->findOneBy(['postId' => $post->getId(), 'metaKey' => '_yoast_wpseo_metadesc']);
            $blog->setDescription('');

            if ($post_description) {
                $post_title = $em->getRepository('MigrationBundle:WpPostmeta')->findOneBy(['postId' => $post->getId(), 'metaKey' => '_yoast_wpseo_title']);
                $blog->setSeoTitle($post_title->getMetaValue());
                $blog->setDescription($post_description->getMetaValue());
            }

            $em->persist($blog);
        }

        $em->flush();
    }

    /**
     * @param ObjectManager $em
     */
    private function updateCategories(ObjectManager $em)
    {
        $categories = $em->getRepository('RestBundle:Category')->findAll();

        foreach ($categories as $category) {
            $term_category_id = $em->getRepository('MigrationBundle:WpTerms')->findOneBy(['slug' => $category->getCategory()]);
            $articles_ids = $em->getRepository('MigrationBundle:WpTermRelationships')->findBy(['termTaxonomyId' => $term_category_id]);

            foreach ($articles_ids as $articles_id) {
                $article = $em->getRepository('MigrationBundle:WpPosts')->findOneBy(['id' => $articles_id->getObjectId()]);
                if ($article) {
                    $blog = $em->getRepository('RestBundle:Blog')->findOneBy(['url' => $article->getPostName()]);
                    if ($blog instanceof Blog) {
                        $blog->addCategory($category);
                        $em->persist($blog);
                    }
                }
            }
        }

        $em->flush();
    }
}
