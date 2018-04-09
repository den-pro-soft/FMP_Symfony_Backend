<?php

namespace AdminBundle\Controller;

use JMS\Serializer\SerializationContext;
use RestBundle\Entity\Message;
use RestBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends Controller
{
    /**
     * @Route("/admin/message/count", name="admin_message_get_count")
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function indexAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
 
             /** @var Message[] $messages */
             $messages = $em->getRepository('RestBundle:Message')
                ->createQueryBuilder('m')
                ->select('m')
                ->join('m.unread_users', 'u')
                ->where('u.id = :my_id')
                ->setParameters(array(
                    'my_id' => $this->getUser()->getId(),
                ))
                ->getQuery()
                ->getResult(); 

            if (empty($messages)) {
                return new JsonResponse(array());
            }

            $data = array();

            foreach ($messages as $message) {
                /** @var User $author */
                $author = $em->getRepository('RestBundle:User')->find($message->getAuthor());
                $message->setUsername($author->getFullName());

                $data[$author->getId()][] = $message;
            }

            return new Response($this->get('serializer')->serialize($data, 'json', SerializationContext::create()->setGroups(array('api'))));
        }

        return new JsonResponse(array('status' => 'Fail'), JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @param Request $request
     * @param Message $message
     * @return JsonResponse
     *
     * @Route("/admin/message/{message}/delete", name="admin_message_delete")
     */
    public function deleteAction(Request $request, Message $message)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($message);
            $em->flush();

            return new JsonResponse(array('status' => 'Success'), JsonResponse::HTTP_OK);
        }

        return new JsonResponse(array('status' => 'Fail'), JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @param Request $request
     * @param Message $message
     * @return JsonResponse
     *
     * @Route("/admin/message/{message}/update", name="admin_message_update")
     */
    public function udpateAction(Request $request, Message $message)
    {  

        if ($request->isXmlHttpRequest()) {
            
            $em = $this->getDoctrine()->getManager();
            $message->setMessage( $request->get('mess_data') );
            $message->setEdited(1);
            $em->persist($message);
            $em->flush();

            return new JsonResponse(array('status' => 'Success'), JsonResponse::HTTP_OK);
        }

        return new JsonResponse(array('status' => 'Fail'), JsonResponse::HTTP_BAD_REQUEST);
    }
}
