<?php

namespace RestBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Vich\Uploadable
 * @ORM\Table(name="blog")
 * @ORM\Entity(repositoryClass="RestBundle\Entity\Repository\BlogRepository")
 */
class Blog
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
     *      min = 5,
     *      max = 150,
     *      minMessage = "The title must be at least {{ limit }} characters long",
     *      maxMessage = "The title cannot be longer than {{ limit }} characters"
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\Length(
     *      min = 5,
     *      max = 150,
     *      minMessage = "The SEO title must be at least {{ limit }} characters long",
     *      maxMessage = "The SEO title cannot be longer than {{ limit }} characters"
     * )
     */
    private $seo_title;

    /**
     * @var text
     * @ORM\Column(type="text", nullable=false)
     * @Assert\Length(
     *      min = 5,
     *      minMessage = "The SEO description must be at least {{ limit }} characters long"
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=false)
     * @Assert\Length(
     *      min = 5,
     *      minMessage = "The content must be at least {{ limit }} characters long"
     * )
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
     * @ORM\Column(type="string", nullable=true)
     */
    private $image_alt;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $post_date;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $url;

    /**
     * @ORM\OneToOne(targetEntity="RestBundle\Entity\Author", cascade={"remove", "persist"}, inversedBy="blog")
     * @ORM\JoinColumn(name="author", referencedColumnName="id")
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity="RestBundle\Entity\Category", inversedBy="articles", cascade={"remove", "persist"})
     * @ORM\JoinColumn(name="category", referencedColumnName="id")
     */
    private $category;
 

    /**
     * @ORM\ManyToOne(targetEntity="RestBundle\Entity\User", inversedBy="blogs")
     * @ORM\JoinColumn(name="admin_id", referencedColumnName="id", nullable=true)
     */
    private $admin;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_updated;

    /**
     * @ORM\Column(type="integer")
     */
    private $views = 0;

    /**
     * @ORM\Column(type="string")
     */
    private $status = 'Draft';

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRemoved;

    /**
     * @var
     */
    private $tops;

    /**
     * @var
     */
    private $random_blogs;

    /**
     * @var bool
     */
    private $liked = false;

    public function __construct()
    {
        $this->post_date = new \DateTime();
        $this->category = new ArrayCollection();
        $this->isRemoved = false;
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

        if ($image) {
            $this->date_updated = new \DateTime();
        }
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
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function addUrl()
    {
        $this->url = str_replace(' ', '-', strtolower($this->getTitle()));
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
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

    /**
     * @return mixed
     */
    public function getTops()
    {
        return $this->tops;
    }

    /**
     * @param mixed $tops
     */
    public function setTops($tops)
    {
        $this->tops = $tops;
    }

    /**
     * @return mixed
     */
    public function getRandomBlogs()
    {
        return $this->random_blogs;
    }

    /**
     * @param mixed $random_blogs
     */
    public function setRandomBlogs($random_blogs)
    {
        $this->random_blogs = $random_blogs;
    }

    /**
     * @return mixed
     */
    public function getLiked()
    {
        return $this->liked;
    }

    /**
     * @param mixed $liked
     */
    public function setLiked($liked)
    {
        $this->liked = $liked;
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
     * @param mixed $category
     */
    public function addCategory($category)
    {
        if (!in_array($category, $this->category->toArray(), true)) {
            $this->category[] = $category;
        }
    }

    /**
     * @return mixed
     */
    public function getImageAlt()
    {
        return $this->image_alt;
    }

    /**
     * @param mixed $image_alt
     */
    public function setImageAlt($image_alt)
    {
        $this->image_alt = $image_alt;
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
     * @return int
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * @param int $views
     */
    public function setViews($views)
    {
        $this->views = $views;
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

    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * setAdmin
     *
     * @param User $user
     *
     * @return self
     */
    public function setAdmin(User $admin)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * remove
     *
     * @return self
     */
    public function remove()
    {
        $this->isRemoved = true;

        return $this;
    }
}
