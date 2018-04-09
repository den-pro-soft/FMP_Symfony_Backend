<?php

namespace RestBundle\Controller;

use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\FOSRestController as Controller;
use RestBundle\Entity\Page;
use RestBundle\Entity\Service;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ServicesController extends Controller
{
    /**
     * @Route("/coaching-services", name="all_services")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allAction()
    {
        $services = $this->getDoctrine()->getRepository('RestBundle:Service')->findAll();

        return $this->handleView($this->view($services)->setContext((new Context())->setGroups([
            'other_services'
        ])));
    }

    /**
     * @Route("/coaching-services/{service}", name="services")
     * @ParamConverter("service", class="RestBundle:Page", options={"mapping": {"service": "slug"}})
     * @param Page $service
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function serviceAction(Page $service)
    {
        $vm = $this->get('app.transformer')->transform($service);

        return $this->handleView($this->view($vm));
    }

    /**
     * @Route("/price-services/{service}", name="price_services")
     * @param Service $service
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function careerFinderAction(Service $service)
    {
        return $this->handleView($this->view($service)->setContext((new Context())->setGroups([
            'price_services'
        ])));
    }
}
