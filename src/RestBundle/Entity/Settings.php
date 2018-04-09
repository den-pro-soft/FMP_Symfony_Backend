<?php

namespace RestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="settings")
 * @ORM\Entity()
 */
class Settings
{
    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     */
    protected $id;

    /**
     * @ORM\Column(type="text", nullable=false)
     * @Assert\Length(
     *      min = 5,
     *      max = 1000,
     *      minMessage = "The congratulation message must be at least {{ limit }} characters long",
     *      maxMessage = "The congratulation message cannot be longer than {{ limit }} characters"
     * )
     */
    protected $congratulation_message;

    /**
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
    public function getCongratulationMessage()
    {
        return $this->congratulation_message;
    }

    /**
     * @param mixed $congratulation_message
     */
    public function setCongratulationMessage($congratulation_message)
    {
        $this->congratulation_message = $congratulation_message;
    }

}