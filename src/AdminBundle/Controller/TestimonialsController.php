<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\TestimonialType;
use RestBundle\Entity\Testimonial;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TestimonialsController
 * @package AdminBundle\Controller
 */
class TestimonialsController extends Controller
{
    /**
     * @Route("/admin/testimonials/{page}/{filter}",
     *     requirements={"page":"\d+", "filter": "null|career-finder|resume-makeover|cover-letter-writing|linkedin-profile-makeover|interview-training"},
     *     defaults={"page": 1, "filter": null}, name="list_testimonials")
     *
     * @param Request $request
     * @param $page
     * @param $filter
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, $page, $filter)
    {
        if ($request->isMethod('POST') && $request->request->has('filter')) {
            $page = 1;
            $filter = $request->request->get('filter');
        }

        if ($filter === 'show-on-homepage') {
            $testimonials = $this->getDoctrine()->getRepository('RestBundle:Testimonial')->getOnHomepage($page);
        } else {
            $testimonials = $this->getDoctrine()->getRepository('RestBundle:Testimonial')->getTestimonials($page, $filter);
        }

        return $this->render('@Admin/Admin/testimonials.html.twig', [
            'pages' => $testimonials['count'],
            'filter' => $filter,
            'current' => $page,
            'testimonials' => $testimonials['testimonials']
        ]);
    }

    /**
     * @Route("/admin/testimonials/add", name="add_testimonial")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $testimonial = new Testimonial();
        $form = $this->createForm(TestimonialType::class, $testimonial);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($testimonial);
                $em->flush();

                $this->addFlash(
                    'success',
                    'Testimonial was added successfully'
                );

                return $this->redirectToRoute('edit_testimonial', ['testimonial' => $testimonial->getId()]);
            }

            $errors = $this->get('validator')->validate($testimonial);

            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }

        return $this->render('@Admin/Admin/testimonial_editor.html.twig', [
            'form' => $form->createView(),
            'testimonial' => $testimonial
        ]);
    }

    /**
     * @Route("/admin/testimonials/delete/{testimonial}", name="delete_testimonial")
     * @Method("GET")
     * @param Testimonial $testimonial
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Testimonial $testimonial)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($testimonial);
        $em->flush();

        $this->addFlash(
            'success',
            'Testimonial was delete successfully'
        );

        return $this->redirectToRoute('list_testimonials');
    }

    /**
     * @Route("/admin/testimonials/edit/{testimonial}", name="edit_testimonial")
     * @param Request $request
     * @param Testimonial $testimonial
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Testimonial $testimonial)
    {
        $form = $this->createForm(TestimonialType::class, $testimonial);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                if ($testimonial->getAvatar() instanceof UploadedFile) {
                    $testimonial->setAvatarName($testimonial->getAvatar()->getClientOriginalName());
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($testimonial);
                $em->flush();

                $this->addFlash(
                    'success',
                    'Your changes has been saved'
                );

                return $this->redirect($request->getRequestUri());
            }

            $errors = $this->get('validator')->validate($testimonial);

            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }

        return $this->render('@Admin/Admin/testimonial_editor.html.twig', [
            'form' => $form->createView(),
            'testimonial' => $testimonial
        ]);
    }
}
