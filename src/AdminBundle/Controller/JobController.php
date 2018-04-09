<?php

namespace AdminBundle\Controller;

use RestBundle\Entity\Job;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class JobController extends Controller
{
    /**
     * @Route("/admin/jobs/check-new", name="admin_job_status_check")
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function checkNewAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();

            /** @var Job[] $messages */
            $jobs = $em->getRepository('RestBundle:Job')->getReadyJobs($this->getUser());

            if (empty($jobs)) {
                return new JsonResponse(array());
            }

            $data = array();

            foreach ($jobs as $job) {
                $job->username = $job->getUser()->getFullName();
                $data[$job->getUser()->getId()][] = $job;
            }

            return new Response($this->get('serializer')->serialize($data, 'json'));
        }

        return new JsonResponse(array('status' => 'Fail'), JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/admin/jobs/{job}/delete", name="admin_delete_job")
     * @param Job $job
     * @return JsonResponse|Response
     */
    public function deleteAction(Job $job)
    {
        $userId = $job->getUser()->getId();

        $em = $this->getDoctrine()->getManager();
        $em->remove($job);
        $em->flush();

        $tasks = $em->getRepository('RestBundle:Task')->getAllTasksByContact($job->getId());
        if($tasks && is_array($tasks)){
            foreach($tasks as $task){
                $em->remove($task);
            }
            $em->flush();
        }

        return $this->redirectToRoute('user_jobs', array('user' => $userId));
    }

}