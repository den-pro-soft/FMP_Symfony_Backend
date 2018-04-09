<?php

namespace RestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Vich\Uploadable
 * @ORM\Table(name="documents")
 * @ORM\Entity()
 * @Serializer\ExclusionPolicy("all")
 */
class Document
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Serializer\Expose()
     * @Serializer\Groups({"profile"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Serializer\Expose()
     * @Serializer\Groups({"profile"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     * @Serializer\Expose()
     * @Serializer\Groups({"profile"})
     */
    private $type = 'Resume';

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Serializer\Expose()
     * @Serializer\Groups({"profile"})
     */
    private $addedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=false)
     * @Serializer\Expose()
     * @Serializer\Groups({"profile"})
     */
    private $dateAdded;

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
     * @Vich\UploadableField(mapping="document", fileNameProperty="path")
     * @var File $document
     * @Serializer\Expose()
     * @Serializer\Groups({"profile"})
     */
    private $document;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=false)
     */
    private $path;

    /**
     * @ORM\ManyToOne(targetEntity="RestBundle\Entity\Profile", inversedBy="documents")
     */
    private $profile;

    /**
     * __construct
     */
    public function __construct()
    {
        $this->dateAdded = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getAddedBy()
    {
        return $this->addedBy;
    }

    /**
     * @param string $addedBy
     */
    public function setAddedBy($addedBy)
    {
        $this->addedBy = $addedBy;
    }

    /**
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * @param \DateTime $dateAdded
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
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

    /**
     * @return mixed
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @param mixed $document
     */
    public function setDocument($document)
    {
        $this->document = $document;
    }
}
