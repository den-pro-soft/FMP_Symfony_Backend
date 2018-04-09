<?php
/**
 * Created by LiuWebDev
 */

namespace RestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Vich\Uploadable
 * @ORM\Table(name="resume_sample")
 * @ORM\Entity(repositoryClass="RestBundle\Entity\Repository\ResumeSampleRepository")
 */
class ResumeSample
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
     *     min = 1,
     *     max = 150,
     *     minMessage = "The title must be at least {{ limit }} characters long",
     *     maxMessage = "The title cannot be longer than {{ limit }} characters"
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $url;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $category = 'senior';

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $status = 'Draft';

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
     * @Assert\File(mimeTypes={"application/pdf"})
     * @Vich\UploadableField(mapping="template", fileNameProperty="pdf_name")
     * @var File $pdf
     */
    private $pdf;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $pdf_name;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $updated_at;

    /**
     * ResumeSample constructor.
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
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
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function addUrl(){
        $this->url = str_replace(' ', '-', strtolower($this->getTitle()));
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
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
     * @return File
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param File $image
     * @return ResumeSample
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
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

    /**
     * @return File
     */
    public function getPdf()
    {
        return $this->pdf;
    }

    /**
     * @param File $pdf
     * @return ResumeSample
     */
    public function setPdf($pdf)
    {
        $this->pdf = $pdf;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPdfName()
    {
        return $this->pdf_name;
    }

    /**
     * @param mixed $pdf_name
     */
    public function setPdfName($pdf_name)
    {
        $this->pdf_name = $pdf_name;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param mixed $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }




}
