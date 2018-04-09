<?php
namespace RestBundle\EventListener;

use JMS\Serializer\Handler\FormErrorHandler;
use RestBundle\Exception\AccessDeniedException;
use RestBundle\Exception\ApiException;
use RestBundle\Exception\PasswordException;
use RestBundle\Exception\UserNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

/**
 * Class ExceptionListener
 * @package ApiBundle\EventListener
 */
class ExceptionListener
{
    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        switch($exception) {
            case $exception instanceof PasswordException:
                $event->setResponse($this->buildPasswordException($exception->getMessage()));
                break;

            case $exception instanceof AccessDeniedException:
                $event->setResponse($this->buildAccessDeniedException());
                break;

            case $exception instanceof UserNotFoundException:
                $event->setResponse($this->buildUserNotFoundException());
                break;

            case $exception instanceof ApiException:
                $event->setResponse($this->buildApiException($exception->getMessage()));
                break;
        }
    }

    /**
     * @param $text
     * @return JsonResponse
     */
    private function buildPasswordException($text)
    {
        $message = [
            'status' => 'Fail',
            'message' => $text ? :'Incorrect password'
        ];

        return new JsonResponse($message, JsonResponse::HTTP_BAD_REQUEST);
    }

    private function buildAccessDeniedException()
    {
        $message = [
            'status' => 'Fail',
            'message' => 'Access denied'
        ];

        return new JsonResponse($message, JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @return JsonResponse
     */
    private function buildUserNotFoundException()
    {
        $message = [
            'status' => 'Fail',
            'message' => 'User not found'
        ];

        return new JsonResponse($message, JsonResponse::HTTP_NOT_FOUND);
    }

    /**
     * @param $text
     * @return JsonResponse
     */
    private function buildApiException($text)
    {
        $message = [
            'status' => 'Fail',
            'message' => $text
        ];

        return new JsonResponse($message, JsonResponse::HTTP_BAD_REQUEST);
    }
}
