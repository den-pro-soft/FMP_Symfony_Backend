<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\DocumentType;
use Doctrine\Common\Collections\ArrayCollection;
use AdminBundle\Form\JobType;
use AdminBundle\Form\UserType;
use JMS\Serializer\SerializationContext;
use RestBundle\Entity\Attempt;
use RestBundle\Entity\Contact;
use RestBundle\Entity\Discount;
use RestBundle\Entity\Document;
use RestBundle\Entity\Job;
use RestBundle\Entity\Message;
use RestBundle\Entity\Task;
use RestBundle\Entity\User;
use RestBundle\Entity\UserPackages;
use RestBundle\Entity\Schedule;
use RestBundle\Exception\ApiException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;

class UsersController extends Controller
{
    /**
     * @Route("/admin/users/{page}", name="list_users", requirements={"page": "\d+"}, defaults={"page": 1})
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($page)
    {
        $userRepo   = $this->getDoctrine()->getRepository('RestBundle:User');
        $result     = $userRepo->getUsersByAdmin($this->getUser());
        $admins     = $userRepo->getAllAdminsByRole('ROLE_ADMIN');
        $sdrs       = $userRepo->getAllAdminsByRole('ROLE_SDR');

        return $this->render('@Admin/Admin/users.html.twig', array(
            'users'         => $result['users'],
            'filter_val'    => "",
            'admins'        => $admins,
            'sdrs'          => $sdrs
        ));
    }

    /**
     * @Route("/admin/users/{filter}", name="list_users_filter", requirements={"page": "\d+"}, defaults={"page": 1})
     * @param $page
     * @param $filter
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function selectAction( $filter , $page )
    {
        $em         = $this->getDoctrine()->getManager();
        $userRepo   = $em->getRepository('RestBundle:User');
        $this_admin = $this->getUser();
        $users      = $userRepo->getUsersByAdmin($this_admin);
        $user_list  = array();
        $admins     = array();
        $sdrs       = array();

        if($this_admin->isSuperAdmin()){
            $admins = $userRepo->getAllAdminsByRole('ROLE_ADMIN');
            $sdrs   = $userRepo->getAllAdminsByRole('ROLE_SDR');
        } elseif ($this_admin->isAdminManager()){
            foreach($this_admin->getUsers() as $admin_item){
                if($admin_item->isAdmin()){
                    $admins[] = $admin_item;
                } elseif ($admin_item->isSDR()){
                    $sdrs[] = $admin_item;
                }
            }
        }

        if( $filter =="complete")   $filter_value = TRUE;
        if( $filter =="active")     $filter_value = FALSE;
        
        foreach ( $users as $user ) {
            $package_data = $user->getPackages();
            foreach ( $package_data as $package) {
                if( $package->getIsApproved() === $filter_value  ){
                    $user_list[] = $user;
                    break;
                }
            }
        }

        return $this->render('@Admin/Admin/users.html.twig', array(
            'users'         => $user_list,
            'filter_val'    => $filter,
            'admins'        => $admins,
            'sdrs'          => $sdrs
        ));
    }

    /**
     * @Route("/admin/user/{user}", name="view_user")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(User $user)
    {
        $form       = $this->createForm(UserType::class, $user);
        $file_form  = $this->createForm(DocumentType::class);
        $em         = $this->getDoctrine()->getManager();
        $messages   = $em->getRepository('RestBundle:Message')->getUserMessages($user);
         
        $schedule   = $em->getRepository('RestBundle:Schedule')->findBy(array( 'invitee_email' => $user->getEmail() ));
        if($schedule)
            $schedule_list = $schedule;
        else 
            $schedule_list = "";
        
        $data = array();
        
        foreach ($messages as $message) {
            /** @var Message $message */
            $message->removeUnreadUser($this->getUser());
            $sender = $em->getRepository('RestBundle:User')->find($message->getAuthor());
            if( $sender ) 
                    $message->setSenderName( $sender->getFullName() ); 
            $em->persist($message);

