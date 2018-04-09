<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\TemplateType;
use RestBundle\Entity\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TemplatesController extends Controller
{
    /**
     * @Route("/admin/templates", name="list_templates")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(TemplateType::class);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                /** @var Template $template */
                $admin = $this->getUser();

                $template = $form->getData();
                $template->setName($template->getTemplate()->getClientOriginalName());
                $template->setAddedBy($admin->getFullName());
                $em = $this->getDoctrine()->getManager();
                $em->persist($template);
                $em->flush();

                $this->addFlash(
                    'success',
                    'Template was upload successfully'
                );

                return $this->redirectToRoute('list_templates');
            }

            $this->addFlash('error', 'Template was not upload');
        }

        $templates = $this->getDoctrine()->getRepository('RestBundle:Template')->findAll();

        return $this->render('@Admin/Admin/templates.html.twig', array(
            'templates' => $templates,
            'file_form' => $form->createView()
        ));
    }

    /**
     * @Route("/admin/templates/download/{template}", name="template_link")
     * @param Template $template
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function downloadAction(Template $template)
    {
        $downloadHandler = $this->get('vich_uploader.download_handler');
        $fileName   = $template->getName();

        return $downloadHandler->downloadObject($template, $fileField = 'template', $objectClass = null, $fileName);
    }

    /**
     * @Route("/admin/templates/remove/{template}", name="template_remove")
     * @Method("GET")
     * @param Template $template
     * @return Response
     */
    public function removeAction(Template $template)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($template);
        $em->flush();

        $this->addFlash(
            'success',
            'Template was delete successfully'
        );

        return $this->redirectToRoute('list_templates');
    }

    /**
     * @Route("/admin/templates/add", name="admin_templates_add")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addDocumentAction(Request $request)
    {
        $templates = new Template();
        $form = $this->createForm(TemplateType::class, $templates);
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var User $admin */
            $admin = $this->getUser();
            $templates->setName($templates->getTemplate()->getClientOriginalName());
            $templates->setAddedBy($admin->getFullName());
            // $profile = $user->getProfile();
            // $documents->setProfile($profile);
            // $templates->addTemplate($templates);

            $em = $this->getDoctrine()->getManager();
            // $em->persist($user);
            $em->flush();

            $this->addFlash(
                'success',
                'Document was upload successfully'
            );
        } else {
            $errors = $this->get('validator')->validate($templates);

            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }

        return $this->redirectToRoute('list_templates');
    }

}
