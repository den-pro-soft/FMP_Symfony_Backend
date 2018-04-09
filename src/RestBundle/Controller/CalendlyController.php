<?php

namespace RestBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController as Controller;
use RestBundle\Entity\Schedule;
use RestBundle\Entity\User;
use RestBundle\Entity\UserPackages;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CalendlyController extends Controller
{
    /**
     * @Route("/calendly", name="calendly")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(), true);

        $user = $em->getRepository('RestBundle:User')->findOneBy(['email' => $data['payload']['invitee']['email']]);

        if (!$user instanceof User) {
            return new JsonResponse(['status' => 'fail'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if ($data['event'] === 'invitee.canceled') {
            $schedule = $em->getRepository('RestBundle:Schedule')->findOneBy(['uuid' => $data['payload']['event']['uuid']]);
            $schedule->setStatus('Canceled');
        } else {
            $schedule = new Schedule();
            $schedule->setEvent($data['event']);
            $schedule->setLink($data['payload']['event_type']['slug']);
            $schedule->setName($data['payload']['event_type']['name']);
            $schedule->setDuration($data['payload']['event_type']['duration']);
            $schedule->setStartTime(new \DateTime($data['payload']['event']['start_time']));
            $schedule->setEndTime(new \DateTime($data['payload']['event']['end_time']));
            $schedule->setLocation($data['payload']['event']['location']);
            $schedule->setCanceled($data['payload']['event']['canceled']);
            $schedule->setUuid($data['payload']['event']['uuid']);
            $schedule->setInviteeName($data['payload']['invitee']['name']);
            $schedule->setInviteeEmail($data['payload']['invitee']['email']);

            /** @var UserPackages[] $packages */
            $packages = $user->getPackages();

            foreach ($packages as $package) {
                $service = $package->getService();
                $links = [
                    'resume-makeover' => [
                        'resume-makeover-executive',
                        'resume-makeover-senior'
                    ],
                    'cover-letter-service' => [
                        'cover-letter-writing-executive',
                        'cover-letter-writing-senior'
                    ],
                    'linkedin-profile-makeover' => [
                        'linkedin-profile-makeover-executive',
                        'linkedin-profile-makeover-senior'
                    ],
                    'job-interview-prep' => [
                        'interview-training-executive-executive',
                        'interview-training-executive-senior'
                    ],
                    'career-finder' => [
                        'career-finder-intro',
                        'career-finder-intro-1'
                    ]
                ];

                if ($package->getUuid() === null && in_array($schedule->getLink(), $links[$service->getLink()], true)) {
                    $package->setUuid($schedule->getUuid());
                    $em->persist($package);
                    break;
                }
            }
        }

        $schedule->setCancelerName($data['payload']['event']['canceler_name']);
        $schedule->setCancelReason($data['payload']['event']['cancel_reason']);

        $em->persist($schedule);
        $em->flush();

        return new JsonResponse(['status' => 'ok'], 200);
    }
}
