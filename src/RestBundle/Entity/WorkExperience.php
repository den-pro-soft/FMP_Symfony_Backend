<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.05.17
 * Time: 15:19
 */

namespace RestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Table(name="work_experiences")
 * @ORM\Entity()
 * @Serializer\ExclusionPolicy("all")
 */
class WorkExperience
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
    protected $employer;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Expose()
     * @Serializer\Groups({"profile"})
     */
    protected $job_title;

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
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Expose()
     * @Serializer\Groups({"profile"})
     */
    protected $salary_earned;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Expose()
     * @Serializer\Groups({"profile"})
     */
    protected $reason_for_leaving;

    /**
     * @ORM\ManyToOne(targetEntity="RestBundle\Entity\Profile", inversedBy="work_experience")
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
    public function getEmployer()
    {
        return $this->employer;
    }

    /**
     * @param mixed $employer
     */
    public function setEmployer($employer)
    {
        $this->employer = $employer;
    }

    /**
     * @return mixed
     */
    public function getJobTitle()
    {
        return $this->job_title;
    }

    /**
     * @param mixed $job_title
     */
    public function setJobTitle($job_title)
    {
        $this->job_title = $job_title;
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
    public function getSalaryEarned()
    {
        return $this->salary_earned;
    }

    /**
     * @param mixed $salary_earned
     */
    public function setSalaryEarned($salary_earned)
    {
        $this->salary_earned = $salary_earned;
    }

    /**
     * @return mixed
     */
    public function getReasonForLeaving()
    {
        return $this->reason_for_leaving;
    }

    /**
     * @param mixed $reason_for_leaving
     */
    public function setReasonForLeaving($reason_for_leaving)
    {
        $this->reason_for_leaving = $reason_for_leaving;
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