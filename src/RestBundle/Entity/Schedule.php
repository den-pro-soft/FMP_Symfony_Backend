<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.06.17
 * Time: 10:09
 */

namespace RestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="schedules")
 */
class Schedule
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $uuid;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     */
    protected $event;

    /**
     * @ORM\Column(type="string")
     */
    protected $link;

    /**
     * @ORM\Column(type="integer")
     */
    protected $duration;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $start_time;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $end_time;

    /**
     * @ORM\Column(type="string")
     */
    protected $location;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $canceled;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $canceler_name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $cancel_reason;

    /**
     * @ORM\Column(type="string")
     */
    protected $invitee_name;

    /**
     * @ORM\Column(type="string")
     */
    protected $invitee_email;

    /**
     * @ORM\Column(type="string")
     */
    protected $status = 'Pending';

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param mixed $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return mixed
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param mixed $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->start_time;
    }

    /**
     * @param \DateTime $start_time
     */
    public function setStartTime($start_time)
    {
        $this->start_time = $start_time;
    }

    /**
     * @return \DateTime
     */
    public function getEndTime()
    {
        return $this->end_time;
    }

    /**
     * @param \DateTime $end_time
     */
    public function setEndTime($end_time)
    {
        $this->end_time = $end_time;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return mixed
     */
    public function getCanceled()
    {
        return $this->canceled;
    }

    /**
     * @param mixed $canceled
     */
    public function setCanceled($canceled)
    {
        $this->canceled = $canceled;
    }

    /**
     * @return mixed
     */
    public function getCancelerName()
    {
        return $this->canceler_name;
    }

    /**
     * @param mixed $canceler_name
     */
    public function setCancelerName($canceler_name)
    {
        $this->canceler_name = $canceler_name;
    }

    /**
     * @return mixed
     */
    public function getCancelReason()
    {
        return $this->cancel_reason;
    }

    /**
     * @param mixed $cancel_reason
     */
    public function setCancelReason($cancel_reason)
    {
        $this->cancel_reason = $cancel_reason;
    }

    /**
     * @return mixed
     */
    public function getInviteeName()
    {
        return $this->invitee_name;
    }

    /**
     * @param mixed $invitee_name
     */
    public function setInviteeName($invitee_name)
    {
        $this->invitee_name = $invitee_name;
    }

    /**
     * @return mixed
     */
    public function getInviteeEmail()
    {
        return $this->invitee_email;
    }

    /**
     * @param mixed $invitee_email
     */
    public function setInviteeEmail($invitee_email)
    {
        $this->invitee_email = $invitee_email;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param mixed $uuid
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
    }
}