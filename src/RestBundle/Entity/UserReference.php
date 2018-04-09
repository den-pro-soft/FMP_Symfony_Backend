<?php

namespace RestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Table(name="user_references")
 * @ORM\Entity()
 * @Serializer\ExclusionPolicy("all")
 */
class UserReference
{
    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Expose()
     * @Serializer\Groups({"profile"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Expose()
     * @Serializer\Groups({"profile"})
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Expose()
     * @Serializer\Groups({"profile"})
     */
    protected $job_title;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Expose()
     * @Serializer\Groups({"profile"})
     */
    protected $company;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Expose()
     * @Serializer\Groups({"profile"})
     */
    protected $email;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Expose()
     * @Serializer\Groups({"profile"})
     */
    protected $phone_number;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Expose()
     * @Serializer\Groups({"profile"})
     */
    protected $relationship;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Serializer\Expose()
     * @Serializer\Groups({"profile"})
     */
    protected $reference_text;

    /**
     * @ORM\ManyToOne(targetEntity="RestBundle\Entity\Profile", inversedBy="user_reference")
     */
    protected $profile;

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
    public function getJobTitle()
    {
        return $this->job_title;
    }

    /**
     * @param mixed $job_title
     */
    public function setJobTitle($job_title)
    {
        $this->job_title = $job_title;
    }

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param mixed $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phone_number;
    }

    /**
     * @param mixed $phone_number
     */
    public function setPhoneNumber($phone_number)
    {
        $this->phone_number = $phone_number;
    }

    /**
     * @return mixed
     */
    public function getRelationship()
    {
        return $this->relationship;
    }

    /**
     * @param mixed $relationship
     */
    public function setRelationship($relationship)
    {
        $this->relationship = $relationship;
    }

    /**
     * @return mixed
     */
    public function getReferenceText()
    {
        return $this->reference_text;
    }

    /**
     * @param mixed $reference_text
     */
    public function setReferenceText($reference_text)
    {
        $this->reference_text = $reference_text;
    }

    /**
     * @return mixed
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param mixed $profile
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;
    }

    public function set($name, $value)
    {
        if (!property_exists($this, $name)) {
            return false;
        }

        $this->$name = $value;

        return true;
    }
}