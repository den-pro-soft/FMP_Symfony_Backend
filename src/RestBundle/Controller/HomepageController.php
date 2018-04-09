<?php

namespace RestBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\FOSRestController as Controller;
use RestBundle\Entity\Blog;
use RestBundle\Entity\Document;
use RestBundle\Entity\Message;
use RestBundle\Entity\Profile;
use RestBundle\Entity\Schedule;
use RestBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
/**
 * Class HomepageController
 * @package RestBundle\Controller
 */
class HomepageController extends Controller
{
    /**
     * @Route("/homepage/message/send", name="user_message_send")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sendMessageAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
 
        if( $user->getAdmins()->count() > 0 )
        {
            $recipients = $user->getAdmins();

            $message = new Message();
            $message->setAuthor( $user->getId() );
            $message->setOwner(  $user->getId() );
            $message->setUnreadUsers( $recipients );
            $message->setTypeSender(2);
            $message->setEdited(0);

            if (!empty($request->get('message'))) {
                $message->setMessage($request->get('message'));
            } else if ($request->files->has('attachment')) {
                /** @var UploadedFile $uploaded_file */
                $uploaded_file = $request->files->get('attachment');
                /** @var File $file */
                $file = $this->get('app.file_uploader')->upload($uploaded_file);

                $message->setAttachment($file);
                $message->setAttachmentName($uploaded_file->getClientOriginalName());
                $message->setAttachmentPath($file->getFilename());

                $errors = $this->get('validator')->validate($message);

