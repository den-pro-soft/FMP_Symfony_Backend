<?php

namespace RestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Table(name="services")
 * @ORM\Entity(repositoryClass="RestBundle\Entity\Repository\ServiceRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class Service
{
    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Serializer\Groups({"other_services"})
     * @Serializer\Expose()
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Serializer\Groups({"other_services"})
     * @Serializer\Expose()
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Serializer\Groups({"price_services"})
     * @Serializer\Expose()
     */
    private $price_senior;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Serializer\Groups({"price_services"})
     * @Serializer\Expose()
     */
    private $price_executive;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Serializer\Expose()
     */
    private $icon;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $description;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Serializer\Groups({"other_services"})
     * @Serializer\Expose()
     */
    private $link;

    /**
     * @ORM\OneToMany(targetEntity="RestBundle\Entity\UserPackages", mappedBy="service")
     */
    protected $packages;

    /**
     * @ORM\ManyToMany(targetEntity="RestBundle\Entity\Discount", mappedBy="services", cascade={"remove", "persist"})
     */
    protected $discounts;

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
    public function getPriceSenior()
    {
        return $this->price_senior;
    }

    /**
     * @param mixed $price_senior
     */
    public function setPriceSenior($price_senior)
    {
        $this->price_senior = $price_senior;
    }

    /**
     * @return mixed
     */
    public function getPriceExecutive()
    {
        return $this->price_executive;
    }

    /**
     * @param mixed $price_executive
     */
    public function setPriceExecutive($price_executive)
    {
        $this->price_executive = $price_executive;
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param mixed $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
}