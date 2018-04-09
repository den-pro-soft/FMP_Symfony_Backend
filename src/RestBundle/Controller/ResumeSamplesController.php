<?php
/**
 * Created by LiuWebDev
 */

namespace RestBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Request\ParamFetcherInterface;
use RestBundle\Exception\ApiException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResumeSamplesController extends Controller
{
    public $CATEGORIES = array(
        'senior',
        'entry'
    );

    /**
     * @Route("/resume-samples/all", name="all_resume_samples")
     * @Method("GET")
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $repo       = $this->getDoctrine()->getManager()->getRepository('RestBundle:ResumeSample');
        $result     = array();

        $base_url   = $request->getSchemeAndHttpHost() . '/uploads/';

        foreach ($this->CATEGORIES as $category)
        {
            $items          = $repo->getSamplesByCategoryAsArray($category);
            $sample_array   = array();

            foreach ($items as $item)
            {
                $sample                 = $item;
                $sample['image_name']   = $base_url . $item['image_name'];
                $sample['pdf_name']     = $base_url . 'templates/' . $item['pdf_name'];
                $sample_array[]         = $sample;
            }
            $result[$category]          = $sample_array;
        }

        return $this->handleView($this->view($result)->setContext((new Context())->setGroups(array('resumes'))));
    }
}