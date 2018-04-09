<?php

namespace RestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Vich\Uploadable
 * @ORM\Table(name="pages")
 * @ORM\Entity(repositoryClass="RestBundle\Entity\Repository\PageRepository")
 */
class Page
{
    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $title;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $seo_title;

    /**
     * @var text
     * @ORM\Column(type="text", nullable=false)
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $content;

    /**
     * @Assert\Image(maxSize="2M", mimeTypes={"image/png", "image/jpeg"})
     * @Vich\UploadableField(mapping="image", fileNameProperty="image_name")
     * @var File $image
     */
    private $image;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $image_name;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $post_date;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $slug;

    public function __construct()
    {
        $this->post_date = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return \DateTime
     */
    public function getPostDate()
    {
        return $this->post_date;
    }

    /**
     * @param \DateTime $post_date
     */
    public function setPostDate($post_date)
    {
        $this->post_date = $post_date;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getSeoTitle()
    {
        return $this->seo_title;
    }

    /**
     * @param mixed $seo_title
     */
    public function setSeoTitle($seo_title)
    {
        $this->seo_title = $seo_title;
    }

    /**
     * @return mixed
     */
    public function getImageName()
    {
        return $this->image_name;
    }

    /**
     * @param mixed $image_name
     */
    public function setImageName($image_name)
    {
        $this->image_name = $image_name;
    }
}
