<?php
/**
 * Created by LiuWebDev.
 */

namespace AdminBundle\Controller;

use RestBundle\Entity\ResumeSample;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AdminBundle\Form\ResumeSampleType;

/**
 * Class ResumeSamplesController
 * @package AdminBundle\Controller
 */
class ResumeSamplesController extends Controller
{
    /**
     * @Route("/admin/resume-samples/{page}", requirements={"page": "\d+"}, defaults={"page": 1}, name="view_resume_samples")
     * @param Request $request
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, $page)
    {
        if($request->isMethod('POST'))
        {
            $page = 1;
        }

        return $this->getResumeSamplesPage($request, $page);
    }

    public function getResumeSamplesPage($request, $page)
    {
        $em         = $this->getDoctrine()->getManager();
        $sortField  = $request->query->get('sort_field', 'title');
        $sortOrder  = $request->query->get('sort_order', 'ASC');
        $query      = $request->get('query');
        $filter     = $request->get('filter');

        $samples = $em->getRepository('RestBundle:ResumeSample')->getSamplesBy($filter, $query, $sortField, $sortOrder, $page, 15);
        return $this->render('@Admin/Admin/resume-samples.html.twig', array(
            'samples'   => $samples['samples'],
            'pages'     => $samples['count'],
            'current'   => $page,
            'filter'    => $filter,
            'query'     => $query
        ));
    }

    /**
     * @Route("/admin/resume-samples/add", name="add_resume_sample")
     * @param Request $request
     * @return Response
     */
    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $sample = new ResumeSample();

        if($request->isMethod('POST'))
        {
            $resume     = $request->get('resume');

            $title      = $resume['title'];
            $category   = $resume['category'];
            $status     = $resume['status'];
            $image      = $resume['image'];
            $pdf        = $resume['pdf'];

            $errors = array();
            if($title == ''){
                $errors[] = 'Title is blank.';
            } elseif ($category == ''){
                $errors[] = 'Category is not selected.';
            } elseif ($status == ''){
                $errors[] = 'Status is not selected.';
            } elseif (!isset($image) || empty($image)){
                $errors[] = 'Image is blank.';
            } elseif (!isset($pdf) || empty($pdf)){
                $errors[] = 'Document is blank.';
            }

            if(count($errors) == 0)
            {
                $sample->setTitle($title);
                $sample->setCategory($category);
                $sample->setStatus($status);
                $sample->addUrl();
                $sample->setImageName($image);
                $sample->setPdfName($pdf);

                $em->persist($sample);
                $em->flush();

                $this->addFlash('success', 'Resume sample was added successfully.');
                return $this->redirectToRoute('view_resume_samples');
            } else {
                foreach ($errors as $error)
                {
                    $this->addFlash('error', $error);
                }
            }
        }

        return $this->render('@Admin/Admin/resume-sample-editor.html.twig', array('sample'=>$sample, 'is_edit' => false));
    }

    /**
     * @Route("/admin/resume-samples/edit/{sample}", name="edit_resume_sample")
     * @param Request $request
     * @param ResumeSample $sample
     * @return Response
     */
    public function editAction(Request $request, ResumeSample $sample)
    {
        $em = $this->getDoctrine()->getManager();

        if($request->isMethod('POST'))
        {
            $resume     = $request->get('resume');

            $title      = $resume['title'];
            $category   = $resume['category'];
            $status     = $resume['status'];
            $image      = $resume['image'];
            $pdf        = $resume['pdf'];

            $errors = array();
            if($title == ''){
                $errors[] = 'Title is blank.';
            } elseif ($category == ''){
                $errors[] = 'Category is not selected.';
            } elseif ($status == ''){
                $errors[] = 'Status is not selected.';
            }

            if(count($errors) == 0)
            {
                $sample->setTitle($title);
                $sample->setCategory($category);
                $sample->setStatus($status);
                $sample->addUrl();
                $sample->setUpdatedAt(new \DateTime());

                if(isset($image) && ! empty($image))
                {
                    $sample->setImageName($image);
                }

                if(isset($pdf) && ! empty($pdf))
                {
                    $sample->setPdfName($pdf);

                }

                $em->persist($sample);
                $em->flush();

                $this->addFlash('success', 'Resume sample was updated successfully.');
                return $this->redirectToRoute('view_resume_samples');
            } else {
                foreach ($errors as $error)
                {
                    $this->addFlash('error', $error);
                }
            }
        }

        return $this->render('@Admin/Admin/resume-sample-editor.html.twig', array(
            'is_edit' => true,
            'sample' => $sample
        ));
    }

    /**
     * @Route("/admin/resume-samples/delete/{sample}", name="delete_resume_sample")
     * @param Request $request
     * @param ResumeSample $sample
     * @return null
     */
    public function deleteAction(Request $request, ResumeSample $sample)
    {
        $this->addFlash(
            'success',
            $sample->getTitle() . ' was deleted successfully.'
        );
        $em = $this->getDoctrine()->getManager();
        $em->remove($sample);
        $em->flush();

        $filter = $request->get('filter');
        $page   = $request->get('page');

        return $this->redirectToRoute('view_resume_samples', array('page' => $page, 'filter' => $filter));
    }
}

















