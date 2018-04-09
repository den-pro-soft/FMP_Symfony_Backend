<?php

namespace RestBundle\Services;

use RestBundle\Exception\ApiException;
use RestBundle\Manager\MailChimpMessageInterface;
use DrewM\MailChimp\MailChimp;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MailChimpNotification
 * @package RestBundle\Service
 */
class MailChimpNotification
{
    /**
     * @var MailChimp object
     */
    private $mailChimp;

    /**
     * MailChimpNotification constructor.
     * @param string $apiKey
     *
     */
    public function __construct($apiKey)
    {
        $this->mailChimp = new MailChimp($apiKey);
    }

    /**
     * @param $email
     * @param $list
     * @return array
     * @throws ApiException
     */
    public function subscribe($email, $list)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $status = 'subscribed';

            $array_response = $this->mailChimp
                ->post("lists/$list/members", [
                    'email_address' => $email,
                    'status' => $status,
                ]);

            if ($array_response['status'] === $status) {
                return [
                    'message' => Response::HTTP_OK,
                    'code' => Response::HTTP_OK
                ];
            }

            return [
                'message' => $array_response['title'],
                'code' => $array_response['status'],
            ];
        }

        throw new ApiException('Email isn\'t valid');
    }

    /**
     * @param MailChimpMessageInterface $message
     * @param $list
     * @return array|false
     */
    public function send(MailChimpMessageInterface $message, $list)
    {
        $new_campaign_id = $this->mailChimp->post('campaigns', [
            'recipients' => [
                'list_id' => $list,
            ],
            'type' => 'regular',
            'settings' => [
                'subject_line' => $message->getSubject(),
                'title' => $message->getTitle(),
                'reply_to' => $message->getReplyTo(),
                'from_name' => $message->getFromName(),
            ]
        ])['id'];

        $this->mailChimp->put("campaigns/$new_campaign_id/content", [
            'html' => $message->getBody(),
        ]);

        return $this->mailChimp->post("campaigns/$new_campaign_id/actions/send");
    }
}