            $data[] = $message;
        }

        /** @var UserPackages[] $user_packages */
        $user_packages = $user->getPackages();

        foreach ($user_packages as $user_package) {
            if (null !== $user_package->getDiscount()) {
                $discount = $em-> getRepository('RestBundle:Discount')->findOneBy(array('code' => $user_package->getDiscount()));
                if (!$discount instanceof Discount) {
                    continue;
                }
            }
        }

        $em->flush();
        return $this->render('@Admin/Admin/user_editor.html.twig', array(
            'form'      => $form->createView(),
            'file_form' => $file_form->createView(),
            'user'      => $user,
            'schedules' => $schedule_list,
            'messages'  => $data
        ));
    }

    /**
     * @Route("/admin/user/{user}/jobs", name="user_jobs")
     * @param User $user
     * @return Response
     */
    public function userJobAction(User $user)
    {
        $form           = $this->createForm(JobType::class);
        /** @var Job[] $jobs */
        $em             = $this->getDoctrine()->getManager();
        $jobs           = $em->getRepository('RestBundle:Job')->getUserJobs($user);
        $jobs_liked     = array();
        $jobs_applied   = array();

        foreach ($jobs as $job) {
            if ($job->getSection() === 'liked') {
                $jobs_liked[] = $job;
            } else {
                $jobs_applied[] = $job;
            }
            $job->setIsNewAdmin(false);
            $em->persist($job);
        }

        $em->flush();

        return $this->render('@Admin/Admin/view_jobs.html.twig', array(
            'user'          => $user,
            'form'          => $form->createView(),
            'jobs_liked'    => $jobs_liked,
            'jobs_applied'  => $jobs_applied
        ));
    }

    /**
     * @Route("/admin/user/jobs/edit", name="job_data_update")
     * @Method("POST")
     *
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function JobEditAction(Request $request)
    {
        $job_id         = $request->get('job_id');
        $job_url        = $request->get('job_url');
        $job_company    = $request->get('job_company');
        $job_title      = $request->get('job_title');

        $em             = $this->getDoctrine()->getManager();
        $job            = $em->getRepository('RestBundle:Job')->find($job_id);
        
        $job->setLink( $job_url );
        $job->setCompany( $job_company );
        $job->setPosition( $job_title );
        $user = $job->getUser();
        
        $em->persist($job);
        $em->flush();
        
        return new JsonResponse(array('status' => 'Ok'));
    }

    /**
     * @Route("/admin/cover/{job}", name="user_get_cover_letter")
     * @Method("GET")
     * @param Job $job
     * @return Response
     */
    public function downloadCoverLetterAction(Job $job)
    {
        $downloadHandler    = $this->get('vich_uploader.download_handler');
        $fileName           = $job->getAttachmentAlias();
        return $downloadHandler->downloadObject($job, $fileField = 'attachment', $objectClass = null, $fileName);
    }

    /**
     * @Route("/admin/cover/{job}", name="user_add_cover_letter")
     * @Method("POST")
     * @param Request $request
     * @param Job $job
     * @return Response
     * @throws ApiException
     */
    public function addCoverLetterAction(Request $request, Job $job)
    {
        if ($request->files->has('file')) {
            /** @var UploadedFile $uploaded_file */
            $uploaded_file = $request->files->get('file');
            /** @var File $file */
            $file = $this->get('app.file_uploader')->upload($uploaded_file);

            $job->setAttachment($file);
            $job->setAttachmentAlias($uploaded_file->getClientOriginalName());
            $job->setAttachmentName($file->getFilename());

            $em = $this->getDoctrine()->getManager();
            $em->persist($job);

            $errors = $this->get('validator')->validate($job);

            if (count($errors) > 0) {
                $this->get('app.file_uploader')->remove($file);
                throw new ApiException($errors[0]->getMessage());
            }

            $em->flush();
        } else {
            throw new ApiException('No file');
        }

        return new JsonResponse(array(
            'id'            => $job->getId(),
            'file_url'      => $this->generateUrl('user_get_cover_letter', array('job' => $job->getId())),
            'remove_url'    => $this->generateUrl('user_remove_cover_letter', array('job' => $job->getId())),
            'add_url'       => $this->generateUrl('user_add_cover_letter', array('job' => $job->getId())),
            'filename'      => $job->getAttachmentAlias()
        ));
    }

    /**
     * @Route("/admin/cover/{job}/remove", name="user_remove_cover_letter")
     * @Method("GET")
     * @param Job $job
     * @return Response
     */
    public function removeCoverLetterAction(Job $job)
    {
        $job->setAttachment(null);
        $job->setAttachmentAlias(null);
        $job->setAttachmentName(null);

        $em = $this->getDoctrine()->getManager();
        $em->persist($job);

        $em->flush();

        return new JsonResponse(array('Ok'));
    }

    /**
     * @Route("/admin/user/{user}/job/add", name="add_user_job")
     * @Method("POST")
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addJobAction(Request $request, User $user)
    {
        $job    = new Job();
        $form   = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isValid()) {
            if (strpos($form->getData()->getLink(), 'http') === false) {
                $job->setLink('http://' . $form->getData()->getLink());
            }

            $job->setUser($user);
            $job->setAddedBy('admin');
            $job->setRate('N/A');
            $em = $this->getDoctrine()->getManager();
            $em->persist($job);
            $em->flush();

            $task = new Task();
            $task->setUser($user);
            $task->setJobId($job->getId());
            $task->setName("Add Contacts");
            $em->persist($task);
            $em->flush();

            $this->addFlash(
                'success',
                'Job was added successfully'
            );
        } else {
            $errors = $this->get('validator')->validate($job);
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }

        return $this->redirectToRoute('user_jobs', array('user' => $user->getId()));
    }

    /**
     * @Route("/admin/job/rate", name="change_job_rate")
     * @Method("POST")
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function changeRateJobAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em     = $this->getDoctrine()->getManager();
            /** @var Job $job */
            $job    = $em->getRepository('RestBundle:Job')->find($request->get('job'));

            $rate   = $request->get('status');
            $job->setRate($rate);
            $em->persist($job);
            $em->flush();

            if($rate > 9){
                $task = $em->getRepository('RestBundle:Task')->getOneToOneTask($job);
                if(! $task || $task->getCompleted() == 'yes'){
                    $new_task = new Task();
                    $new_task->setUser($job->getUser());
                    $new_task->setJobId($job->getId());
                    $new_task->setName('Client 1-on-1');
                    $new_task->setDueDate($this->getDueDate(1));
                    $em->persist($new_task);
                    $em->flush();
                }
            }

            return new JsonResponse(array('status' => 'Ok'));
        }

        return new JsonResponse(array('status' => 'Fail'));
    }

    /**
     * @Route("/admin/job/status", name="change_job_status")
     * @Method("POST")
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function changeStatusJobAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em         = $this->getDoctrine()->getManager();
            /** @var Job $job */
            $user_id    = $request->get('user');
            $job        = $em->getRepository('RestBundle:Job')->find($request->get('job'));
            $status     = $request->get('status');

            if ($status === 'Applied') {
                $job->setSection('applied');
                $job->setAppliedDate(new \DateTime());
            } elseif ($status === 'Ready' || $status === 'Pending') {
                $job->setSection('liked');
            }

            $job->setStatus($status, true);
            $em->persist($job);
            $em->flush();

            $user = $job->getUser();

            $jobs_list      = $em->getRepository('RestBundle:Job')->getUserJobs($user);
            $jobs_liked     = array();
            $jobs_applied   = array();

            foreach ($jobs_list as $jobitems) {
                if ($jobitems->getSection() === 'liked') {
                    $jobs_liked[] = $jobitems;
                } else {
                    $jobs_applied[] = $jobitems;
                }
                $jobitems->setIsNewAdmin(false);
                $em->persist($jobitems);
            }
            $em->flush();

            return new JsonResponse(array('status' => 'Ok', 'jobs_liked' => $jobs_liked, 'jobs_applied' => $jobs_applied));
        }

        return new JsonResponse(array('status' => 'Fail'));
    }

    /**
     * @Route("/admin/user/{user}/documents/add", name="admin_user_documents_add")
     * @Method("POST")
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addDocumentAction(Request $request, User $user)
    {
        $documents  = new Document();
        $form       = $this->createForm(DocumentType::class, $documents);
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var User $admin */
            $admin = $this->getUser();
            $documents->setName($documents->getDocument()->getClientOriginalName());
            $documents->setAddedBy($admin->getFullName());
            $profile = $user->getProfile();
            $documents->setProfile($profile);
            $profile->addDocument($documents);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'success',
                'Document was upload successfully'
            );
        } else {
            $errors = $this->get('validator')->validate($documents);

            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }

        return $this->redirectToRoute('view_user', array('user' => $user->getId()));
    }

    /**
     * @Route("/admin/document/download/{document}", name="admin_user_documents_download")
     * @Method("GET")
     * @param Document $document
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function downloadFileAction(Document $document)
    {
        $downloadHandler    = $this->get('vich_uploader.download_handler');
        $fileName           = $document->getName();
        return $downloadHandler->downloadObject($document, $fileField = 'document', null, $fileName);
    }

    /**
     * @Route("/admin/document/remove/{document}", name="admin_user_documents_remove")
     * @Method("GET")
     * @param Document $document
     * @return Response
     */
    public function removeFileAction(Document $document)
    {
        $em     = $this->getDoctrine()->getManager();
        $user   = $document->getProfile()->getUser();
        $em->remove($document);
        $em->flush();

        $this->addFlash(
            'success',
            'Document was delete successfully'
        );

        return $this->redirectToRoute('view_user', array('user' => $user->getId()));
    }

    /**
     * @Route("/admin/assign/user", name="admin_user_assign_to_admin")
     * @Method("POST")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function assignAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em         = $this->getDoctrine()->getManager();
            $userRepo   = $em->getRepository('RestBundle:User');
            $user       = $userRepo->find($request->get('user'));
            $admin_id   = $request->get('admin');
            $role       = $request->get('role');
            $is_exist   = false;
            // remove unallocated admins
            foreach( $user->getAdmins() as $admin )
            {
                if($admin->getRole() != $role) continue;

                if($admin->getId() == $admin_id){
                    $is_exist = true;
                } else {
                    $user->removeAdmin( $admin );
                    $admin->removeUser( $user );
                    $em->persist( $user );
                    $em->persist( $admin );
                    $em->flush();
                }
            }

            if(! $is_exist && $admin_id > 0){
                $admin = $userRepo->find($admin_id);
                $user->addAdmin( $admin );
                $admin->addUser( $user );
                $em->persist( $user );
                $em->persist( $admin );
                $em->flush();
            }
            
            return new JsonResponse(array('status' => 'Ok'));
        }

        return new JsonResponse(array('status' => 'Fail'), JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/admin/assign/title_change", name="admin_title_change")
     * @Method("POST")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function titleAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em         = $this->getDoctrine()->getManager();
            $title_val  = $request->get('title');
            $admin      = $em->getRepository('RestBundle:User')->find($request->get('admin'));
            $admin->setTitle($title_val);
            $em->persist($admin);
            $em->flush();
            return new JsonResponse(array('status' => 'Ok'));
        }

        return new JsonResponse(array('status' => 'Fail'), JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/admin/message/send/{user}", name="admin_message_send")
     * @param Request $request
     * @param User $user
     * @return Response
     * @throws ApiException
     */
    public function sendMessageAction(Request $request, User $user)
    {
        if ($request->isXmlHttpRequest()) {
            $link       = $delete_link = null;
            $message    = new Message();
            /** @var User $admin */
            $unreadusers = $user->getAdmins();
            $unreadusers->removeElement( $this->getUser() ); 
            $unreadusers->add($user);

            $message->setAuthor( $this->getUser()->getId() );
            $message->setOwner( $user->getId() );
            $message->setUnreadUsers( $unreadusers );
            $message->setTypeSender( 1 );
            $message->setEdited( 0 );
            $em = $this->getDoctrine()->getManager();

            $post_message = trim($request->get('message'));

            if (!empty($post_message)) {
                $message->setMessage($post_message);
                $em->persist($message);
                $em->flush();
            } else if ($request->files->has('attachment')) {
                /** @var UploadedFile $uploaded_file */
                $uploaded_file = $request->files->get('attachment');

                /** @var File $file */
                $file = $this->get('app.file_uploader')->upload($uploaded_file);

                $message->setAttachment($file);
                $message->setAttachmentName($uploaded_file->getClientOriginalName());
                $message->setAttachmentPath($file->getFilename());
                $em->persist($message);

                $errors = $this->get('validator')->validate($message);

                if (count($errors) > 0) {
                    $this->get('app.file_uploader')->remove($file);
                    throw new ApiException($errors[0]->getMessage());
                }

                $em->flush();

                $link = $this->generateUrl('admin_chat_attachment_download', array('message' => $message->getId()));
                $delete_link = $this->generateUrl('admin_message_delete', array('message' => $message->getId()));
            }

            return new JsonResponse(array(
                'message'           => $message->getMessage(),
                'sendername'        => $this->getUser()->getFullName(),
                'message_id'        => $message->getId(),
                'download_link'     => $link,
                'attachment_name'   => $message->getAttachmentName(),
                'delete_link'       => $delete_link
            ));
        }

        return new JsonResponse(array('status' => 'Fail', 'message'), JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/admin/message/get/{user}", name="admin_message_get")
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function getMessageAction(Request $request, User $user)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
 
            $messages = $em->getRepository('RestBundle:Message')
                ->createQueryBuilder('m')
                ->select('m')
                ->join('m.unread_users', 'ad')
                ->where('ad.id = :admin_id')
                ->andWhere('m.owner = :user_id')
                ->setParameters(array(
                    'user_id'  => $user->getId(), 
                    'admin_id' => $this->getUser()->getId(),
                ))
                ->getQuery()
                ->getResult(); 

            $data = array();

            foreach ($messages as $message) {
                /** @var Message $message */
                $message->removeUnreadUser( $this->getUser() );
                $em->persist($message);

                if ($message->getAttachmentName()) {
                    $link = $this->generateUrl('admin_chat_attachment_download', array('message' => $message->getId()));
                    $delete_link = $this->generateUrl('admin_message_delete', array('message' => $message->getId()));

                    $message = array(
                        'message_id'        => $message->getId(),
                        'sendername'        =>  $em->getRepository('RestBundle:User')->find( $message->getAuthor() )->getFullName() ,
                        'download_link'     => $link,
                        'attachment_name'   => $message->getAttachmentName(),
                        'delete_link'       => $delete_link
                    );
                }
                else
                {
                    $message->setSendername(  $em->getRepository('RestBundle:User')->find( $message->getAuthor() )->getFullName() );
                }

                $data[] = $message;
            }

            $em->flush();

            if (empty($data)) {
                return new JsonResponse(array());
            }

            return new JsonResponse($this->get('serializer')->serialize($data, 'json', SerializationContext::create()->setGroups(array('chat'))));
        }

        return new JsonResponse(array('status' => 'Fail'), JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/admin/chat/download/{message}", name="admin_chat_attachment_download")
     * @Method("GET")
     * @param Message $message
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function downloadAttachmentAction(Message $message)
    {
        $downloadHandler    = $this->get('vich_uploader.download_handler');
        $fileName           = $message->getAttachmentName();
        return $downloadHandler->downloadObject($message, $fileField = 'attachment', null, $fileName);
    }

    /**
     * @Route("/admin/order/{order}/{status}", requirements={"status": "completed|cancel"}, name="admin_order_status")
     * @Method("GET")
     * @param UserPackages $order
     * @param $status
     * @return Response
     */
    public function changeOrderStatusAction(UserPackages $order, $status)
    {
        $em     = $this->getDoctrine()->getManager();
        $user   = $order->getUser();

        if ($status === 'completed') {
            $order->setIsApproved(true);
            $em->persist($order);
            $em->flush();
            
            $this->get('user.mailer')->sendAdminLeaveReview($user);

            $this->addFlash(
                'success',
                'Order was approved successfully'
            );
        } else {
            $uuid = $order->getUuid();
            if ($uuid) {
                $schedule = $em->getRepository('RestBundle:Schedule')->findOneBy(array('uuid' => $uuid));
                $em->remove($schedule);
            }

            $em->remove($order);

            $this->addFlash(
                'success',
                'Order was delete successfully'
            );
        }

        $em->flush();

        return $this->redirectToRoute('view_user', array('user' => $user->getId()));
    }

    /**
     * @Route("/admin/users/delete/{user}", name="delete_user")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function deleteAction(User $user)
    {
        if (!$this->getUser()->isSuperAdmin()) {
            throw new \Exception('Access denied');
        }

        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository('RestBundle:Message')->getUserMessages($user);

        foreach ($messages as $message) {
            $em->remove($message);
        }

        $user->setProfile(null);
        $em->remove($user);

        $em->flush();

        $this->addFlash(
            'success',
            'User was deleted successfully.'
        );

        return $this->redirectToRoute('list_users');
    }

    /**
     * @Route("/admin/update/password", name="update_user_password")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function updateUserPassword(Request $request)
    {

        if (!$this->getUser()->isSuperAdmin()) {
            throw new \Exception('Access denied');
        }
        if($request->get('password') === $request->get('old_password'))
        {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('RestBundle:User')->find( $request->get('user') );
            $user->setPassword($request->get('password'));
    
            $em->flush();
            return new JsonResponse(array('OK'));
        }
        
    }

    /**
     * @Route("/admin/user/job/contacts", name="job_contact_get")
     * @Method("POST")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getContactsByJob(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $job_id     = $request->get('job');
            $em         = $this->getDoctrine()->getManager();
            $job        = $em->getRepository('RestBundle:Job')->find($job_id);
            $result     = $em->getRepository('RestBundle:Contact')->getJobContacts($job);
            return new JsonResponse(array('status' => 'Ok', 'contacts' => $result));
        }
        return new JsonResponse(array('status' => 'fail'), JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/admin/user/job/contact_add", name="job_contact_add")
     * @Method("POST")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addContact(Request $request){
        if ($request->isXmlHttpRequest()) {

            $em = $this->getDoctrine()->getManager();

            $job_id = $request->get('job');
            $job = $em->getRepository('RestBundle:Job')->find($job_id);

            $contact = new Contact();
            $contact->setJob($job);
            $contact->setName($request->get('name'));
            $contact->setTitle($request->get('title'));
            $contact->setLink($request->get('link'));
            $contact->setNote($request->get('note'));
            $contact->setStatus($request->get('status'));

            $em->persist($contact);
            $em->flush();

            $attempt = new Attempt();
            $attempt->setContact($contact);
            $attempt->setNote($contact->getNote());
            $attempt->setStatus($contact->getStatus());

            $em->persist($attempt);
            $em->flush();

            $add_contact_task = $em->getRepository('RestBundle:Task')->getAddContactTask($job);

            if($add_contact_task){
                if($add_contact_task->getCompleted() == 'no'){
                    $add_contact_task->setCompleted("yes");
                    $add_contact_task->setCompletedAt(new \DateTime());
                    $em->persist($add_contact_task);
                    $em->flush();
                }
            }

            $m1_task = new Task();
            $m1_task->setUser($job->getUser());
            $m1_task->setJobId($job->getId());
            $m1_task->setContactId($contact->getId());
            $m1_task->setName("Message 2");
            $m1_task->setDueDate($this->getDueDate(3));
            $em->persist($m1_task);
            $em->flush();

            return new JsonResponse(array('status' => 'Ok', 'id' => $contact->getId()));
        }
        return new JsonResponse(array('status' => 'fail'), JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/admin/user/job/contact_del", name="job_contact_del")
     * @Method("POST")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteContact(Request $request){
        if ($request->isXmlHttpRequest()) {
            $em         = $this->getDoctrine()->getManager();
            $contact_id = $request->get('contact');
            $contact    = $em->getRepository('RestBundle:Contact')->find($contact_id);
            if($contact != null){
                $em->remove($contact);
                $em->flush();
            }

            $tasks = $em->getRepository('RestBundle:Task')->getAllTasksByContact($contact_id);
            if($tasks && is_array($tasks)){
                foreach($tasks as $task){
                    $em->remove($task);
                }
                $em->flush();
            }

            return new JsonResponse(array('status' => 'Ok'));
        }
        return new JsonResponse(array('status' => 'fail'), JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/admin/user/job/contact_change_status", name="contact_change_status")
     * @Method("POST")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function changeContactStatus(Request $request){
        if($request->isXmlHttpRequest()){
            $contact_id = $request->get('contact');
            $status     = $request->get('status');
            $em         = $this->getDoctrine()->getManager();
            $contact    = $em->getRepository('RestBundle:Contact')->find($contact_id);
            if($contact != null){
                $contact->setStatus($status);
                $em->persist($contact);
                $em->flush();
            }

            $this->processTask($contact_id, $status);
        }
        return new JsonResponse(array('status' => 'fail'), JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/admin/user/contact/attempts", name="contact_attempt_get")
     * @Method("POST")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAttemptsByContact(Request $request){
        if ($request->isXmlHttpRequest()) {
            $contact_id = $request->get('contact');
            $em         = $this->getDoctrine()->getManager();
            $contact    = $em->getRepository('RestBundle:Contact')->find($contact_id);
            $result     = $em->getRepository('RestBundle:Attempt')->getContactAttempts($contact);
            return new JsonResponse(array('status' => 'Ok', 'attempts' => $result, 'name' => $contact->getName(), 'title' => $contact->getTitle()));
        }
        return new JsonResponse(array('status' => 'fail'), JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/admin/user/contact/attempt_add", name="contact_attempt_add")
     * @Method("POST")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAttempt(Request $request){
        if($request->isXmlHttpRequest()){
            $em         = $this->getDoctrine()->getManager();
            $contact_id = $request->get('contact');
            $contact    = $em->getRepository('RestBundle:Contact')->find($contact_id);
            $status     = $request->get('status');

            $attempt = new Attempt();
            $attempt->setContact($contact);
            $attempt->setNote($request->get('note'));
            $attempt->setStatus($status);

            $em->persist($attempt);
            $em->flush();

            $contact->setStatus($attempt->getStatus());
            $em->persist($contact);
            $em->flush();

            $this->processTask($contact_id, $status);

            return new JsonResponse(array('status' => 'Ok', 'id' => $attempt->getId(), 'date' => $attempt->getDate()));
        }
        return new JsonResponse(array('status' => 'fail'), JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/admin/user/contact/attempt_del", name="contact_attempt_del")
     * @Method("POST")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAttempt(Request $request){
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $attempt_id = $request->get('attempt');
            $attempt = $em->getRepository('RestBundle:Attempt')->find($attempt_id);
            if($attempt != null){
                $em->remove($attempt);
                $em->flush();
            }
            return new JsonResponse(array('status' => 'Ok'));
        }
        return new JsonResponse(array('status' => 'fail'), JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/admin/user/contact/attempt_note_get", name="attempt_note_get")
     * @Method("POST")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAttemptNote(Request $request){
        if ($request->isXmlHttpRequest()) {
            $em         = $this->getDoctrine()->getManager();
            $attempt_id = $request->get('attempt_id');
            $attempt    = $em->getRepository('RestBundle:Attempt')->find($attempt_id);
            return new JsonResponse(array('status' => 'Ok', 'note' => $attempt->getNote()));
        }
        return new JsonResponse(array('status' => 'fail'), JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/admin/user/contact/attempt_note_save", name="attempt_note_save")
     * @Method("POST")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function saveAttemptNote(Request $request){
        if ($request->isXmlHttpRequest()) {
            $em         = $this->getDoctrine()->getManager();
            $attempt_id = $request->get('attempt_id');
            $note       = $request->get('note');
            $attempt    = $em->getRepository('RestBundle:Attempt')->find($attempt_id);
            $attempt->setNote($note);
            $em->persist($attempt);
            $em->flush();
            return new JsonResponse(array('status' => 'Ok'));
        }
        return new JsonResponse(array('status' => 'fail'), JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/admin/user/job/note_get", name="job_note_get")
     * @Method("POST")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getJobNote(Request $request){
        if($request->isXmlHttpRequest()){
            $em     = $this->getDoctrine()->getManager();
            $job_id = $request->get('job_id');
            $job    = $em->getRepository('RestBundle:Job')->find($job_id);
            return new JsonResponse(array('status' => 'Ok', 'note' => $job->getJobdescription()));
        }
        return new JsonResponse(array('status' => 'fail'), JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/admin/user/job/note_save", name="job_note_save")
     * @Method("POST")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function saveJobNote(Request $request){
        if($request->isXmlHttpRequest()){
            $em     = $this->getDoctrine()->getManager();
            $job_id = $request->get('job_id');
            $note   = $request->get('note');
            $job    = $em->getRepository('RestBundle:Job')->find($job_id);
            $job->setJobdescription($note);
            $em->persist($job);
            $em->flush();
            return new JsonResponse(array('status' => 'Ok'));
        }
        return new JsonResponse(array('status' => 'fail'), JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/admin/user/task/change_status", name="task_change")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function changeTaskStatus(Request $request){
        if($request->isXmlHttpRequest()){
            $em         = $this->getDoctrine()->getManager();
            $task_id    = $request->get('task');
            $completed  = $request->get('completed');
            $task       = $em->getRepository('RestBundle:Task')->find($task_id);
            $task->setCompleted($completed);
            if($completed == 'yes'){
                $task->setCompletedAt(new \DateTime());
            }
            $em->persist($task);
            $em->flush();
            return new JsonResponse(array('status' => 'Ok'));
        }
        return new JsonResponse(array('status' => 'fail'), JsonResponse::HTTP_BAD_REQUEST);
    }

    public function processTask($contact_id, $status){
        $em         = $this->getDoctrine()->getManager();
        $contact    = $em->getRepository('RestBundle:Contact')->find($contact_id);
        $job        = $contact->getJob();
        if($job->getStatus() == 'Interview') return;

        $repository = $em->getRepository('RestBundle:Task');
        if($status == 'Messaged(1)'){
            $task = $repository->getLastM2Task($contact_id);
            if($task){
                $task->setDueDate($this->getDueDate(3));
                $em->persist($task);
                $em->flush();
            }
        } elseif ($status == 'Messaged(2)'){
            $m2_task = $repository->getLastM2Task($contact_id);
            if($m2_task){
                $m2_task->setCompleted('yes');
                $m2_task->setCompletedAt(new \DateTime());
                $em->persist($m2_task);
                $em->flush();
            }

            $m3_task = new Task();
            $m3_task->setUser($job->getUser());
            $m3_task->setJobId($job->getId());
            $m3_task->setContactId($contact_id);
            $m3_task->setName("Message 3");
            $m3_task->setDueDate($this->getDueDate(3));
            $em->persist($m3_task);
            $em->flush();
        } elseif ($status == 'Messaged(3)'){
            $m3_task = $repository->getLastM3Task($contact_id);
            if($m3_task){
                $m3_task->setCompleted('yes');
                $m3_task->setCompletedAt(new \DateTime());
                $em->persist($m3_task);
                $em->flush();
            }
            $contact->setStatus('Unresponsive');
            $em->persist($contact);
            $em->flush();
        } elseif ($status == 'Reviewing' || $status == 'Forwarded'){
            $task = $repository->getFollowUpTask($contact_id);
            if (! $task || $task->getCompleted() == 'yes'){
                $new_task = new Task();
                $new_task->setUser($job->getUser());
                $new_task->setJobId($job->getId());
                $new_task->setContactId($contact_id);
                $new_task->setName('Follow-Up');
                $new_task->setDueDate($this->getDueDate(5));
                $em->persist($new_task);
                $em->flush();
            }
        } elseif ($status == 'No Help' || $status == 'Referred' || $status == 'Rejected' || $status == 'Unresponsive' || $status == 'Interested'){
            $tasks = $repository->getAllTasksByContact($contact_id);
            if($tasks && is_array($tasks)){
                foreach($tasks as $task){
                    $em->remove($task);
                }
                $em->flush();
            }
        }
    }

    /**
     * get due date considered weekend
     * @param $due
     * @return \DateTime
     */
    public function getDueDate($due){
        $additional = 0;
        for($i = 1; $i < $due + 1; $i++){
            $str = $i == 1 ? 'now +1 day' : 'now +' . $i . ' days';
            if($this->isWeekend(new \DateTime($str))){
                $additional++;
            }
        }
        $due = $due + $additional;
        $str_date = $due == 1 ? 'now  +1 day' : 'now +' . $due . ' days';
        return new \DateTime($str_date);
    }

    /**
     * check if date is weekend
     * @param $date
     * @return bool
     */
    public function isWeekend($date){
        $str_date = $date->format('Y-m-d H:i:s');
        $weekday = date('w', strtotime($str_date));
        return ($weekday == 0 || $weekday == 6);
    }
}
