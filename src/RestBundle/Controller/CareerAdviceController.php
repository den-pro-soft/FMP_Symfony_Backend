<?php

namespace RestBundle\Controller;

use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\FOSRestController as Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Request\ParamFetcherInterface;
use RestBundle\Entity\Blog;
use RestBundle\Entity\Category;
use RestBundle\Entity\Top; 
use RestBundle\Entity\User;
use RestBundle\Exception\ApiException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class CareerAdviceController extends Controller
{
    /**
     * @Route("/blog/{category}", requirements={"category": "career-advice|linkedin|resume|interviewing|job-search"}, name="all_blog")
     * @Method("GET")
     * @Rest\QueryParam(name="page", requirements="\d+", default="1")
     * @Rest\QueryParam(name="searchStr")
     *
     * @param ParamFetcher $paramFetcher
     * @param $category
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(ParamFetcher $paramFetcher, $category)
    {
        $career_advices = $this->getCareerAdvices($category, $paramFetcher->get('page'), $paramFetcher->get('searchStr'));
        $page = $this->getDoctrine()->getRepository('RestBundle:Page')->getPage($category);
        
        $vm = $this->get('app.transformer')->transform($page  );
        $vm->setCount($career_advices['count']);
        $vm->setBlogs($career_advices['blogs']);
        $vm->setTop($career_advices['tops']);
        $vm->setTotalCount($career_advices['total_count']);

        return $this->handleView($this->view($vm)->setContext((new Context())->setGroups([
            'list',
            'tops' => ['top']
        ])));
    }

    /**
     * @Route("/career-advice/subscribe", name="subscribe_blog")
     * @Method("POST")
     * @Rest\RequestParam(name="email", description="Subscriber email.", nullable=false)
     * @param ParamFetcherInterface $paramFetcherInterface
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function subscribeAction(ParamFetcherInterface $paramFetcherInterface)
    {
        $response = $this->get('mailchimp.notification')->subscribe($paramFetcherInterface->get('email'), 'd54e95e2f1');

        return $this->handleView($this->view(['message' => $response['message']], $response['code']));
    }

    /**
     * @Route("/career-advice/{title}", name="view_blog")
     * @Method("GET")
     * @param string $title
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws ApiException
     */
    public function viewAction($title)
    {
        $em = $this->getDoctrine()->getManager();
        $blog = $em->getRepository('RestBundle:Blog')->findOneBy(['url' => $title, 'status' => 'Publish']);

        if (!$blog instanceof Blog) {
            throw new ApiException('Article Not Found');
        }

        $views = $blog->getViews();
        $blog->setViews(++$views);
        $em->persist($blog);
        $em->flush();
        $category = $blog->getCategory()->toArray();

        if (count($category) === 1) {
            $category = $category[0];
        } elseif (count($category) > 1) {
            
            $categories = $blog->getCategory();

            foreach ($categories as $cat) {
                if (in_array($cat, $category, true)) {
                    $category = $cat;
                    break;
                }
            }
        } else {
            $category = 'career-advice';
        }

        if (!$category instanceof Category) {
            $category = $em->getRepository('RestBundle:Category')->findOneBy(['category' => 'career-advice']);
        }

        $random_blogs = $em->getRepository('RestBundle:Blog')->getRandomBlogsByCategory($category, $blog->getId());
        $tops = $em->getRepository('RestBundle:Blog')->getTops($category, $blog->getId(), 5);

        $blog->setTops($tops);
        $blog->setRandomBlogs($random_blogs);

        return $this->handleView($this->view($blog)->setContext((new Context())->setGroups([
            'details',
            /*'tops' => ['top'],*/
            'random_blogs' => [
                'random_blog',
                'author' => ['random_blog']
            ]
            ])));
    }

    /**
     * @Route("/blog/slugs", name="slugs_blog")
     * @Method("GET")
     */
    public function allSlugsAction()
    {
        $blogs = $this->getDoctrine()->getRepository('RestBundle:Blog')->findBy([
            'status' => 'Publish', 'isRemoved' => 0
        ]);

        return $this->handleView($this->view($blogs)->setContext((new Context())->setGroups(['slugs'])));
    }

    /**
     * @Route("/sitemap/generate", name="generate_sitemap")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function siteMapAction()
    {
        $result = $this->get('sitemap.generator')->generate();

        if ($result) {
            $body = 'Ok';
            $status = JsonResponse::HTTP_OK;
        } else {
            $body = 'Fail';
            $status = JsonResponse::HTTP_BAD_REQUEST;
        }

        return new JsonResponse([$body], $status);
    }

    /**
     * @param $category
     * @param $page
     * @return array
     */
    private function getCareerAdvices($category , $page , $searchStr)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('RestBundle:Category')->findOneBy(['category' => $category]);

        if( $searchStr )
            $career_advices = $em->getRepository('RestBundle:Blog')->getCareerAdvicesByCategoryAndQuery($category , $searchStr, $page); 
        else 
            $career_advices = $em->getRepository('RestBundle:Blog')->getCareerAdvicesByCategory($category, $page);

        $headers = $this->get('request')->headers;

        if ($headers->has('token')) {
            /** @var User $user */
            $user = $em->getRepository('RestBundle:User')->findOneBy(['token' => $headers->get('token')]);
            $likes = $user->getLikes();

            if (count($likes)) {
                /** @var array $career_advices */
                foreach ($career_advices['blogs'] as $career_advice) {
                    /** @var Blog $career_advice */
                    if (in_array($career_advice, $likes, true)) {
                        $career_advice->setLiked(true);
                    }
                }
            }
        }

        $career_advices['tops'] = $em->getRepository('RestBundle:Blog')->getTopsByCategory($category); 

        return $career_advices;
    }
}
