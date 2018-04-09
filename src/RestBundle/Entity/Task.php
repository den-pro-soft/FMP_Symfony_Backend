<?php

namespace RestBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\HttpFoundation\File\File;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Vich\Uploadable
 * @ORM\Table(name="tasks")
 * @ORM\Entity(repositoryClass="RestBundle\Entity\Repository\TaskRepository")
 * @ExclusionPolicy("all")
 */

class Task
{
    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose()
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="RestBundle\Entity\User", inversedBy="tasks")
     */
    protected $user;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @Expose()
     */
    protected $job_id;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @Expose()
     */
    protected $contact_id;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Expose()
     */
    protected $name;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Expose()
     */
    protected $due_date;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Expose()
     */
    protected $completed;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Expose()
     */
    protected $completed_at;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Expose()
     */
    protected $completed_by;

    /**
     * Task constructor.
     */
    public function __construct()
    {
        $this->job_id = 0;
        $this->contact_id = 0;
        $this->name = 0;
        $this->completed = "no";
        $this->completed_by = 0;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getJobId()
    {
        return $this->job_id;
    }

    /**
     * @param mixed $job_id
     */
    public function setJobId($job_id)
    {
        $this->job_id = $job_id;
    }

    /**
     * @return mixed
     */
    public function getContactId()
    {
        return $this->contact_id;
    }

    /**
     * @param mixed $contact_id
     */
    public function setContactId($contact_id)
    {
        $this->contact_id = $contact_id;
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
    public function getDueDate()
    {
        return $this->due_date;
    }

    /**
     * @param mixed $due_date
     */
    public function setDueDate($due_date)
    {
        $this->due_date = $due_date;
    }

    /**
     * @return mixed
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * @param mixed $completed
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;
    }

    /**
     * @return mixed
     */
    public function getCompletedAt()
    {
        return $this->completed_at;
    }

    /**
     * @param mixed $completed_at
     */
    public function setCompletedAt($completed_at)
    {
        $this->completed_at = $completed_at;
    }

    /**
     * @return mixed
     */
    public function getCompletedBy()
    {
        return $this->completed_by;
    }

    /**
     * @param mixed $completed_by
     */
    public function setCompletedBy($completed_by)
    {
        $this->completed_by = $completed_by;
    }
}