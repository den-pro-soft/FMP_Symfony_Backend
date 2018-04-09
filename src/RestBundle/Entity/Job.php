<?php

namespace RestBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Vich\Uploadable
 * @ORM\Table(name="jobs")
 * @ORM\Entity(repositoryClass="RestBundle\Entity\Repository\JobRepository")
 * @ExclusionPolicy("all")
 */
class Job
{
    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose()
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="RestBundle\Entity\User", inversedBy="jobs")
     */
    protected $user;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Expose()
     */
    protected $link;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Expose()
     */
    protected $jobdescription;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Expose()
     */
    protected $company;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Expose()
     */
    protected $position;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default" = "N/A"})
     * @Expose()
     */
    protected $rate;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Expose()
     */
    protected $status = 'Pending';

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Expose()
     */
    protected $section = 'liked';

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Expose()
     */
    protected $addedBy = 'user';

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @Expose()
     */
    protected $date;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Expose()
     */
    protected $appliedDate;

    /**
     * @Expose()
     */
    public $username;

    /**
     * @Assert\File(maxSize="10M", mimeTypes={
     *     "application/pdf",
     *     "application/msword",
     *     "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
     *     "application/rtf",
     *     "text/rtf",
     *     "text/plain",
     *     "image/png",
     *     "image/jpeg"
     * },
     *     mimeTypesMessage="Available file types pdf, doc(x), rtf, txt, png, jpg, jpeg.",
     *     maxSizeMessage="The file can not be larger than 10 Mb.")
     * @Vich\UploadableField(mapping="document", fileNameProperty="attachment_name")
     * @var File $template
     * @Expose()
     */
    protected $attachment;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $attachment_name;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Expose()
     * @Serializer\SerializedName("attachment_name")
     */
    protected $attachment_alias;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_new = true;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_new_admin = true;

    /**
     * @ORM\Column(type="boolean")
     * @Expose()
     */
    protected $checked = false;

    /**
     * @ORM\OneToMany(targetEntity="RestBundle\Entity\Contact", mappedBy="job", cascade={"remove", "persist"})
     * @ORM\JoinColumn(name="contact_id", referencedColumnName="id")
     */
    protected $contacts;

    public function __construct()
    {
        $this->date = new \DateTime();
        $this->contacts = new ArrayCollection();
    }

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
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param string $rate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
    }

    /**
     * @return string
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param $status
     * @param bool $check
     */
    public function setStatus($status, $check = false)
    {
        if (in_array($status, ['Applied', 'Need Info', 'Ready'], true)) {
            if ($check) {
                $this->setIsNew(true);
            } else {
                $this->setIsNewAdmin(true);
            }
        }

        $this->status = $status;
    }

    /**
     * @return File
     */
    public function getAttachment()
    {
        return $this->attachment;
    }

    /**
     * @param File $attachment
     */
    public function setAttachment($attachment)
    {
        $this->attachment = $attachment;
    }

    /**
     * @return mixed
     */
    public function getAttachmentName()
    {
        return $this->attachment_name;
    }

    /**
     * @param mixed $attachment_name
     */
    public function setAttachmentName($attachment_name)
    {
        $this->attachment_name = $attachment_name;
    }

    /**
     * @return mixed
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @param mixed $section
     */
    public function setSection($section)
    {
        $this->section = $section;
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

    /**
     * @return mixed
     */
    public function getAddedBy()
    {
        return $this->addedBy;
    }

    /**
     * @param mixed $addedBy
     */
    public function setAddedBy($addedBy)
    {
        $this->addedBy = $addedBy;
    }

    /**
     * @return mixed
     */
    public function getAttachmentAlias()
    {
        return $this->attachment_alias;
    }

    /**
     * @param mixed $attachment_alias
     */
    public function setAttachmentAlias($attachment_alias)
    {
        $this->attachment_alias = $attachment_alias;
    }

    /**
     * @return mixed
     */
    public function getJobdescription()
    {
        return $this->jobdescription;
    }

    /**
     * @param mixed $jobdescription
     */
    public function setJobdescription($jobdescription)
    {
        $this->jobdescription = $jobdescription;
    }

    /**
     * @return mixed
     */
    public function getIsNew()
    {
        return $this->is_new;
    }

    /**
     * @param mixed $is_new
     */
    public function setIsNew($is_new)
    {
        $this->is_new = $is_new;
    }

    /**
     * @return mixed
     */
    public function getChecked()
    {
        return $this->checked;
    }

    /**
     * @param mixed $checked
     */
    public function setChecked($checked)
    {
        $this->checked = $checked;
    }

    /**
     * @return mixed
     */
    public function getIsNewAdmin()
    {
        return $this->is_new_admin;
    }

    /**
     * @param mixed $is_new_admin
     */
    public function setIsNewAdmin($is_new_admin)
    {
        $this->is_new_admin = $is_new_admin;
    }

    /**
     * setAppliedDate
     *
     * @param DateTime $date
     *
     * @return self
     */
    public function setAppliedDate(\DateTime $date)
    {
        $this->appliedDate = $date;

        return $this;
    }

    /**
     * getAppliedDate
     *
     * @return \DateTime|null
     */
    public function getAppliedDate()
    {
        return $this->appliedDate;
    }

    /**
     * getContacts
     * @return mixed
     */
    public function getContacts()
    {
        return $this->contacts->toArray();
    }



    
}