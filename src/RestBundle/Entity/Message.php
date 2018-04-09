<?php
namespace RestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass="RestBundle\Entity\Repository\MessageRepository")
 * @ORM\Table(name="messages")
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $author;

    /**
     * @ORM\Column(type="integer")
     */
    protected $owner;

    /**
     * @ORM\Column(type="integer" , options={"default" : 0})
     */
    protected $edited = 0;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $message;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $date;

    /**
     * @ORM\ManyToMany(targetEntity="User")
     * @ORM\JoinTable(name="unread_users",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="message_id", referencedColumnName="id")}
     *      )
     */
    private $unread_users;
 


    /**
     * @Assert\File(maxSize="10M",
     *     mimeTypes={
     *     "application/pdf",
     *     "application/msword",
     *     "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
     *     "application/rtf",
     *     "text/rtf",
     *     "text/plain",
     *     "image/png",
     *     "image/jpeg"
     * },
     *     mimeTypesMessage="Available file types pdf, doc(x), rtf, txt, png, jpg, jpeg.",
     *     maxSizeMessage="The file can not be larger than 10 Mb.")
     * @Vich\UploadableField(mapping="document", fileNameProperty="attachment_path")
     */
    protected $attachment;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $attachment_path;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $attachment_name;

 

    /**
     * @ORM\Column(type="integer")
     *
     * 1 - admin
     * 2 - user
     */
    protected $type_sender;

    protected $username;
  
    protected $sendername;

    public function __construct()
    {
        $this->date = new \DateTime();
        $this->unread_users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param mixed $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return mixed
     */
    public function getEdited()
    {
        return $this->edited;
    }

    /**
     * @param mixed $edited
     */
    public function setEdited($edited)
    {
        $this->edited = $edited;
    }

     /**
     * @return mixed
     */
    public function getRecipients()
    {
        if( $this->author->getRole() == 'ROLE_USER' )
        {
            return $this->author->getAdmins();
        }
        else
        {   
            $recipients = new ArrayCollection();
            $recipients->add( $this->owner );
            return $this->owner;
        }
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }
 
    /**
     * @return mixed
     */
    public function getAttachment()
    {
        return $this->attachment;
    }

    /**
     * @param File $attachment
     */
    public function setAttachment($attachment)
    {
        $this->attachment = $attachment;
    }

    /**
     * @return mixed
     */
    public function getAttachmentPath()
    {
        return $this->attachment_path;
    }

    /**
     * @param mixed $attachment_path
     */
    public function setAttachmentPath($attachment_path)
    {
        $this->attachment_path = $attachment_path;
    }

    /**
     * @return mixed
     */
    public function getAttachmentName()
    {
        return $this->attachment_name;
    }

    /**
     * @param mixed $attachment_name
     */
    public function setAttachmentName($attachment_name)
    {
        $this->attachment_name = $attachment_name;
    }

    /**
     * @return mixed
     */
    public function getTypeSender()
    {
        return $this->type_sender;
    }

    /**
     * @param mixed $type_sender
     */
    public function setTypeSender($type_sender)
    {
        $this->type_sender = $type_sender;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

  
    /**
     * @return mixed
     */
    public function getSenderName()
    {
        return $this->sendername;
    }

    /**
     * @param mixed $sendername
     */
    public function setSenderName($sendername)
    {
        $this->sendername = $sendername;
    }
 
    /**
     * @return mixed
     */
    public function getUnreadUsers()
    {
        return $this->unread_users;
    }


    /**
     * @param mixed $unread_users
     */
    public function setUnreadUsers($unread_users)
    {
        $this->unread_users = $unread_users;
    }

    /**
     * @param mixed $user
     */
    public function addUnreadUser($user)
    {
        $this->unread_users->add( $user );
        return $this->unread_users;
    }

    /**
     * @param mixed $user
     */
    public function removeUnreadUser($user)
    {
        $this->unread_users->removeElement( $user );
        return $this->unread_users;
    }
 
 
}