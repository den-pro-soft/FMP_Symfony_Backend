<?php

namespace RestBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="career_preferences")
 * @ORM\Entity
 */
class CareerPreferences
{
    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $industry;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $job_titles;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $job_types;

    /**
     * @ORM\Column(type="boolean")
     */
    private $relocation_value = false;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $relocation_type;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $relocation_location;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $experience;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $education;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $desire_salary_value;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $desire_salary_type;


    public function __construct()
    {
        $this->job_titles = new ArrayCollection();
        $this->job_types = new ArrayCollection();
        $this->relocation_location = new ArrayCollection();
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
    public function getIndustry()
    {
        return $this->industry;
    }

    /**
     * @param mixed $industry
     */
    public function setIndustry($industry)
    {
        $this->industry = $industry;
    }

    /**
     * @return mixed
     */
    public function getJobTypes()
    {
        return $this->job_types instanceof ArrayCollection ? $this->job_types->toArray() : $this->job_types;
    }

    /**
     * @param mixed $job_types
     */
    public function setJobTypes($job_types)
    {
        $this->job_types = $job_types;
    }

    /**
     * @return mixed
     */
    public function getRelocationValue()
    {
        return $this->relocation_value;
    }

    /**
     * @param mixed $relocation_value
     */
    public function setRelocationValue($relocation_value)
    {
        $this->relocation_value = $relocation_value;
    }

    /**
     * @return mixed
     */
    public function getExperience()
    {
        return $this->experience;
    }

    /**
     * @param mixed $experience
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;
    }

    /**
     * @return mixed
     */
    public function getEducation()
    {
        return $this->education;
    }

    /**
     * @param mixed $education
     */
    public function setEducation($education)
    {
        $this->education = $education;
    }

    /**
     * @return mixed
     */
    public function getRelocationType()
    {
        return $this->relocation_type;
    }

    /**
     * @param mixed $relocation_type
     */
    public function setRelocationType($relocation_type)
    {
        $this->relocation_type = $relocation_type;
    }

    /**
     * @return mixed
     */
    public function getDesireSalaryValue()
    {
        return $this->desire_salary_value;
    }

    /**
     * @param mixed $desire_salary_value
     */
    public function setDesireSalaryValue($desire_salary_value)
    {
        $this->desire_salary_value = $desire_salary_value;
    }

    /**
     * @return mixed
     */
    public function getDesireSalaryType()
    {
        return $this->desire_salary_type;
    }

    /**
     * @param mixed $desire_salary_type
     */
    public function setDesireSalaryType($desire_salary_type)
    {
        $this->desire_salary_type = $desire_salary_type;
    }

    /**
     * @return mixed
     */
    public function getRelocationLocation()
    {
        return $this->relocation_location instanceof ArrayCollection ? $this->relocation_location->toArray() : $this->relocation_location;
    }

    /**
     * @param mixed $relocation_location
     */
    public function setRelocationLocation($relocation_location)
    {
        $this->relocation_location = $relocation_location;
    }

    /**
     * @return mixed
     */
    public function getJobTitles()
    {
        return $this->job_titles instanceof ArrayCollection ? $this->job_titles->toArray() : $this->job_titles;
    }

    /**
     * @param mixed $job_titles
     */
    public function setJobTitles($job_titles)
    {
        $this->job_titles = $job_titles;
    }
}
