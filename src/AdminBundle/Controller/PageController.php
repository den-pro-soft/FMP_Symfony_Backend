<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\PageType;
use RestBundle\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PageController
 * @package AdminBundle\Controller
 */
class PageController extends Controller
{
    /**
     * @Route("/admin/pages/{page}", name="edit_page")
     * @param Request $request
     * @param Page $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Page $page)
    {
        $form = $this->createForm(PageType::class, $page);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($page);
                $em->flush();

                $this->addFlash(
                    'success',
                    'Your changes has been saved'
                );

                return $this->redirect($request->getRequestUri());
            }

            $errors = $this->get('validator')->validate($page);

            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }

        return $this->render('@Admin/Admin/page_editor.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/admin/pages", name="view_list_pages")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $pages = $this->getDoctrine()->getRepository('RestBundle:Page')->findAll();

        return $this->render('@Admin/Admin/pages.html.twig', array('pages' => $pages));
    }
}
