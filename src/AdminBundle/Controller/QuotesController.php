<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\QuotesType;
use RestBundle\Entity\Quotes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class QuotesController
 * @package AdminBundle\Controller
 */
class QuotesController extends Controller
{
    /**
     * @Route("/admin/quotes/{quotes}", name="edit_quotes")
     * @param Request $request
     * @param Quotes $quotes
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Quotes $quotes)
    {
        $form = $this->createForm(QuotesType::class, $quotes);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($quotes);
                $em->flush();

                $this->addFlash(
                    'success',
                    'Your changes has been saved'
                );

                return $this->redirect($request->getRequestUri());
            }

            // $errors = $this->get('validator')->validate($quotes);

            // if (count($errors) > 0) {
            //     foreach ($errors as $error) {
            //         $this->addFlash('error', $error->getMessage());
            //     }
            // }
        }

        return $this->render('@Admin/Admin/quotes_editor.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/admin/add_quotes", name="add_quotes")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $quotes = new Quotes();
        $form = $this->createForm(QuotesType::class, $quotes);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($quotes);
                $em->flush();

                $this->addFlash(
                    'success',
                    'Quotes was added successfully'
                );

                return $this->redirectToRoute('edit_quotes', array('quotes' => $quotes->getId()));
            }

            $errors = $this->get('validator')->validate($quotes);

            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }

        return $this->render('@Admin/Admin/quotes_editor.html.twig', array(
            'form' => $form->createView(),
            'quotes' => $quotes
        ));
    }

    /**
     * @Route("/admin/quotes", name="view_list_quotes")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $quotes = $this->getDoctrine()->getRepository('RestBundle:Quotes')->findAll();

        return $this->render('@Admin/Admin/quotes.html.twig', array('quotes' => $quotes));
    }

    /**
     * @Route("/admin/quotes/delete/{quotes}", name="delete_quotes")
     * @param Quotes $quotes
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function deleteAction(Quotes $quotes)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($quotes);

        $em->flush();

        $this->addFlash(
            'success',
            'Quotes was deleted successfully.'
        );

        return $this->redirectToRoute('view_list_quotes');
    }

}
