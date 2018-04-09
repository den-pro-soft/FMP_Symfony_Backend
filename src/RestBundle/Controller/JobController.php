<?php

namespace RestBundle\Controller;

use AdminBundle\Form\JobType;
use FOS\RestBundle\Controller\FOSRestController as Controller;
use RestBundle\Entity\Job;
use RestBundle\Entity\Service;
use RestBundle\Entity\User;
use RestBundle\Entity\UserPackages;
use RestBundle\Exception\ApiException;
use RestBundle\Form\EditJobType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;

class JobController extends Controller
{
    /**
     * @Route("/job/cover/{job}", name="job_cover_add")
     * @Method("POST")
     * @param Request $request
     * @param Job $job
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws ApiException
     */
    public function addCoverAction(Request $request, Job $job)
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
            return $this->handleView($this->view(['status' => 'Fail'], 400));
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($job);
        $em->flush();

        return $this->handleView($this->view($job));
    }

    /**
     * @Route("/job/cover/{job}", name="job_cover_remove")
     * @Method("DELETE")
     * @param Job $job
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeCoverAction(Job $job)
    {
        if ($job->getAttachmentAlias()) {
            $job->setAttachment(null);
            $job->setAttachmentName(null);
            $job->setAttachmentAlias(null);
        } else {
            return $this->handleView($this->view(['status' => 'Fail'], 400));
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($job);
        $em->flush();

        return $this->handleView($this->view(['status' => 'Ok']));
    }

    /**
     * @Route("/job/{section}",
     *     requirements={"section": "liked|applied"},
     *     defaults={"section": "liked"},
     *     name="profile_jobs"
     * )
     * @Rest\QueryParam(name="page", requirements="\d+", default="1")
     * @Rest\QueryParam(name="filter", default=null)
     * @Method("GET")
     * @param Request $request
     * @param $section
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, $section)
    {
        $filter = $request->get('filter');
        $page = $request->get('page');
        $em = $this->getDoctrine()->getManager();

        if (!$page) {
            $page = 1;
        }

        /** @var array $jobs */
        $jobs = $em->getRepository('RestBundle:Job')
            ->getJobsBySection($this->getUser(), $section, $page, $filter);

        $maxdate = '';
        
        
        
        $data = $jobs;

        if ($filter) {
            $data['jobs'] = [];

            foreach ($jobs['jobs'] as $job) {
                /** @var Job $job */
                $job->setIsNew(false);
                $em->persist($job);

                $data['jobs'][] = $job;
            }

            $em->flush();
        }

        $data['last_active_days'] = $this->getUser()->getLastActiveDays();

        return $this->handleView($this->view($data));
    }

    /**
     * @Route("/job", name="profile_add_job")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    { 
        /** @var User $user */
        $user = $this->getUser();

        $job = new Job();
        $job->setAddedBy('user');
        $form = $this->createForm(JobType::class, $job);
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {

            $careerFinderService = $this->getDoctrine()->getRepository(Service::class)->findOneBy(['link' => 'career-finder']);
            $isCareerFinder = $this->getDoctrine()->getRepository(UserPackages::class)->findOneBy(['user' => $user, 'service' => $careerFinderService]);

            if ($isCareerFinder) {
                $job->setStatus('Ready', true);
                $job->setChecked(true);
            }

            $job->setUser($user);
            $job->setIsNew(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($job);
            $em->flush();
        } else {
            $errors = $this->get('validator')->validate($job);

            if (count($errors) > 0) {
                return $this->handleView($this->view(['status' => $errors[0]->getMessage()], 400));
            }
        }

        return $this->handleView($this->view($job));
    }


    /**
     * @Route("/job/update/{job}", name="profile_update_job")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateJobAction(Request $request, Job $job)
    { 
        /** @var User $user */
        $user = $this->getUser();
  
        $job->setLink( $request->request->get('link') );
        $job->setPosition( $request->request->get('position') );
        $job->setCompany( $request->request->get('company') );
        $em = $this->getDoctrine()->getManager();
        $em->persist($job);
        $em->flush(); 
        return $this->handleView($this->view($job));
    }

    /**
     * @Route("/job/updatenote/{job}", name="profile_updatenote_job")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateJobNoteAction(Request $request, Job $job)
    {
        /** @var User $user */
        $user = $this->getUser();
        $job->setJobdescription( $request->request->get('jobdescription') );
        $em = $this->getDoctrine()->getManager();
        $em->persist($job);
        $em->flush();
        return $this->handleView($this->view($job));
    }

    /**
     * @Route("/job/{job}", name="profile_change_job")
     * @Method("PUT")
     * @param Request $request
     * @param Job $job
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function putAction(Request $request, Job $job)
    {
        $em = $this->getDoctrine()->getManager();
        $packages = $this->getUser()->getPackages()->toArray();
        $career_finder = $em->getRepository('RestBundle:Service')->findOneBy(['price_executive' => null]);

        /** @var UserPackages[] $packages */
        foreach ($packages as $package) {
            if ($career_finder === $package->getService()) {
                $form = $this->createForm(EditJobType::class, $job);
                $form->submit($request->request->all(), false);

                if ($form->isValid()) {
                    if ($request->request->has('checked') && $request->request->get('checked') === true) {
                        $job->setStatus('Ready');
                    } else {
                        $job->setStatus('Pending');
                        $job->setSection('liked');
                    }

                    $em->persist($job);
                    $em->flush();
                } else {
                    $errors = $this->get('validator')->validate($job);

                    if (count($errors) > 0) {
                        return $this->handleView($this->view(['status' => $errors[0]->getMessage()], 400));
                    }
                }

                return $this->indexAction($request, $job->getSection());
            }
        }

        return $this->handleView($this->view(['status' => 'Fail'], 400));
    }

    /**
     * @Route("/job-rate/{job}", name="profile_change_job_rate")
     * @Method("PUT")
     * @param Request $request
     * @param Job $job
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function jobRateAction(Request $request, Job $job)
    {
        $job->setRate($request->get('rate'));
        $em = $this->getDoctrine()->getManager();
        $em->persist($job);
        $em->flush();

        return $this->handleView($this->view(['status' => 'Ok']));
    }


    /**
     * @Route("/job-status/{job}", name="profile_change_job_status")
     * @Method("PUT")
     * @param Request $request
     * @param Job $job
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function jobStatusAction(Request $request, Job $job)
    {
        $oldJobStatus = $job->getStatus();

        $form = $this->createForm(EditJobType::class, $job);
        $form->submit($request->request->all(), false);

       

        if ($form->isValid() && $form->isSubmitted()) {
            $status = $form->get('status')->getData();
            $section = $job->getSection();

            switch($status) {
                case 'Applied':
                    $job->setChecked(false);
                    $job->setSection('applied');
                    $job->setAppliedDate(new \DateTime());
                    break;

                case 'Ready':
                    $job->setChecked(true);
                    $job->setSection('liked');
                    break;

                case 'Pending':
                case 'Need Info':
                    $job->setChecked(false);
                    $job->setSection('liked');
                    break;
                case 'Not Interested':
                    if ($job->getAddedBy() === 'admin') {
                        $job->setSection('liked');
                    } else {
                        //$status = $oldJobStatus;
                    }
                    break;
            }

            $job->setStatus($status);

            $em = $this->getDoctrine()->getManager();
            $em->persist($job);
            $em->flush();

            return $this->indexAction($request, $section);
        }

        return $this->handleView($this->view(['status' => 'Fail'], 400));
    }

    /**
     * @Route("/job/{job}", name="profile_remove_job")
     * @Method("DELETE")
     * @param Request $request
     * @param Job $job
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Request $request, Job $job)
    {
        $section = $job->getSection();
        $em = $this->getDoctrine()->getManager();
        $em->remove($job);
        $em->flush();

        return $this->indexAction($request, $section);
    }
}
