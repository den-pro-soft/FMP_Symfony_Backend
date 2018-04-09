<?php

namespace RestBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="discounts")
 * @ORM\Entity(repositoryClass="RestBundle\Entity\Repository\DiscountRepository")
 * @Serializer\ExclusionPolicy("all")
 * @UniqueEntity("code")
 */
class Discount
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
     *      minMessage = "The code must be at least {{ limit }} characters long",
     *      maxMessage = "The code cannot be longer than {{ limit }} characters"
     * )
     */
    protected $code;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Expose()
     */
    protected $type = 'fixed';

    /**
     * @ORM\Column(type="integer")
     * @Serializer\Expose()
     */
    protected $value = 0;

    /**
     * @ORM\ManyToMany(targetEntity="RestBundle\Entity\Service", inversedBy="discounts")
     * @ORM\JoinColumn(name="category", referencedColumnName="id")
     * @Serializer\Expose()
     */
    private $services;

    /**
     * @ORM\Column(type="integer")
     */
    private $count = 1;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     */
    protected $date_from;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     */
    protected $date_undo;

    public function __construct()
    {
        $this->services = new ArrayCollection();
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
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
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
     * @return \DateTime
     */
    public function getDateFrom()
    {
        return $this->date_from;
    }

    /**
     * @param \DateTime $date_from
     */
    public function setDateFrom($date_from)
    {
        $this->date_from = $date_from;
    }

    /**
     * @return \DateTime
     */
    public function getDateUndo()
    {
        return $this->date_undo;
    }

    /**
     * @param \DateTime $date_undo
     */
    public function setDateUndo($date_undo)
    {
        $this->date_undo = $date_undo;
    }

    /**
     * @return mixed
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param mixed $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * @return mixed
     */
    public function getServices()
    {
        return $this->services->toArray();
    }

    /**
     * @param mixed $services
     */
    public function setServices($services)
    {
        $this->services = $services;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}