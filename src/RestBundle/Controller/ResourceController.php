<?php

namespace RestBundle\Controller;

use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\FOSRestController as Controller;
use AdminBundle\Form\ResourceType;
use RestBundle\Entity\Resource;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResourceController extends Controller
{
    /**
     * @Route("/resources", name="resource")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function resourceAction()
    {
        $resource = $this->getDoctrine()->getRepository('RestBundle:Resource')->findBy([], ['sort' => 'ASC']);
        return $this->handleView($this->view($resource)->setContext((new Context())->setGroups(['resource', 'Default'])));
    }
}
