<?php

namespace RestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraint as Assert;

/**
 * @Vich\Uploadable
 * @ORM\Table(name="attempts")
 * @ORM\Entity(repositoryClass="RestBundle\Entity\Repository\AttemptRepository")
 * @ExclusionPolicy("all")
 */
class Attempt
{
    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose()
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="RestBundle\Entity\Contact", inversedBy="attempts")
     */
    protected $contact;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Expose()
     */
    protected $note;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Expose()
     */
    protected $status = "Messaged_1";

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @Expose()
     */
    protected $date;

    /**
     * Attempt constructor.
     */
    public function __construct()
    {
        $this->date = new \DateTime();
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
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @param mixed $contact
     */
    public function setContact($contact)
    {
        $this->contact = $contact;
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
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

}