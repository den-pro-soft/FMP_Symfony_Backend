<?php

namespace RestBundle\Controller;

use AdminBundle\Form\EducationType;
use AdminBundle\Form\JobType;
use AdminBundle\Form\QuestionsType;
use AdminBundle\Form\ReferenceType;
use AdminBundle\Form\WorkExperienceType;
use FOS\RestBundle\Context\Context;
use RestBundle\Entity\Document;
use RestBundle\Entity\Education;
use RestBundle\Entity\CareerPreferences;
use RestBundle\Entity\Job;
use RestBundle\Entity\Profile;
use RestBundle\Entity\Questions;
use RestBundle\Entity\UserPackages;
use RestBundle\Entity\UserReference;
use RestBundle\Entity\User;
use RestBundle\Entity\WorkExperience;
use RestBundle\Exception\ApiException;
use RestBundle\Form\CareerPreferencesType;
use RestBundle\Form\ProfileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\FOSRestController as Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    /**
     * @Route("/profile", name="profile")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profileAction()
    {
        /** @var User $user */
        $user = $this->getUser();
        $profile = $user->getProfile();

        /** @var UserPackages[] $packages */
        $packages = $user->getPackages();

        foreach ($packages as $package) {
            $service = $package->getService()->getLink();
            if (in_array( $service, ['career-finder', 'resume-makeover'] , true ) ) {
                 $templates = $this->getDoctrine()->getRepository('RestBundle:Template')->findAll();
                 $profile->setTemplates($templates);
                break;
            }
        }

        return $this->handleView($this->view($profile)->setContext((new Context())->setGroups(['profile', 'Default'])));
    }

    /**
     * @Route(
     *     "/profile/{section}",
     *     defaults={"section": "profile"},
     *     requirements={"section": "profile|career_preferences|education|work_experience|reference|document|questions"},
     *     name="profile_save_section"
     * )
     * @Method({"POST", "PUT"})
     *
     * @param Request $request
     * @param string $section
     * @return Response
     * @throws ApiException
     */
    public function saveAction(Request $request, $section)
    {
        /** @var User $user */
        $user = $this->getUser();
        $profile = $user->getProfile();
        $em = $this->getDoctrine()->getManager();

        switch ($section) {
            case 'profile':
                $form = $this->createForm(ProfileType::class, $profile);
                $form->submit($request->request->all(), false);

                if ($request->request->has('full_name')) {
                    $user->setFullName($request->get('full_name'));
                    $em->persist($user);
                }

                break;

            case 'career_preferences':
                if ($request->isMethod('PUT')) {
                    $object = $profile->getCareerPreferences();
                } else {
                    $object = new CareerPreferences();
                }

                $form = $this->createForm(CareerPreferencesType::class, $object);

                $form->submit($request->request->all(), false);

                if ($form->isValid()) {
                    $profile->setCareerPreferences($object);
                }

                break;

            case 'education':
                $education = new Education();
                $education->setProfile($profile);

                if ($request->request->has('id')) {
                    $education = $em->getRepository('RestBundle:Education')->find($request->get('id'));
                }

                $form = $this->createForm(EducationType::class, $education);

                $form->submit($request->request->all(), false);

                if ($form->isValid()) {
                    $education->setStartDate((new \DateTime($education->getStartDate()))->format('Y-m-d'));

                    if ($request->request->has('end_date') && null !== $request->request->get('end_date')) {
                        $education->setEndDate((new \DateTime($education->getEndDate()))->format('Y-m-d'));
                    } else {
                        $education->setEndDate(null);
                    }

                    $em->persist($education);
                    $profile->addEducation($education);
                }

                break;

            case 'work_experience':
                $work_experience = new WorkExperience();
                $work_experience->setProfile($profile);

                if ($request->request->has('id')) {
                    $work_experience = $em->getRepository('RestBundle:WorkExperience')->find($request->get('id'));
                }

                $form = $this->createForm(WorkExperienceType::class, $work_experience);

                $form->submit($request->request->all(), true);

                if ($form->isValid()) {
                    $work_experience->setStartDate((new \DateTime($work_experience->getStartDate()))->format('Y-m-d'));

                    if ($request->request->has('end_date') && null !== $request->request->get('end_date')) {
                        $work_experience->setEndDate((new \DateTime($work_experience->getEndDate()))->format('Y-m-d'));
                    } else {
                        $work_experience->setEndDate(null);
                    }

                    $profile->addWorkExperience($work_experience);
                }

                break;

            case 'reference':
                $reference = new UserReference();
                $reference->setProfile($profile);

                if ($request->request->has('id')) {
                    $reference = $em->getRepository('RestBundle:UserReference')->find($request->get('id'));
                }

                $form = $this->createForm(ReferenceType::class, $reference);

                $form->submit($request->request->all(), false);

                if ($form->isValid()) {
                    $profile->addReference($reference);
                }

                break;

            case 'document':
                if ($request->request->has('id') && $request->request->has('type')) {
                    $document = $em->getRepository('RestBundle:Document')->find($request->get('id'));
                    $document->setType($request->get('type'));
                } else if ($request->files->has('document')) {
                    /** @var UploadedFile $uploaded_file */
                    $uploaded_file = $request->files->get('document');
                    /** @var File $file */
                    $file = $this->get('app.file_uploader')->upload($uploaded_file);

                    $document = new Document();
                    $document->setAddedBy($user->getFullName());
                    $document->setDateAdded(new \DateTime());
                    $document->setType('Resume');
                    $document->setName($uploaded_file->getClientOriginalName());
                    $document->setPath($file->getFilename());
                    $document->setDocument($file);
                    /** @var Profile $profile */
                    $profile = $user->getProfile();

                    $document->setProfile($profile);

                    $validator = $this->get('validator');
                    $errors = $validator->validate($document);

                    if (count($errors) > 0) {
                        $errorsString = (string) $errors;

                        throw new ApiException($errorsString);
                    }

                } else {
                    throw new ApiException('No valid document');
                }


                $em->persist($document);

                break;

            case 'questions':
                $questions = $profile->getQuestions();

                if (!$questions instanceof Questions) {
                    $questions = new Questions();
                }

                $form = $this->createForm(QuestionsType::class, $questions);
                $form->submit($request->request->all());

                if ($form->isValid()) {
                    $em->persist($questions);
                    $profile->setQuestions($questions);
                } else {
                    throw new ApiException($form->getErrorsAsString());
                }

                break;
        }

        $em->persist($profile);
        $em->flush();

        return $this->profileAction();
    }

    /**
     * @Route(
     *     "/profile/{section}/{id}",
     *     requirements={"section": "education|work_experience|reference|document", "id": "\d+"},
     *     name="profile_delete_section"
     * )
     * @Method("DELETE")
     *
     * @param string $section
     * @param $id
     * @return Response
     * @throws ApiException
     */
    public function deleteAction($section, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Profile $profile */
        $profile = $this->getUser()->getProfile();
        $object = null;

        switch ($section) {
            case 'education':
                $object = $em->getRepository('RestBundle:Education')->find($id);

                break;

            case 'work_experience':
                $object = $em->getRepository('RestBundle:WorkExperience')->find($id);

                break;

            case 'reference':
                $object = $em->getRepository('RestBundle:UserReference')->find($id);

                break;

            case 'document':
                $object = $em->getRepository('RestBundle:Document')->find($id);

                break;
        }

        if (!$object) {
            throw new ApiException('Object not fount', 404);
        }

        $em->remove($object);
        $em->flush();

        return $this->profileAction();
    }

    /**
     * @Route("/profile/jobs", name="user_profile_jobs")
     * @Method("GET")
     */
    public function userJobAction()
    {
        /** @var Job[] $jobs */
        $jobs = $this->getDoctrine()->getRepository('RestBundle:Job')->getUserJobs($this->getUser());
        $jobs_liked = [];
        $jobs_applied = [];

        foreach ($jobs as $job) {
            if ($job->getSection() === 'liked') {
                $jobs_liked[] = $job;
            } else {
                $jobs_applied[] = $job;
            }
        }

        return $this->handleView($this->view([
            'jobs_liked' => $jobs_liked,
            'jobs_applied' => $jobs_applied
        ]));
    }

    /**
     * @Route("/profile/jobs", name="add_user_profile_jobs")
     * @Method("POST")
     * @param Request $request
     * @return Response
     * @throws ApiException
     */
    public function addJobAction(Request $request)
    {
        $job = new Job();
        $form = $this->createForm(JobType::class, $job);
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $job->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($job);
            $em->flush();
        } else {
            throw new ApiException('Form do not valid');
        }

        return $this->handleView($this->view(['status' => 'Ok']));
    }

    /**
     * @Route("/profile/job/status", name="change_profile_job_status")
     * @Method("POST")
     * @param Request $request
     * @return Response
     */
    public function changeStatusJobAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $job = $em->getRepository('RestBundle:Job')->find($request->get('id'));

        $status = $request->get('status');

        if ($status === 'Applied') {
            $job->setSection('applied');
        }

        $job->setStatus($status);
        $em->persist($job);
        $em->flush();

        return $this->handleView($this->view(['status' => 'Ok']));
    }

    /**
     * @Route("/profile/{job}", name="user_profile_add_cover_letter")
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
            $em->flush();
        } else {
            throw new ApiException('No file');
        }

        return $this->handleView($this->view(['status' => 'Ok']));
    }
}
