<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LoginController
 * @package AdminBundle\Controller
 */
class LoginController extends Controller
{
    /**
     * @Route("/admin", name="index")
     * @return RedirectResponse
     */
    public function indexAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')){
            return $this->redirectToRoute('dashboard');
        }

        return $this->redirectToRoute('login');
    }

    /**
     * @Route("/admin/login", name="login")
     * @return Response
     */
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($error) {
            $this->addFlash('error', $error->getMessage());
        }

        return $this->render('@Admin/Security/login.html.twig', array(
            'last_username' => $lastUsername
        ));
    }

//    /**
//     * @Route("/admin/forgot-password", name="admin_forgot_password")
//     * @param Request $request
//     * @return Response
//     */
//    public function forgotPasswordAction(Request $request)
//    {
//        if ($request->isMethod('POST')) {
//            $user = $this->getDoctrine()->getRepository('RestBundle:User')->findOneBy(['username' => $request->get('email')]);
//
//            if (!$user instanceof User) {
//                $this->addFlash('error', 'User not found');
//                return $this->render('@Admin/Security/forgot-password.html.twig');
//            }
//
//            $hash = hash('sha1', random_bytes(16));
//            $user->setTemporaryToken($hash);
//
//            $link = $this->generateUrl(
//                    'reset-password', [
//                    't' => $hash
//                ],
//                UrlGeneratorInterface::ABSOLUTE_URL
//            );
//
//            $this->get('user.mailer')->sendUserForgotPassword($user, $link);
//
//            return $this->redirectToRoute('login');
//        }
//
//        return $this->render('@Admin/Security/forgot-password.html.twig');
//    }
}
