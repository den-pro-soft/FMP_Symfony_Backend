<?php

namespace RestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Vich\Uploadable
 * @ORM\Table(name="authors")
 * @ORM\Entity()
 */
class Author
{
    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\Length(
     *      min = 5,
     *      max = 50,
     *      minMessage = "The name must be at least {{ limit }} characters long",
     *      maxMessage = "The name cannot be longer than {{ limit }} characters"
     * )
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank()
     */
    protected $author_description;

    /**
     * @Assert\Image(maxSize="2M", mimeTypes={"image/png", "image/jpeg"})
     * @Vich\UploadableField(mapping="avatar", fileNameProperty="avatar_name")
     * @var File $image
     */
    protected $avatar;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $avatar_alt;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $avatar_name;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\Length(
     *      min = 5,
     *      max = 150,
     *      minMessage = "The link must be at least {{ limit }} characters long",
     *      maxMessage = "The link cannot be longer than {{ limit }} characters"
     * )
     */
    protected $link;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $date_updated;

    /**
     * @ORM\OneToOne(targetEntity="Blog", mappedBy="author")
     */
    protected $blog;

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
    public function getDescription()
    {
        return $this->author_description;
    }

    /**
     * @param mixed $author_description
     */
    public function setDescription($author_description)
    {
        $this->author_description = $author_description;
    }

    /**
     * @return File
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param File $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        if ($avatar instanceof UploadedFile) {
            $this->setDateUpdated(new \DateTime());
        }
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
    public function getDateUpdated()
    {
        return $this->date_updated;
    }

    /**
     * @param mixed $date_updated
     */
    public function setDateUpdated($date_updated)
    {
        $this->date_updated = $date_updated;
    }

    /**
     * @return mixed
     */
    public function getAvatarAlt()
    {
        return $this->avatar_alt;
    }

    /**
     * @param mixed $avatar_alt
     */
    public function setAvatarAlt($avatar_alt)
    {
        $this->avatar_alt = $avatar_alt;
    }
}