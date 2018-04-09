<?php
namespace AdminBundle\Controller;

use AdminBundle\Form\CongratulationMessageType;
use AdminBundle\Form\SettingsType;
use RestBundle\Entity\Settings;
use RestBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SettingsController extends Controller
{
    /**
     * @Route("/admin/settings", name="settings")
     * @param Request $request
     * @return Response
     */
    public function settingsAction(Request $request)
    {
        $form = $this->createForm(SettingsType::class);

        if ($request->isMethod('POST')) {
            /** @var User $user */
            $user = $this->getUser();
            $form->handleRequest($request);

            $data = $form->getData();

            $valid = $this->get('security.password_encoder')->isPasswordValid(
                $user,
                $data['current_password']
            );

            if ($valid) {
                if ($data['new_password'] === $data['confirm_password']) {
                    $user->setPassword($data['new_password']);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();

                    $this->addFlash(
                        'success',
                        'Your password has been changed'
                    );

                    return $this->redirectToRoute('settings');
                }

                $this->addFlash(
                    'error',
                    'New password and confirm password doesn\'t match'
                );
            } else {
                $this->addFlash(
                    'error',
                    'Current password is not valid'
                );
            }
        }

        return $this->render('@Admin/Admin/settings.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/admin/settings/chat", name="chat_settings")
     * @param Request $request
     * @return Response
     */
    public function chatSettingsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $settings = $em->getRepository('RestBundle:Settings')->find(1);

        if (!$settings) {
            $settings = new Settings();
            $settings->setId(1);
            $settings->setCongratulationMessage('Hello user');
        }

        $form = $this->createForm(CongratulationMessageType::class, $settings);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid() && !$form->get('congratulationMessage')->isEmpty()) {
                $em->persist($settings);
                $em->flush();

                $this->addFlash(
                    'success',
                    'Your changes has been saved'
                );

                return $this->redirectToRoute('chat_settings');
            }

            $this->addFlash('error', 'Content can\'t be empty');

            $errors = $this->get('validator')->validate($settings);

            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }

        return $this->render('@Admin/Admin/chat_settings.html.twig', array('form' => $form->createView()));
    }
}