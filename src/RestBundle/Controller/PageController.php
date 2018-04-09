<?php

namespace RestBundle\Controller;

use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\FOSRestController as Controller;
use RestBundle\Form\ContactUsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller
{
    /**
     * @Route("/home", name="home")
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $page = $this->getDoctrine()
            ->getRepository('RestBundle:Page')
            ->findOneBy(['slug' => 'find-profession-best-career-advice-career-finder']);

        $vm = $this->get('app.transformer')->transform($page);

        return $this->handleView($this->view($vm));
    }

    /**
     * @Route("/faq", name="faq")
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function faqAction()
    {
        $page = $this->getDoctrine()
            ->getRepository('RestBundle:Page')
            ->findOneBy(['slug' => 'faq']);

        $vm = $this->get('app.transformer')->transform($page);

        return $this->handleView($this->view($vm));
    }

    /**
     * @Route("/terms-of-use", name="terms-of-use")
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function termsOfUseAction()
    {
        $page = $this->getDoctrine()
            ->getRepository('RestBundle:Page')
            ->findOneBy(['slug' => 'terms-of-use']);

        $vm = $this->get('app.transformer')->transform($page);

        return $this->handleView($this->view($vm));
    }

    /**
     * @Route("/about-us", name="about-us")
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function aboutUsAction()
    {
        $page = $this->getDoctrine()
            ->getRepository('RestBundle:Page')
            ->findOneBy(['slug' => 'about-us']);

        $vm = $this->get('app.transformer')->transform($page);

        return $this->handleView($this->view($vm));
    }

    /**
     * @Route("/contact-us", name="contact-us")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contactUsAction(Request $request)
    {
        $page = $this->getDoctrine()
            ->getRepository('RestBundle:Page')
            ->findOneBy(['slug' => 'contact-us']);

        if ($request->isMethod('POST')) {
            $form = $this->createForm(ContactUsType::class);
            $form->submit($request->request->all(), false);

            if ($form->isValid()) {
                $data = $form->getData();
                $body = 'From: ' . $data['name'];
                $body .= ' Subject: FMP - How Can We Help';
                $body .= ' Message Body: ' . $data['message'] . ' -- This e-mail was sent from a contact form on Find My Profession';
                $body .= ' (<a href="https://www.findmyprofession.com/contact-us">https://www.findmyprofession.com/contact-us</a>)';

                $this->get('mailer')->send(\Swift_Message::newInstance()
                    ->setSubject('FMP - How Can We Help')
                    ->setFrom($this->getParameter('noreply_email'), $data['name'])
                    ->setTo($this->getParameter('help_email'))
                    ->setReplyTo($data['email'])
                    ->setBody($body, 'text/html')
                );

                return $this->handleView($this->view(['status' => 'Ok', 'message' => 'The email was sent']));
            }

            return $this->handleView($this->view([
                'status' => 'Fail',
                'message' => $this->get('app.form_errors')->getErrors($form)
            ]));
        }

        $vm = $this->get('app.transformer')->transform($page);

        return $this->handleView($this->view($vm));
    }
}
