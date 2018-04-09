<?php

namespace RestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Table(name="quotes")
 * @ORM\Entity(repositoryClass="RestBundle\Entity\Repository\QuotesRepository")
 */
class Quotes
{
    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Serializer\Expose()
     * @Serializer\Groups({"quote"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=false , options={"default":1})
     * @Serializer\Expose()
     * @Serializer\Groups({"quote"})
     */
    private $no;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Serializer\Expose()
     * @Serializer\Groups({"quote"})
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=false)
     * @Serializer\Expose()
     * @Serializer\Groups({"quote"})
     */
    private $content;

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
     * @return mixed
     */
    public function getNo()
    {
        return $this->no;
    }

    /**
     * @param string $no
     */
    public function setNo($no)
    {
        $this->no = $no;
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

}
