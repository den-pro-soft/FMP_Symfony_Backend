<?php

namespace RestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @Vich\Uploadable
 * @ORM\Table(name="resources")
 * @ORM\Entity(repositoryClass="RestBundle\Entity\Repository\TestimonialRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class Resource
{
    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Serializer\Expose()
     * @Serializer\Groups({"resource"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Serializer\Expose()
     * @Serializer\Groups({"resource"})
     */
    protected $name;

    /**
     * @Assert\File(maxSize="10M", mimeTypes={
     *     "application/pdf",
     *     "application/msword",
     *     "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
     *     "application/rtf",
     *     "text/rtf",
     *     "text/plain"
     * }, mimeTypesMessage="Please add a valid document")
     * @Vich\UploadableField(mapping="resource", fileNameProperty="resource_name")
     * @var File $resource
     * @Serializer\Expose()
     * @Serializer\Groups({"resource"})
     */
    protected $resource;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $resource_name;

    /**
     * @Assert\Image(maxSize="2M", mimeTypes={"image/png", "image/jpeg"}, mimeTypesMessage="Please add a valid image")
     * @Vich\UploadableField(mapping="resource_preview", fileNameProperty="preview_name")
     * @var File $resource
     * @Serializer\Expose()
     * @Serializer\Groups({"resource"})
     */
    protected $preview;

    /**
     * @ORM\Column(type="string", nullable=false) 
     */
    protected $preview_name;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Serializer\Expose()
     * @Serializer\Groups({"resource"})
     */
    protected $type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Serializer\Expose()
     * @Serializer\Groups({"resource"})
     */
    protected $sort;

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
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param mixed $resource
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    /**
     * @return mixed
     */
    public function getResourceName()
    {
        return $this->resource_name;
    }

    /**
     * @param mixed $resource_name
     */
    public function setResourceName($resource_name)
    {
        $this->resource_name = $resource_name;
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
     * @return File
     */
    public function getPreview()
    {
        return $this->preview;
    }

    /**
     * @param File $preview
     */
    public function setPreview($preview)
    {
        $this->preview = $preview;
    }

    /**
     * @return mixed
     */
    public function getPreviewName()
    {
        return $this->preview_name;
    }

    /**
     * @param mixed $preview_name
     */
    public function setPreviewName($preview_name)
    {
        $this->preview_name = $preview_name;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param mixed $sort
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    }

}