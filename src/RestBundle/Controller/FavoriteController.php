<?php

namespace RestBundle\Controller;

use FOS\RestBundle\Context\Context;
use RestBundle\Entity\Blog;
use RestBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\FOSRestController as Controller;
use Symfony\Component\HttpFoundation\Request;

class FavoriteController extends Controller
{
    /**
     * @Route("/favorites/{blog}", name="add_favorite")
     * @Method("PUT")
     * @param Blog $blog
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Blog $blog)
    {
        /** @var User $user */
        $user = $this->getUser();

        $user->addLike($blog);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->handleView($this->view(['status' => 'Ok']));
    }

    /**
     * @Route("/favorites/{blog}", name="is_favorite")
     * @Method("GET")
     * @param Blog $blog
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction(Blog $blog)
    {
        /** @var User $user */
        $user = $this->getUser();
        $likes = $user->getLikes();
        $is_liked = false;

        if (count($likes) > 0 && in_array($blog, $likes, true)) {
            $is_liked = true;
        }

        return $this->handleView($this->view(['is_liked' => $is_liked]));
    }



    /**
     * @Route("/favorites/{blog}", name="remove_favorite")
     * @Method("DELETE")
     * @param Blog $blog
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeAction(Blog $blog)
    {
        /** @var User $user */
        $user = $this->getUser();

        $user->removeLike($blog);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->handleView($this->view(['status' => 'Ok']));
    }

    /**
     * @Route("/favorites", name="favorite")
     * @Method("GET")
     * @Rest\QueryParam(name="page", requirements="\d+", default="1")
     * @param ParamFetcher $paramFetcher
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(ParamFetcher $paramFetcher)
    {
        $user_likes = $this->getDoctrine()->getRepository('RestBundle:Blog')->getUserLikes(
            $this->getUser(),
            $paramFetcher->get('page')
        );

        return $this->handleView($this->view($user_likes)->setContext((new Context())->setGroups([
            'list'
        ])));
    }

    /**
     * @Route("/favorites/blog-likes", name="favorite_blog_likes")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function likeArticlesAction(Request $request)
    {
        $like_blog = [];
        $blog_ids = $request->get('blog');

        if (!empty($blog_ids)) {
            /** @var Blog[] $likes */
            $likes = $this->getUser()->getLikes();

            foreach ($likes as $like) {
                if (in_array($like->getId(), $blog_ids, true)) {
                    $like_blog[$like->getId()] = true;
                }
            }

            return $this->handleView($this->view([$like_blog]));
        }

        return $this->handleView($this->view(['status' => 'Fail'], 400));
    }
}
