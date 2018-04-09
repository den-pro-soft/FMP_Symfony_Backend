<?php

namespace RestBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController as Controller;
use RestBundle\Entity\CareerPreferences;
use RestBundle\Entity\Message;
use RestBundle\Entity\Profile;
use RestBundle\Entity\Settings;
use RestBundle\Entity\User;
use RestBundle\Exception\AccessDeniedException;
use RestBundle\Exception\PasswordException;
use RestBundle\Exception\UserNotFoundException;
use RestBundle\Form\SignUpType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SecurityController extends Controller
{
    /**
     * @Route("/signin", name="signin")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function signInAction(Request $request)
    {
        $user = $this->getUserByUsername($request->get('email'));
        $em = $this->getDoctrine()->getManager();

        if ($timezone = $request->get('timezone')) {
            $user->setTimezone($timezone);
            $em->persist($user);
        }

        $isValid = $this->get('security.password_encoder')->isPasswordValid($user, $request->get('password'));

        if (!$isValid) {
            throw new PasswordException();
        }

        $user->setLastActive(new \DateTime());
        $em->persist($user);
        $em->flush();

        $messages = $em->getRepository('RestBundle:Message')->getUserMessages($user);

        if (count($messages) === 0) {
            $congratulation_message = new Message();
            $congratulation_message->setOwner($user->getId());
            $congratulation_message->addUnreadUser($user);
            if( $user->getAdmins()->count() > 0 )
            $congratulation_message->setAuthor($user->getAdmins()->first()->getId());
            $congratulation_message->setTypeSender(1);
            /** @var Settings $settings */
            $settings = $em->getRepository('RestBundle:Settings')->find(1);

            if ($settings) {
                $message_text = $settings->getCongratulationMessage();
                $congratulation_message->setMessage($message_text);
                $em->persist($congratulation_message);
            }
        }

        $em->flush();

        return $this->handleView($this->view($user));
    }

    /**
     * @Route("/forgot-password", name="forgot-password")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function forgotPasswordAction(Request $request)
    {
        $user = $this->getUserByUsername($request->get('email'));
        $hash = hash('sha1', random_bytes(16));
        $user->setTemporaryToken($hash);

        $link = $this->generateUrl(
            'reset-password', [
                't' => $hash
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $port = parse_url($link, PHP_URL_PORT);

        if ($port) {
            $link = str_replace($port, '443', $link);
        }

        $this->get('user.mailer')->sendUserForgotPassword($user, $link);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->handleView($this->view(['status' => 'Ok', 'message' => 'Mail was sent']));
    }

    /**
     * @Route("/change-password", name="change-password")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function changePasswordAction(Request $request)
    {
        if ($request->get('token')) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('RestBundle:User')->findOneBy(['temporary_token' => $request->get('token')]);

            if (!$user instanceof User) {
                throw new AccessDeniedException();
            }

            $this->checkPassword($request->get('password'), $request->get('confirm_password'));

            $user->setTemporaryToken(null);
            $user->setPassword($request->get('password'));
            $em->persist($user);
            $em->flush();
        } else {
            throw new AccessDeniedException();
        }

        return $this->handleView($this->view(['status' => 'Ok', 'message' => 'Password has been changed'], 200));
    }

    /**
     * @param string $username
     * @return null|User
     * @throws \Exception
     */
    private function getUserByUsername($username)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('RestBundle:User')->findOneBy(['username' => $username, 'is_active' => true]);

        if (!$user instanceof User || $user->hasRole('ROLE_ADMIN')) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    /**
     * @param $password
     * @param $confirm_password
     * @throws PasswordException
     */
    private function checkPassword($password, $confirm_password)
    {
        if (null === $password || null === $confirm_password) {
            throw new PasswordException('Password or confirm password is empty');
        }

        if ($password !== $confirm_password) {
            throw new PasswordException('Password does not match');
        }
    }
}
