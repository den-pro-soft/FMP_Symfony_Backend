<?php

namespace RestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Vich\Uploadable
 * @ORM\Table(name="testimonials")
 * @ORM\Entity(repositoryClass="RestBundle\Entity\Repository\TestimonialRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class Testimonial
{
    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "The title must be at least {{ limit }} characters long",
     *      maxMessage = "The title cannot be longer than {{ limit }} characters"
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=false, length=1000)
     * @Serializer\Expose()
     * @Assert\Length(
     *      min = 10,
     *      max = 1000,
     *      minMessage = "The detail must be at least {{ limit }} characters long",
     *      maxMessage = "The detail cannot be longer than {{ limit }} characters"
     * )
     */
    private $detail;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @Serializer\Expose()
     * @Assert\NotBlank()
     */
    private $rating;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Serializer\Expose()
     * @Assert\Length(
     *      min = 2,
     *      max = 20,
     *      minMessage = "The name must be at least {{ limit }} characters long",
     *      maxMessage = "The name cannot be longer than {{ limit }} characters"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @Serializer\Expose()
     * @Assert\NotBlank()
     */
    private $age;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Serializer\Expose()
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "The industry must be at least {{ limit }} characters long",
     *      maxMessage = "The industry cannot be longer than {{ limit }} characters"
     * )
     */
    private $industry;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @Serializer\Expose()
     */
    private $date;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $status = true;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $deleted = false;

    /**
     * @ORM\ManyToOne(targetEntity="RestBundle\Entity\Service")
     * @ORM\JoinColumn(name="service_id", referencedColumnName="id")
     * @Serializer\Expose()
     */
    private $service;

    /**
     * @Assert\Image(maxSize="2M", mimeTypes={"image/png", "image/jpeg"})
     * @Vich\UploadableField(mapping="avatar", fileNameProperty="avatar_name")
     * @var File $avatar
     * @Serializer\Expose()
     */
    protected $avatar;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $avatar_name;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $on_homepage;

    /**
     * Testimonial constructor.
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
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * @param mixed $detail
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;
    }

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param mixed $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
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
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param mixed $age
     */
    public function setAge($age)
    {
        $this->age = $age;
    }

    /**
     * @return mixed
     */
    public function getIndustry()
    {
        return $this->industry;
    }

    /**
     * @param mixed $industry
     */
    public function setIndustry($industry)
    {
        $this->industry = $industry;
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
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param mixed $deleted
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param mixed $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * @return mixed
     */
    public function getAvatarName()
    {
        return $this->avatar_name;
    }

    /**
     * @param mixed $avatar_name
     */
    public function setAvatarName($avatar_name)
    {
        $this->avatar_name = $avatar_name;
    }

    /**
     * @return mixed
     */
    public function getOnHomepage()
    {
        return $this->on_homepage;
    }

    /**
     * @param mixed $on_homepage
     */
    public function setOnHomepage($on_homepage)
    {
        $this->on_homepage = $on_homepage;
    }
}
