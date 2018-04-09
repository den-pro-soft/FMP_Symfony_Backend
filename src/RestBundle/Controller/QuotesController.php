<?php

namespace RestBundle\Controller;

use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\FOSRestController as Controller;
use AdminBundle\Form\QuotesType;
use RestBundle\Entity\Quotes;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Query\ResultSetMapping;
use RestBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;

class QuotesController extends Controller
{
    public function check24hours( $front )
    {
        $since_start = $front->diff(new \DateTime("now"));

        return $since_start->days >= 1;
    }

    /**
     * @Route("/quote", name="quote")
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    { 
        /** @var User $user */
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $quoteRepo =  $this->getDoctrine()->getRepository('RestBundle:Quotes');

        if( $user->getQuotesDate() == null || $this->check24hours( $user->getQuotesDate() ) )
        {
             $user->setQuotesNum( $user->getQuotesNum() + 1 );
             $user->setQuotesDate( new \DateTime("now") );
             $user->setQuoteChecked( false );
             $em->persist($user);
             $em->flush();
        }

        $quoteno = $user->getQuotesNum();
        $quote = [];

        if( !$user->getQuoteChecked() )
        {
            $quote = $quoteRepo->createQueryBuilder('p')
                ->select('p.content')
                ->orderBy('p.no', 'DESC')
                ->setFirstResult($quoteno)
                ->setMaxResults( 1 )
                ->getQuery()
                ->useQueryCache(true)
                ->getResult();
        }

        return $this->handleView($this->view($quote)->setContext( (new Context() )->setGroups(['quote', 'Default'])));
    }

     /**
     * @Route("/quote/close", name="close_quote")
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function closeQuote()
    {
        /** @var User $user */
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $user->setQuoteChecked( true );
        $em->persist($user);
        $em->flush();

        return new JsonResponse([]);
    }
}