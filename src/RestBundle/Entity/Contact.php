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
 * @ORM\Table(name="contacts")
 * @ORM\Entity(repositoryClass="RestBundle\Entity\Repository\ContactRepository")
 * @ExclusionPolicy("all")
 */

class Contact
{
    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose()
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="RestBundle\Entity\Job", inversedBy="contacts")
     */
    protected $job;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Expose()
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Expose()
     */
    protected $title;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Expose()
     */
    protected $link;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Expose()
     */
    protected $note;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Expose()
     */
    protected $status = 'Messaged';

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @Expose()
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @Expose()
     */
    protected $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="RestBundle\Entity\Attempt", mappedBy="contact", cascade={"remove", "persist"})
     * @ORM\JoinColumn(name="attempt_id", referencedColumnName="id")
     */
    protected $attempts;

    /**
     * Contact constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->attempts = new ArrayCollection();
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
    public function getJob()
    {
        return $this->job;
    }

    /**
     * @param mixed $job
     */
    public function setJob($job)
    {
        $this->job = $job;
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param mixed $note
     */
    public function setNote($note)
    {
        $this->note = $note;
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
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

}