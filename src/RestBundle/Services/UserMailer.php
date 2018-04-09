<?php

namespace RestBundle\Services;

use RestBundle\Entity\Message;
use RestBundle\Entity\User;
use Symfony\Bridge\Twig\TwigEngine;

class UserMailer
{
    private $templating;
    private $mailer;
    private $email_from;
    private $admin_email;

    /**
     * UserMailer constructor.
     * @param TwigEngine $templating
     * @param \Swift_Mailer $mailer
     * @param $email_from
     * @param $admin_email
     */
    public function __construct(TwigEngine $templating, \Swift_Mailer $mailer, $email_from, $admin_email)
    {
        $this->templating = $templating;
        $this->mailer = $mailer;
        $this->email_from = $email_from;
        $this->admin_email = $admin_email;
    }

    public function setEmailFrom( $email_from )
    {
        $this->email_from = $email_from;
    }

    /**
     * @param User $user
     * @param $link
     * @throws \Twig_Error
     */
    public function sendUserForgotPassword(User $user, $link)
    {
        $body = $this->templating->render('@Rest/Email/forgot_password.html.twig', array(
            'link' => $link
        ));

        $this->sendEmail('Reset password', $user->getEmail(), $body);
    }

    /**
     * @param $subject
     * @param $recipient_email
     * @param $body
     * @return int
     */
    private function sendEmail($subject, $recipient_email, $body)
    {
        /* @var $message \Swift_Message */
        $message = $this->mailer->createMessage();

        $message
            ->setSubject($subject)
            ->setFrom($this->email_from)
            ->setTo($recipient_email)
            ->setBody($body, 'text/html');

        if (!$this->mailer->getTransport()->isStarted()) {
            $this->mailer->getTransport()->start();
        }

        $email = $this->mailer->send($message);
        $this->mailer->getTransport()->stop();

        return $email;
    }

    /**
     * @param User $user
     * @param $packages
     * @throws \Twig_Error
     */
    public function sendWelcome(User $user, $packages)
    {
        $body = $this->templating->render('@Rest/Email/welcome.html.twig', array(
            'packages' => $packages,
            'user'     => $user
        ));

        $this->sendEmail('Order Details', $user->getEmail(), $body);
    }

    /**
     * @param User $user
     * @param $packages
     * @throws \Twig_Error
     */
    public function sendOrderMessage(User $user, $packages)
    {
        $body = $this->templating->render('@Rest/Email/order_template.twig', array(
            'packages' => $packages,
            'user'     => $user
        ));

        $this->sendEmail('Getting Started', $user->getEmail(), $body);
    }

    /**
     * @param User $user
     * @param $packages
     * @throws \Twig_Error
     */
    public function sendAdminNewOrder(User $user, $packages)
    {
        $body = $this->templating->render('@Rest/Email/new_order.html.twig', array(
            'packages' => $packages,
            'user'     => $user
        ));

        $this->sendEmail('New Order', $this->admin_email, $body);
    }

    /**
     * @param User $user
     * @throws \Twig_Error
     */
    public function sendAdminLeaveReview(User $user)
    {
        $body = $this->templating->render('@Rest/Email/leave_review.html.twig', array(
            'user' => $user
        ));

        $this->sendEmail('Leave Review', $user->getEmail(), $body);
    }

    /**
     * @param User $author
     * @param Message $message
     * @param User $recipient
     * @return int
     * @throws \Twig_Error
     */
    public function sendAdminChatMessage(User $author, Message $message, User $recipient)
    {
        $body = $this->templating->render('@Rest/Email/message_notification.html.twig', array(
            'author'  => $author,
            'message' => $message,
            'recipient' => $recipient
        ));


        echo "message from :".$author->getEmail()."\r\n";
        echo "recipient:".$recipient->getEmail();

        return $this->sendEmail('New chat message from '. $author->getEmail(), $recipient->getEmail(), $body);
    }

    /**
     * sendAdminAccess
     *
     * @param User $user
     * @param string $plainPassword
     * @throws \Twig_Error
     */
    public function sendAdminAccess(User $user, $plainPassword)
    {
        $body = $this->templating->render('@Rest/Email/admin_access.html.twig', array(
            'email' => $user->getEmail(),
            'password' => $plainPassword,
            'fullName' => $user->getFullName()
        ));

        $this->sendEmail('Welcome to administrators', $user->getEmail(), $body);
    }
}