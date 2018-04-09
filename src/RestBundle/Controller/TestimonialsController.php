<?php

namespace RestBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\FOSRestController as Controller;

class TestimonialsController extends Controller
{
    /**
     * @Route("/testimonials", name="testimonials_home")
     * @Rest\QueryParam(name="page", requirements="\d+", default="1")
     *
     * @param ParamFetcher $paramFetcher
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(ParamFetcher $paramFetcher)
    {
        $testimonials = $this->getDoctrine()
            ->getRepository('RestBundle:Testimonial')
            ->getTestimonials($paramFetcher->get('page'));

        $page = $this->getDoctrine()
            ->getRepository('RestBundle:Page')
            ->getPage('testimonials');

        $vm = $this->get('app.transformer')->transform($page);
        $vm->setTestimonials($testimonials);

        return $this->handleView($this->view($vm));
    }
}
