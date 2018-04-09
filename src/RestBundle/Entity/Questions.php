<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22.05.17
 * Time: 11:35
 */

namespace RestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Table(name="questions")
 * @ORM\Entity()
 * @Serializer\ExclusionPolicy("all")
 */
class Questions
{
    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Groups({"profile"})
     * @Serializer\Expose()
     */
    protected $work_authorization;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Groups({"profile"})
     * @Serializer\Expose()
     */
    protected $gender;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Groups({"profile"})
     * @Serializer\Expose()
     */
    protected $veteran_status;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Groups({"profile"})
     * @Serializer\Expose()
     */
    protected $disability_status;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Groups({"profile"})
     * @Serializer\Expose()
     */
    protected $race_ethnicity;

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
    public function getWorkAuthorization()
    {
        return $this->work_authorization;
    }

    /**
     * @param mixed $work_authorization
     */
    public function setWorkAuthorization($work_authorization)
    {
        $this->work_authorization = $work_authorization;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getVeteranStatus()
    {
        return $this->veteran_status;
    }

    /**
     * @param mixed $veteran_status
     */
    public function setVeteranStatus($veteran_status)
    {
        $this->veteran_status = $veteran_status;
    }

    /**
     * @return mixed
     */
    public function getDisabilityStatus()
    {
        return $this->disability_status;
    }

    /**
     * @param mixed $disability_status
     */
    public function setDisabilityStatus($disability_status)
    {
        $this->disability_status = $disability_status;
    }

    /**
     * @return mixed
     */
    public function getRaceEthnicity()
    {
        return $this->race_ethnicity;
    }

    /**
     * @param mixed $race_ethnicity
     */
    public function setRaceEthnicity($race_ethnicity)
    {
        $this->race_ethnicity = $race_ethnicity;
    }
}