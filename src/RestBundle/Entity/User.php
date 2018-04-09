<?php
namespace RestBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Vich\Uploadable
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="RestBundle\Entity\Repository\UserRepository")
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 */
class User implements AdvancedUserInterface, EquatableInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotBlank()
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     * @Assert\NotBlank()
     */
    protected $full_name;

    /**
     * @ORM\Column(type="string")
     */
    protected $token;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_active;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $date_created;

    /**
     * @ORM\Column(type="array")
     */
    protected $roles = array();

    /**
     * @ORM\Column(type="string")
     */
    protected $role;

    /**
     * @ORM\OneToOne(targetEntity="RestBundle\Entity\Profile", mappedBy="user", cascade={"remove", "persist"})
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $profile;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $temporary_token;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isRemoved;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $timezone;

     /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $quotes_date;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $quote_checked;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $quotes_num;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $title;

    /** 
     * @ORM\ManyToMany(targetEntity="User", mappedBy="users")
     */
    protected $admins;

    /** 
     * @ORM\ManyToMany(targetEntity="User", inversedBy="admins")
     * @ORM\JoinTable(name="admin_user_relation",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="admin_id", referencedColumnName="id")}
     *      )
     */
    protected $users;

    /**
     * @ORM\OneToMany(targetEntity="RestBundle\Entity\UserPackages", mappedBy="user", cascade={"remove", "persist"})
     * @ORM\JoinColumn(name="service_id", referencedColumnName="id")
     */
    protected $packages;

    /**
     * @ORM\OneToMany(targetEntity="RestBundle\Entity\Job", mappedBy="user", cascade={"remove", "persist"})
     * @ORM\JoinColumn(name="job_id", referencedColumnName="id")
     */
    protected $jobs;

    /**
     * @ORM\ManyToMany(targetEntity="RestBundle\Entity\Blog")
     * @ORM\JoinTable(name="likes",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="blog_id", referencedColumnName="id")}
     *      )
     * @var Blog[]
     */
    private $likes;

    /**
     * @ORM\OneToMany(targetEntity="RestBundle\Entity\Blog", mappedBy="admin")
     */
    protected $blogs;

    /**
     * @ORM\OneToMany(targetEntity="RestBundle\Entity\Task", mappedBy="user", cascade={"remove", "persist"})
     * @ORM\JoinColumn(name="task_id", referencedColumnName="id")
     */
    protected $tasks;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $last_active;

    public function __construct()
    {
        $this->is_active = true;
        $this->isRemoved = false;
        $this->token = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $this->date_created = new \DateTime();
        $this->packages     = new ArrayCollection();
        $this->likes        = new ArrayCollection();
        $this->users        = new ArrayCollection();
        $this->admins       = new ArrayCollection();
        $this->jobs         = new ArrayCollection();
        $this->blogs        = new ArrayCollection();
        $this->tasks        = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return null
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param $role
     * @return $this
     */
    public function addRole($role)
    {
        $role = strtoupper($role);

        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        if ($this->getProfile() === null && !in_array('ROLE_ADMIN', $this->roles, true) && !in_array('ROLE_SDR', $this->roles, true)) {
            $this->roles[] = 'ROLE_ADMIN';
        }

        return $this;
    }

    public function setRoles(array $roles)
    {
        $this->roles = array();

        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }

    /**
     * @param $role
     */
    public function removeRole($role)
    {
        $role = strtoupper($role);

        if ($role != 'ROLE_SDR' && $role !== 'ROLE_ADMIN' && $role !== 'ROLE_SUPER_ADMIN' && $this->hasRole($role)) {
            $key = array_search($role, $this->roles, true);
            unset($this->roles[$key]);
        }
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param $role
     * @return bool
     */
    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->username,
            $this->password,
            $this->full_name,
            $this->roles,
            $this->token
        ));
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->username,
            $this->password,
            $this->roles,
            $this->is_active
            ) = unserialize($serialized);
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return bool
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->is_active;
    }

    /**
     * @ORM\PreUpdate()
     * @ORM\PrePersist()
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;
    }

    /**
     * @return bool
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->full_name;
    }

    /**
     * @param string $full_name
     */
    public function setFullName($full_name)
    {
        $this->full_name = $full_name;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getDateCreated()
    {
        return $this->date_created;
    }

    /**
     * @param \DateTime $date_created
     */
    public function setDateCreated(\DateTime $date_created)
    {
        $this->date_created = $date_created;
    }

    /**
     * @return Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param Profile $profile
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;
    }

    /**
     * @return mixed
     */
    public function getTemporaryToken()
    {
        return $this->temporary_token;
    }

    /**
     * @param mixed $temporary_token
     */
    public function setTemporaryToken($temporary_token)
    {
        $this->temporary_token = $temporary_token;
    }

    /**
     * @param UserInterface $user
     * @return bool
     */
    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof User) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->getSalt() !== $user->getSalt()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getFullName();
    }

    /**
     * @return mixed
     */
    public function getAdmins()
    {
        return $this->admins;
    }

    /**
     * @param mixed $admin
     */
    public function addAdmin($admin)
    {
        $this->admins->add($admin);
    }

    /**
     * @param mixed $admin
     */
    public function removeAdmin($admin)
    {
        $this->admins->removeElement($admin);
    }

    /**
     * @param mixed $admins
     */
    public function setAdmins($admins)
    {
        $this->admins = $admins;
    }

    /**
     * @return mixed
     */
    public function getPackages()
    {
        return $this->packages;
    }

    /**
     * @param $package
     */
    public function addPackages($package)
    {
        $this->packages->add($package);
    }

    /**
     * @return mixed
     */
    public function getJobs()
    {
        return $this->jobs;
    }

    /**
     * @param mixed $jobs
     */
    public function setJobs($jobs)
    {
        $this->jobs = $jobs;
    }

    /**
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->hasRole('ROLE_SUPER_ADMIN');
    }

    /**
     * @return bool
     */
    public function isAdminManager(){
        return $this->role == 'ROLE_ADMIN_MANAGER';
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role == 'ROLE_ADMIN';
    }

    /**
     * @return bool
     */
    public function isSDR()
    {
        return $this->role == 'ROLE_SDR';
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users->toArray();
    }

    /**
     * @param mixed $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }

    /**
     * @param mixed $user
     */
    public function addUser($user)
    {
        $this->users->add($user);
    }

    /**
     * @param mixed $user
     */
    public function removeUser($user)
    {
        $this->users->removeElement($user);
    }

    /**
     * @return array
     */
    public function getLikes()
    {
        $data = array();

        foreach ($this->likes as $like) {
            $like->setLiked(true);

            $data[] = $like;
        }

        return $data;
    }

    /**
     * @param Blog $like
     */
    public function addLike(Blog $like)
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
        }
    }

    /**
     * @param Blog $like
     */
    public function removeLike(Blog $like)
    {
        $this->likes->removeElement($like);
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
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

    /**
     * release
     *
     * @return self
     */
    public function release()
    {
        $this->isRemoved = false;

        return $this;
    }

    /**
     * getIsRemoved
     *
     * @return bool
     */
    public function getIsRemoved()
    {
        return $this->isRemoved;
    }

    /**
     * setTimezone
     *
     * @param string $timezone
     *
     * @return self
     */
    public function setTimezone($timezone)
    {
        if (!preg_match("/GMT[+|-][0-9][0-2]?/", $timezone)) {
            return $this;
        }

        $this->timezone = $timezone;

        return $this;
    }

    /**
     * getTimezone
     *
     * @return string
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * @return mixed
     */
    public function getQuotesDate()
    {
        return $this->quotes_date;
    }

    /**
     * @param \DateTime $quotes_date
     */
    public function setQuotesDate($quotes_date)
    {
        $this->quotes_date = $quotes_date;
    }

    /**
     * @return boolean
     */
    public function getQuoteChecked()
    {
        return $this->quote_checked;
    }

    /**
     * @param integer $quote_checked
     */
    public function setQuoteChecked($quote_checked)
    {
        $this->quote_checked = $quote_checked;
    }

    /**
     * @return mixed
     */
    public function getQuotesNum()
    {
        return $this->quotes_num;
    }

    /**
     * @param mixed $quotes_num
     */
    public function setQuotesNum($quotes_num)
    {
        $this->quotes_num = $quotes_num;
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
     * getBlogs
     *
     * @return mixed
     */
    public function getBlogs()
    {
        return $this->blogs->toArray();
    }

    /**
     * getAppliedDate
     *
     * @return \DateTime|null
     */
    public function getLastAppliedDate()
    {
        $maxdate = '';
        foreach( $this->getJobs() as $j )
            if( !$maxdate || $maxdate < $j->getAppliedDate() )
                $maxdate = $j->getAppliedDate();
        return  $maxdate ? $maxdate->format('Y-m-d') : '';
    }

    /**
     * getLastActiveDays
     *
     * @return integer
     */
    public function getLastActiveDays()
    {
        $maxdate = '';
        foreach( $this->getJobs() as $j )
            if( (!$maxdate || $maxdate < $j->getDate()) && ( $j->getAddedBy() ==='user' ) )
                $maxdate = $j->getDate();
        
        $last_active_days = $maxdate ? $maxdate->diff(new \DateTime())->days + 1 : 0;
        return $last_active_days;
    }

    /**
     * getAdminLastActiveDays
     *
     * @return integer
     */
    public function getAdminLastActiveDays()
    {
        $maxdate = '';
        foreach( $this->getJobs() as $j )
            if( (!$maxdate || $maxdate < $j->getDate()) && ( $j->getAddedBy() ==='admin' )  )
                $maxdate = $j->getDate();
        
        $admin_last_active_days = $maxdate ? $maxdate->diff(new \DateTime())->days + 1 : 0;
        return $admin_last_active_days;
    }

    /**
     * @return mixed
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * @param mixed $tasks
     */
    public function setTasks($tasks)
    {
        $this->tasks = $tasks;
    }

    /**
     * @return mixed
     */
    public function getLastActive()
    {
        return $this->last_active;
    }

    /**
     * @param mixed $last_active
     */
    public function setLastActive($last_active)
    {
        $this->last_active = $last_active;
    }
}