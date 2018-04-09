<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\ResourceType;
use RestBundle\Entity\Resource;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResourceController extends Controller
{
    /**
     * @Route("/admin/resource", name="list_resources")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(ResourceType::class);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                /** @var Resource $resource */
                $resource = $form->getData();
                $resource->setName($resource->getResource()->getClientOriginalName());
                $em = $this->getDoctrine()->getManager();
                $em->persist($resource);
                $em->flush();

                $this->addFlash(
                    'success',
                    'Resource was upload successfully'
                );

                return $this->redirectToRoute('list_resources');
            }

            $this->addFlash('error', 'Resource was not upload');
        }

        // $resources = $this->getDoctrine()->getRepository('RestBundle:Resource')->findAll();
        $resources = $this->getDoctrine()->getRepository('RestBundle:Resource')->findBy(array(), array('sort' => 'ASC'));;

        return $this->render('@Admin/Admin/resources.html.twig', array(
            'resources' => $resources,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/admin/resource/sortdata_update", name="resource_sort_update")
     * @Method("POST")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function resourceSortAction(Request $request)
    {
        $resource_data = $request->get('resources');

        $em = $this->getDoctrine()->getManager();
        for( $i = 0 ; $i < count($resource_data) ; $i++ )
        {
            $resource = $em->getRepository('RestBundle:Resource')->find( $resource_data[$i] );
            if($resource)
            {
                $resource->setSort($i);
                $em->persist($resource);
                $em->flush();
            }
            
        }
            return $this->redirectToRoute('list_resources');
    }

    /**
     * @Route("/admin/resource/download/{resource}", name="resource_link")
     * @param Resource $resource
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function downloadAction(Resource $resource)
    {
        $downloadHandler = $this->get('vich_uploader.download_handler');
        $fileName   = $resource->getName();

        return $downloadHandler->downloadObject($resource, $fileField = 'resource', $objectClass = null, $fileName);
    }

    /**
     * @Route("/admin/resource/remove/{resource}", name="resource_remove")
     * @Method("GET")
     * @param Resource $resource
     * @return Response
     */
    public function removeAction(Resource $resource)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($resource);
        $em->flush();

        $this->addFlash(
            'success',
            'Resource was delete successfully'
        );

        return $this->redirectToRoute('list_resources');
    }
}
