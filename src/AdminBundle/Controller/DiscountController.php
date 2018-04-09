<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\DiscountType;
use RestBundle\Entity\Discount;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DiscountController
 * @package AdminBundle\Controller
 */
class DiscountController extends Controller
{
    /**
     * @Route("/admin/discounts/add", name="add_discount")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $discount = new Discount();
        $form = $this->createForm(DiscountType::class, $discount);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($discount);
                $em->flush();

                $this->addFlash(
                    'success',
                    'Discount was added successfully'
                );

                return $this->redirectToRoute('view_discounts');
            }

            $errors = $this->get('validator')->validate($discount);

            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }

        return $this->render('@Admin/Admin/discount_form.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/admin/discounts/{discount}", name="edit_discount")
     * @param Request $request
     * @param Discount $discount
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Discount $discount)
    {
        $form = $this->createForm(DiscountType::class, $discount);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($discount);
                $em->flush();

                $this->addFlash(
                    'success',
                    'Discount was updated successfully'
                );

                return $this->redirectToRoute('view_discounts');
            }

            $errors = $this->get('validator')->validate($discount);

            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }

        return $this->render('@Admin/Admin/discount_form.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/admin/discounts", name="view_discounts")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $discounts = $this->getDoctrine()->getRepository('RestBundle:Discount')->findAll();

        return $this->render('@Admin/Admin/discounts.html.twig', array('discounts' => $discounts));
    }

    /**
     * deleteAction
     *
     * @Route("/admin/discounts/delete/{discount}", requirements={"page": "\d+"}, name="delete_discount")
     *
     * @param Discount $discount
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Discount $discount)
    {
        $this->getDoctrine()->getManager()->remove($discount);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('view_discounts');
    }
}