                if (count($errors) > 0) {
                    $this->get('app.file_uploader')->remove($file);

                    return $this->handleView($this->view(['status' => $errors[0]->getMessage()], 400));
                }
            } else {
                return $this->handleView($this->view(['status' => 'Fail1'], 400));
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();
            
            $sender   = $user ; 
 
            $message->setSenderName( $sender->getFullName() ); 

            return $this->handleView($this->view($message)->setContext((new Context())->setGroups(['api'])));
        } 
        

        return $this->handleView($this->view(['status' => 'Fail'], 400));
    }

    /**
     * @Route("/homepage/message/delete", name="user_message_delete")
     * @Method("POST")
     * @param Request $request
     * @return JsonResponse|Response
     */

    public function deleteMessageAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $message = $em->getRepository('RestBundle:Message')->find( $request->get( 'message_id' ) );
        if( $message )
        {
            $em->remove($message);
            $em->flush();
            return new JsonResponse(['status' => 'Success'], JsonResponse::HTTP_OK);
        }

        return new JsonResponse(['status' => 'Fail'], JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/homepage/message/update", name="user_message_update")
     * @Method("POST")
     * @param Request $request
     * @return JsonResponse|Response
     */

    public function updateMessageAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $message = $em->getRepository('RestBundle:Message')->find( $request->get( 'message_id' ) );
        if( $message )
        {
            $message->setMessage( $request->get('message') );
            $message->setEdited( 1 );
            $em->persist($message);
            $em->flush();
            return new JsonResponse(['status' => 'Success'], JsonResponse::HTTP_OK);
        }

        return new JsonResponse(['status' => 'Fail'], JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/homepage/message/get", name="user_message_get")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getMessageAction()
    {
        $em = $this->getDoctrine()->getManager();
  
        $messages = $em->getRepository('RestBundle:Message')
        ->createQueryBuilder('m')
        ->select('m')
        ->join('m.unread_users', 'u')
        ->where('u.id = :user_id')
        ->andWhere('m.type_sender = 1')
        ->setParameters([
            'user_id' => $this->getUser()->getId(), 
            ])
        ->getQuery()
        ->getResult(); 

        $data = [];

        foreach ($messages as $message) {
            /** @var Message $message */
            $sender   = $em->getRepository('RestBundle:User')->find($message->getAuthor()); 
 
            
            $message->removeUnreadUser($this->getUser());
            $em->persist($message);

            if( $sender )
            { 
                $message->setSenderName(    $sender->getFullName() );
            }

         

            $data[] = $message; 
        }

        $em->flush();

        return $this->handleView($this->view($data)->setContext((new Context())->setGroups(['api'])));
    }

    /**
     * @Route("/homepage/schedules", name="user_schedules")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function schedulesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if ($request->isMethod('POST') && $request->request->has('id')) {
            $schedule = $em->getRepository('RestBundle:Schedule')->find($request->get('id'));
            $schedule->setStatus($request->get('status'));

            $em->persist($schedule);
            $em->flush();

            return $this->handleView($this->view(['status' => 'Ok']));
        }

        $user = $this->getUser();

        /** @var Schedule[] $schedules */
        $schedules = $em->getRepository('RestBundle:Schedule')->findBy(['invitee_email' => $user->getEmail()]);

        $data['available'] = [];
        $data['pending'] = [];
        $data['completed'] = [];

        $packages = $em->getRepository('RestBundle:UserPackages')->findBy(['user' => $user->getId(), 'uuid' => null]);

        foreach ($packages as $package) {
            $data['available'][] = $package->getService();
        }

        foreach ($schedules as $schedule) {
            switch ($schedule->getStatus()) {
                case 'Pending':
                    $data['pending'][] = $schedule;
                    break;

                case 'Canceled':
                case 'Completed':
                    $data['completed'][] = $schedule;
                    break;
            }
        }

        return $this->handleView($this->view($data));
    }

    /**
     * @Route("/homepage/{category}", requirements={"category": "career-advice|linkedin|resume|interviewing|job-search"}, name="user_homepage_slider")
     * @Method("GET")
     * @param $category
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getSliderAction($category)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $this->getSliderWithLikes($em, $category);

        return $this->handleView($this->view($data)->setContext((new Context())->setGroups(['top'])));
    }

    /**
     * @Route("/homepage", name="user_homepage")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $data = [
            'updates' => $this->getUpdates($em),
            'chat' => $this->getChat($em),
            'slider' => $this->getSliderWithLikes($em)
        ];

        return $this->handleView($this->view($data)->setContext((new Context())->setGroups([
            'chat' => 'api',
            'slider' => 'top'
        ])));
    }

    /**
     * @param ObjectManager $em
     * @param string $category
     * @return array
     */
    private function getSliderWithLikes(ObjectManager $em, $category = 'career-advice')
    {
        $category = $em->getRepository('RestBundle:Category')->findOneBy(['category' => $category]);
        $tops = $em->getRepository('RestBundle:Blog')->getTops($category);

        $likes = $this->getUser()->getLikes();

        if (count($likes)) {
            /** @var array $tops */
            foreach ($tops as $career_advice) {
                /** @var Blog $career_advice */
                if (in_array($career_advice, $likes, true)) {
                    $career_advice->setLiked(true);
                }
            }
        }

        return $tops;
    }

    /**
     * @param ObjectManager $em
     * @return array
     */
    private function getChat(ObjectManager $em)
    {
        $messages = $em->getRepository('RestBundle:Message')->getUserMessages($this->getUser());
        $data = [];

        if (count($messages) !== 0) {
            foreach ($messages as $message) {
                /** @var Message $message */ 

                $message->removeUnreadUser($this->getUser());

                $sender = $em->getRepository('RestBundle:User')->find($message->getAuthor());
                 
                if( $sender ) 
                    $message->setSenderName(    $sender->getFullName() ); 
              

                $data[] = $message;
            }
            $em->flush();
        }

        if (empty($data)) {
            $congratulation_message = new Message();
            $congratulation_message->setRecipient($this->getUser()->getId());
            $congratulation_message->setOwner($this->getUser()->getId());
            $settings = $em->getRepository('RestBundle:Settings')->find(1);

            if ($settings) {
                $message_text = $settings->getCongratulationMessage();
                $congratulation_message->setTypeSender(1);
                $congratulation_message->setMessage($message_text);
                $data[] = $congratulation_message;
            }
        }

        return $data;
    }

    /**
     * @param ObjectManager $em
     * @return mixed
     */
    private function getUpdates(ObjectManager $em)
    {
        $updates['profile_complete'] = false;
        $updates['resume_uploaded'] = false;
        $updates['schedule_call'] = false;

        /** @var Profile $profile */
        $profile = $this->getUser()->getProfile();

        if ($profile->getProgress()['value'] === 100) {
            $updates['profile_complete'] = true;
        }

        if ($profile->getDocuments()->count() !== 0) {
            /** @var Document[] $documents */
            $documents = $profile->getDocuments();
            foreach ($documents as $document) {
                if ($document->getType() === 'Resume') {
                    $updates['resume_uploaded'] = true;
                }
            }
        }

        $packages = $em->getRepository('RestBundle:UserPackages')->findBy([
            'user' => $this->getUser()->getId(),
            'uuid' => null
        ]);

        if (count($packages) !== 0) {
            $updates['schedule_call'] = true;
        }

        $updates['jobs'] = $em->getRepository('RestBundle:Job')->getJobsCounts($this->getUser());

        return $updates;
    }
}
