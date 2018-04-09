<?php

namespace RestBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\FOSRestController as Controller;
use RestBundle\Entity\CareerPreferences;
use RestBundle\Entity\Discount;
use RestBundle\Entity\Message;
use RestBundle\Entity\Profile;
use RestBundle\Entity\Questions;
use RestBundle\Entity\Service;
use RestBundle\Entity\Settings;
use RestBundle\Entity\User;
use RestBundle\Entity\UserPackages;
use RestBundle\Exception\ApiException;
use RestBundle\Exception\PasswordException;
use RestBundle\Form\SignUpType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;

class CheckoutController extends Controller
{
    /**
     * @Route("/congratulation", name="congratulation")
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function congratulationAction()
    {
        $em = $this->getDoctrine()->getManager();
        $services = $em->getRepository('RestBundle:Service')->findAll();

        return $this->handleView($this->view($services, 200));
    }

    /**
     * @Route("/checkout", name="checkout")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = null;

        if ($request->headers->has('token')) {
            $user = $em->getRepository('RestBundle:User')->findOneBy(array(
                'token' => $request->headers->get('token'),
                'is_active' => true
            ));
        }

        if (!$user instanceof UserInterface) {
            $credentials = $request->get('payer_credentials');

            if (isset($credentials['confirm_password'])) {
                $admin = $em->getRepository('RestBundle:User')->getSuperAdmin();
                $user = $this->signUp($credentials, $admin);

                $this->get('user.mailer')->sendWelcome($user, $request->request->all());
                $payer_basket_check = $request->get('payer_basket');
                if( $payer_basket_check[0]['title'] != "Career Finder" )
                    $this->get('user.mailer')->sendOrderMessage($user, $request->request->all());
            } else {
                $user = $this->signIn($credentials, $em);
            }
        }

        if ($user instanceof UserInterface) {
            try {
                $payment_info = $request->get('payment_info');
                Stripe::setApiKey('sk_live_P5ypN4tsqElBd2Hk2YLjmNDn');


                $customer = Customer::create(array(
                      'email' => $request->get('payer_credentials')['email'],
                      'source'  => $payment_info['source']
                ));


                $charge = Charge::create(array(
 39aa39df5d30e693413a08a6ed56d0d4a256d02b
                    'amount' => $payment_info['amount'] * 100,
                    'currency' => $payment_info['currency'],
                    'customer' => $customer->id,
                    'description' => 'Find My Profession Order'
                ));

                if (null !== $charge->failure_code) {
                    return $this->handleView($this->view(array('status' => 'Fail', 'message' => $charge->failure_message), 400));
                }

                /** @var array $packages */
                $packages = $request->get('payer_basket');

                foreach ($packages as $package) {
                    $service = $em->getRepository('RestBundle:Service')->find($package['id']);

                    if ($service instanceof Service) {
                        $user_package = new UserPackages();
                        $user_package->setService($service);
                        $user_package->setUser($user);
                        $user_package->setPrice($package['price']);
                        $user_package->setPlan($package['plan']);
                        $user_package->setDiscount($payment_info['amount']);

                        $em->persist($user_package);
                        $em->flush();
                        $user->addPackages($user_package);
                    }

                    //if ($service->getName() === 'Career Finder') {
                        $this->get('mailchimp.notification')->subscribe($user->getEmail(), '8347d12319');
                    //}
                }
                $this->get('user.mailer')->sendAdminNewOrder($user, $request->request->all());

                $user->setIsActive(true);
                $em->persist($user);
                $em->flush();

                $messages = $em->getRepository('RestBundle:Message')->getUserMessages($user);

                if ($user->getDateCreated()->format('Y-m-d') === date('Y-m-d')) {
                    foreach ($messages as $message) {
                        $em->remove($message);
                    }

                    $em->flush();
                }

                if (count($messages) === 0) {
                    $congratulation_message = new Message();
                    $congratulation_message->addUnreadUser($user);
                    $congratulation_message->setOwner($user->getId());
                    if( $user->getAdmins()->count() > 0 )
                        $congratulation_message->setAuthor($user->getAdmins()->first()->getId());
                    /** @var Settings $settings */
                    $settings = $em->getRepository('RestBundle:Settings')->find(1);

                    if ($settings) {
                        $message_text = $settings->getCongratulationMessage();
                        $congratulation_message->setMessage($message_text);
                        $congratulation_message->setTypeSender(1);
                        $em->persist($congratulation_message);
                        $em->flush();
                    }
                }
            } catch (\Exception $e) {
                throw new ApiException($e->getMessage());
            }

            return $this->handleView($this->view(array('status' => 'Ok', 'message' => 'Success', 'user' => $user), 200));
        }

        return $this->handleView($this->view(array('status' => 'Fail', 'message' => 'Error message'), 400));
    }

    /**
     * @Route("/coupon-check", name="check")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function check(Request $request)
    {
        if (!$request->request->has('code')) {
            return $this->handleView($this->view(array('status' => 'Error', 'body' => 'Empty code'), 400));
        }

        $em = $this->getDoctrine()->getManager();
        $discount = $em->getRepository('RestBundle:Discount')
            ->getDiscount($request->get('code'));

        if (!$discount instanceof Discount) {
            return $this->handleView($this->view(array('status' => 'Fail', 'body' => 'Coupon not found'), 400));
        }

        $count = $discount->getCount();
        $discount->setCount(--$count);

        $em->persist($discount);
        $em->flush();

        return $this->handleView($this->view($discount)->setContext((new Context())->setGroups(array(
            'Default',
            'services' => array('other_services')
        ))));
    }

    /**
     * @param $credentials
     * @param ObjectManager $em
     * @return null|User
     * @throws ApiException
     */
    private function signIn($credentials, ObjectManager $em)
    {
        $user = $em->getRepository('RestBundle:User')->findOneBy(array('email' => $credentials['email'], 'isRemoved' => false));

        if (!$user instanceof UserInterface) {
            throw new ApiException("This account doesn't exist");
        }

        $isValid = $this->get('security.password_encoder')->isPasswordValid($user, $credentials['password']);

        if (!$isValid) {
            throw new ApiException('Incorrect email or password');
        }

        if ($timezone = $this->get('request')->get('timezone')) {
            $user->setTimezone($timezone);
        }

        return $user;
    }

    /**
     * @param $credentials
     * @param User $admin
     * @return User|\Symfony\Component\HttpFoundation\Response
     * @throws PasswordException | ApiException
     */
    private function signUp($credentials, User $admin)
    {
        if (null === $credentials['password'] || null === $credentials['confirm_password']) {
            throw new PasswordException('Password or confirm password is empty');
        }

        if ($credentials['password'] !== $credentials['confirm_password']) {
            throw new PasswordException('Password does not match');
        }

        $user = new User();
        $user->setUsername($credentials['email']);
        $user->setRole('ROLE_USER');
        $profile = new Profile($user);
        $profile->setCareerPreferences(new CareerPreferences());

        $questions = new Questions();
        $profile->setQuestions($questions);
        $user->setProfile($profile);
        $user->addAdmin($admin);
        $form = $this->createForm(SignUpType::class, $user);
        $form->submit($credentials, false);

        if ($form->isValid()) {
            $user->setIsActive(false);
        } else {
            $errors = $this->get('app.form_errors')->getStringErrors($form);

            throw new ApiException($errors);
        }

        if ($timezone = $this->get('request')->get('timezone')) {
            $user->setTimezone($timezone);
        }

        return $user;
    }
}

