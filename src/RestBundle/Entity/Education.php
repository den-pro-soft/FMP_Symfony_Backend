<?php

namespace RestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Table(name="educations")
 * @ORM\Entity()
 * @Serializer\ExclusionPolicy("all")
 */
class Education
{
    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Expose()
     * @Serializer\Groups({"profile"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Expose()
     * @Serializer\Groups({"profile"})
     */
    protected $institution;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Expose()
     * @Serializer\Groups({"profile"})
     */
    protected $discipline;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Expose()
     * @Serializer\Groups({"profile"})
     */
    protected $level;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Expose()
     * @Serializer\Groups({"profile"})
     */
    protected $start_date;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Expose()
     * @Serializer\Groups({"profile"})
     */
    protected $end_date;

    /**
     * @ORM\ManyToOne(targetEntity="RestBundle\Entity\Profile", inversedBy="education")
     */
    protected $profile;

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
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
     * @param mixed $institution
     */
    public function setInstitution($institution)
    {
        $this->institution = $institution;
    }

    /**
     * @return mixed
     */
    public function getDiscipline()
    {
        return $this->discipline;
    }

    /**
     * @param mixed $discipline
     */
    public function setDiscipline($discipline)
    {
        $this->discipline = $discipline;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * @param mixed $start_date
     */
    public function setStartDate($start_date)
    {
        $this->start_date = $start_date;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * @param mixed $end_date
     */
    public function setEndDate($end_date)
    {
        $this->end_date = $end_date;
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

    public function set($name, $value)
    {
        if (!property_exists($this, $name)) {
            return false;
        }

        $this->$name = $value;

        return true;
    }
}