<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\ServiceType;
use RestBundle\Entity\Service;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ServicesController
 * @package AdminBundle\Controller
 */
class ServicesController extends Controller
{
    /**
     * @Route("/admin/services", name="view_list_services")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $services = $this->getDoctrine()->getManager()->getRepository('RestBundle:Service')->findAll();

        return $this->render('@Admin/Admin/services.html.twig', array('services' => $services));
    }

    /**
     * @Route("/admin/services/{service}", name="edit_service")
     * @param Request $request
     * @param Service $service
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Service $service)
    {
        $form = $this->createForm(ServiceType::class, $service);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($service);
                $em->flush();

                $this->addFlash(
                    'success',
                    'Your changes has been saved'
                );

                return $this->redirect($request->getRequestUri());
            }

            $errors = $this->get('validator')->validate($service);

            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }

        $premium = false;

        if ( null === $service->getPriceExecutive() && null === $service->getPriceSenior() ) {
            $premium = true;
        }

        return $this->render('@Admin/Admin/service_editor.html.twig', array('form' => $form->createView(), 'premium' => $premium));
    }
}
