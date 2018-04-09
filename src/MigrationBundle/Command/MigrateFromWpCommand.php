<?php

namespace MigrationBundle\Command;

use Doctrine\ORM\EntityManager;
use RestBundle\Entity\Service;
use RestBundle\Entity\Testimonial;
use RestBundle\Entity\User;
use MigrationBundle\Entity\WpPosts;
use MigrationBundle\Entity\WpUsers;
use RestBundle\Entity\Blog;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MigrateFromWpCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('rest:migrate')
            ->setDescription('Hello PhpStorm')
            ->addArgument('type', InputArgument::OPTIONAL, 'What is the type?')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        switch ($input->getArgument('type')) {
            case 'testimonials':
                $this->addTestimonials($em);
                break;

            case 'users':
                $this->addUsers($em);
                break;

            case 'role':
                $this->updateUsers($em);
                break;

            case 'blogs':
                $this->addBlog($em);
                break;

            case 'seo':
                $this->addSeo($em);
                break;

            case 'new_seo':
                $this->addNewSeo($em);
                break;

            case 'page':
                $this->addPages($em);
                break;

            case 'update':
                $this->updateImageLinks($em);
                break;

            case 'category':
                $this->updateCategories($em);
                break;

            default:
                $this->all($em);
        }
    }

    private function all(EntityManager $em)
    {
        $this->addUsers($em);
        $this->addBlog($em);
        $this->addTestimonials($em);
    }

    private function addUsers(EntityManager $em)
    {
        /** @var WpUsers[] $wp_users */
        $wp_users = $em->getRepository('MigrationBundle:WpPosts')->findAll();

        foreach ($wp_users as $wp_user) {
            $user = new User();
            $user->setUsername($wp_user->getUserLogin());
            $user->setFullName($wp_user->getDisplayName());
            $user->setDateCreated($wp_user->getUserRegistered());
            $user->setEmail($wp_user->getUserEmail());
            $user->setIsActive(1);

            $em->persist($user);
        }

        $em->flush();
    }

    private function addBlog(EntityManager $em)
    {
        /** @var WpPosts[] $posts */
        $posts = $em->getRepository('MigrationBundle:WpPosts')->findBy(['postStatus' => 'publish', 'postType' => 'post']);

        foreach ($posts as $post) {
            $blog = new Blog();
            $blog_user = $em->getRepository('RestBundle:User')->find($post->getPostAuthor());
            $attachment = $em->getRepository('MigrationBundle:WpPosts')->findOneBy(['postParent' => $post->getId(), 'postType' => 'attachment']);
            $blog->setImage('');

            if ($attachment) {
                $temp = explode('/', $attachment->getGuid());
                $filename = end($temp);
                copy($attachment->getGuid(), $this->getContainer()->getParameter('upload_dir') . '/../' .$filename );
                $blog->setImage($filename);
            }

            $blog->setAuthor($blog_user);
            $blog->setTitle($post->getPostTitle());
            $content = preg_replace('/\[(.*?)\]/is', '', $post->getPostContent());

            $blog->setContent($content);
            $blog->setPostDate($post->getPostDate());
            $blog->setDescription('');

            $blog->setUrl();

            $em->persist($blog);
        }

        $em->flush();
    }

    private function addTestimonials(EntityManager $em)
    {
        $temp_services = [
            'Career Finder' => [
                'price_senior' => 1199,
                'price_executive' => 1699
            ],
            'Resume Makeover' => [
                'price_senior' => 249,
                'price_executive' => 399
            ],
            'Cover Letter Service' => [
                'price_senior' => 129,
                'price_executive' => 169
            ],
            'LinkedIn Makeover'  => [
                'price_senior' => 229,
                'price_executive' => 329
            ],
            'Interview Training' => [
                'price_senior' => 199,
                'price_executive' => 259
            ]
        ];

        foreach ($temp_services as $service_name => $temp_service) {
            $service = new Service();
            $service->setName($service_name);
            $service->setPriceExecutive($temp_service['price_executive']);
            $service->setPriceSenior($temp_service['price_senior']);

            $em->persist($service);

            $wp_testimonials = $em->getRepository('MigrationBundle:CpTestimonials')->findBy(['deleted' => 0, 'status' => 1, 'helpWith' => $service_name]);

            foreach ($wp_testimonials as $wp_testimonial) {
                $testimonial = new Testimonial();
                $testimonial->setName($wp_testimonial->getName());
                $testimonial->setEmail($wp_testimonial->getEmail());
                $testimonial->setTitle($wp_testimonial->getTitle());
                $testimonial->setDate($wp_testimonial->getDate());
                $testimonial->setAge($wp_testimonial->getAge());
                $testimonial->setDetail($wp_testimonial->getDetail());
                $testimonial->setIndustry($wp_testimonial->getIndustry());
                $testimonial->setRating($wp_testimonial->getRating());
                $testimonial->setService($service);

                $em->persist($testimonial);
            }
        }

        $em->flush();
    }

    private function addPages(EntityManager $em)
    {
        $pages = [
            'career-advice',
            'linkedin',
            'resume',
            'interviewing',
            'job-search',
        ];

        foreach ($pages as $page) {
            /** @var WpPosts[] $posts */
            $post = $em->getRepository('MigrationBundle:WpPosts')->findOneBy(['postName' => $page]);

            $blog = new Blog();
            $blog_user = $em->getRepository('RestBundle:User')->find($post->getPostAuthor());
            $attachment = $em->getRepository('MigrationBundle:WpPosts')->findOneBy(['postParent' => $post->getId(), 'postType' => 'attachment']);
            $blog->setImage('');

            if ($attachment) {
                $temp = explode('/', $attachment->getGuid());
                $filename = end($temp);
                copy($attachment->getGuid(), $this->getContainer()->getParameter('upload_dir') . '/' . $filename);
                $blog->setImage($filename);
            }

            $blog->setAuthor($blog_user);
            $blog->setTitle($post->getPostTitle());
            $content = preg_replace('/\[(.*?)\]/is', '', $post->getPostContent());

            $blog->setContent($content);
            $blog->setPostDate($post->getPostDate());
            $blog->setDescription('');
            $blog->setSeoTitle('');
            $blog->setUrl();

            $em->persist($blog);
        }

        $em->flush();
    }

    private function addSeo(EntityManager $em)
    {
        /** @var WpPosts[] $posts */
        $posts = $em->getRepository('MigrationBundle:WpPosts')->findBy(['postStatus' => 'publish', 'postType' => 'post']);

        foreach ($posts as $post) {
            $author_image_id = $em->getRepository('MigrationBundle:WpPostmeta')->findOneBy(['postId' => $post->getId(), 'metaKey' => 'author_image']);
            $author_image = $em->getRepository('MigrationBundle:WpPosts')->find($author_image_id->getMetaValue());

            if ($author_image->getGuid()) {
                $author_link = $em->getRepository('MigrationBundle:WpPostmeta')->findOneBy(['postId' => $post->getId(), 'metaKey' => 'author_link']);

                $blog_user = $em->getRepository('RestBundle:User')->find($post->getPostAuthor());
                $temp = explode('/', $author_image->getGuid());
                $filename = end($temp);
                copy($author_image->getGuid(), $this->getContainer()->getParameter('upload_dir') . '/avatars/' . $filename);
                $blog_user->setLink($author_link->getMetaValue());
                $blog_user->setAvatar($filename);
                $em->persist($blog_user);
            }

            $post_description = $em->getRepository('MigrationBundle:WpPostmeta')->findOneBy(['postId' => $post->getId(), 'metaKey' => '_yoast_wpseo_metadesc']);

            if ($post_description) {
                $post_title = $em->getRepository('MigrationBundle:WpPostmeta')->findOneBy(['postId' => $post->getId(), 'metaKey' => '_yoast_wpseo_title']);
                $blog = $em->getRepository('RestBundle:Blog')->findOneBy(['url' => $post->getPostName()]);
                $blog->setSeoTitle($post_title->getMetaValue());
                $blog->setDescription($post_description->getMetaValue());
                $em->persist($blog);
            }
        }

        $em->flush();
    }

    private function updateImageLinks(EntityManager $em)
    {
        /** @var WpPosts[] $posts */
        $posts = $em->getRepository('MigrationBundle:WpPosts')->findBy(['postStatus' => 'publish', 'postType' => 'post']);

        foreach ($posts as $post) {
            $blog = $em->getRepository('RestBundle:Blog')->findOneBy(['url' => $post->getPostName()]);
            $attachment = $em->getRepository('MigrationBundle:WpPosts')->findOneBy(['postParent' => $post->getId(), 'postType' => 'attachment']);

            if ($attachment) {
                $blog->setImageAlt($attachment->getPostContent());
            }

            $em->persist($blog);
        }

        $em->flush();
    }

    private function addNewSeo(EntityManager $em)
    {
        $pages = [
            'career-advice',
            'linkedin',
            'resume',
            'interviewing',
            'job-search',
        ];

        foreach ($pages as $page) {
            /** @var WpPosts $post */
            $post = $em->getRepository('MigrationBundle:WpPosts')->findOneBy(['postName' => $page]);

            $post_description = $em->getRepository('MigrationBundle:WpPostmeta')->findOneBy(['postId' => $post->getId(), 'metaKey' => '_yoast_wpseo_metadesc']);
dump($post_description);
            if ($post_description) {
                $post_title = $em->getRepository('MigrationBundle:WpPostmeta')->findOneBy(['postId' => $post->getId(), 'metaKey' => '_yoast_wpseo_title']);
                $blog = $em->getRepository('RestBundle:Blog')->findOneBy(['url' => $post->getPostName()]);
                $blog->setSeoTitle($post_title->getMetaValue());
                $blog->setDescription($post_description->getMetaValue());
                $em->persist($blog);
            }
        }

        $em->flush();
    }

    private function updateUsers(EntityManager $em)
    {
        $users = $em->getRepository('RestBundle:User')->findBy(['is_active' => 1]);

        foreach ($users as $user) {
            $user->addRole('ROLE_USER');
            $em->persist($user);
        }

        $em->flush();
    }

    private function updateCategories(EntityManager $em)
    {
        $categories = $em->getRepository('RestBundle:Category')->findAll();

        foreach ($categories as $category) {
            $term_category_id = $em->getRepository('MigrationBundle:WpTerms')->findOneBy(['slug' => $category->getCategory()]);
            $articles_ids = $em->getRepository('MigrationBundle:WpTermRelationships')->findBy(['termTaxonomyId' => $term_category_id]);

            foreach ($articles_ids as $articles_id) {
                $article = $em->getRepository('MigrationBundle:WpPosts')->findOneBy(['id' => $articles_id->getObjectId()]);
                if ($article) {
                    $blog = $em->getRepository('RestBundle:Blog')->findOneBy(['url' => $article->getPostName()]);
                    $blog->addCategory($category);
                    $em->persist($blog);
                }
            }
        }

        $em->flush();
    }
}
