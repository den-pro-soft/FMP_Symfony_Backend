<?php

namespace RestBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="profiles")
 * @ORM\Entity()
 */
class Profile
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="RestBundle\Entity\User", inversedBy="profile")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $user;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $postal_code;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $state;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $city;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $street_address;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $phone_number;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $linkedin_url;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $new_gmail_email;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $new_gmail_password;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $linkedin_email;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $linkedin_password;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $birth_date;

    /**
     * @ORM\OneToMany(targetEntity="RestBundle\Entity\Document", mappedBy="profile", cascade={"remove", "persist"})
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id")
     */
    protected $documents;

    /**
     * @ORM\OneToMany(targetEntity="RestBundle\Entity\Education", mappedBy="profile", cascade={"remove", "persist"})
     * @ORM\JoinColumn(name="education_id", referencedColumnName="id")
     * @ORM\OrderBy({"end_date" = "DESC"})
     */
    protected $education;

    /**
     * @ORM\OneToMany(targetEntity="RestBundle\Entity\WorkExperience", mappedBy="profile", cascade={"remove", "persist"})
     * @ORM\JoinColumn(name="experience_id", referencedColumnName="id")
     * @ORM\OrderBy({"end_date" = "DESC"})
     */
    protected $work_experience;

    /**
     * @ORM\OneToOne(targetEntity="RestBundle\Entity\CareerPreferences", cascade={"remove", "persist"})
     * @ORM\JoinColumn(name="career_preferences_id", referencedColumnName="id")
     */
    protected $career_preferences;

    /**
     * @ORM\OneToMany(targetEntity="RestBundle\Entity\UserReference", mappedBy="profile", cascade={"remove", "persist"})
     * @ORM\JoinColumn(name="reference_id", referencedColumnName="id")
     */
    protected $user_reference;

    /**
     * @ORM\OneToOne(targetEntity="RestBundle\Entity\Questions", cascade={"remove", "persist"})
     * @ORM\JoinColumn(name="questions_id", referencedColumnName="id")
     */
    protected $questions;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $templateIsSelected;

    protected $templates;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->documents = new ArrayCollection();
        $this->education = new ArrayCollection();
        $this->work_experience = new ArrayCollection();
        $this->user_reference = new ArrayCollection();
        $this->templateIsSelected = false;
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
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getPostalCode()
    {
        return $this->postal_code;
    }

    /**
     * @param mixed $postal_code
     */
    public function setPostalCode($postal_code)
    {
        $this->postal_code = $postal_code;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getStreetAddress()
    {
        return $this->street_address;
    }

    /**
     * @param mixed $street_address
     */
    public function setStreetAddress($street_address)
    {
        $this->street_address = $street_address;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phone_number;
    }

    /**
     * @param mixed $phone_number
     */
    public function setPhoneNumber($phone_number)
    {
        $this->phone_number = $phone_number;
    }

    /**
     * @return mixed
     */
    public function getLinkedinUrl()
    {
        return $this->linkedin_url;
    }

    /**
     * @param mixed $linkedin_url
     */
    public function setLinkedinUrl($linkedin_url)
    {
        $this->linkedin_url = $linkedin_url;
    }

    /**
     * @return mixed
     */
    public function getNewGmailEmail()
    {
        return $this->new_gmail_email;
    }

    /**
     * @param mixed $new_gmail_email
     */
    public function setNewGmailEmail($new_gmail_email)
    {
        $this->new_gmail_email = $new_gmail_email;
    }

    /**
     * @return mixed
     */
    public function getNewGmailPassword()
    {
        return $this->new_gmail_password;
    }

    /**
     * @param mixed $new_gmail_password
     */
    public function setNewGmailPassword($new_gmail_password)
    {
        $this->new_gmail_password = $new_gmail_password;
    }

    /**
     * @return mixed
     */
    public function getLinkedinEmail()
    {
        return $this->linkedin_email;
    }

    /**
     * @param mixed $linkedin_email
     */
    public function setLinkedinEmail($linkedin_email)
    {
        $this->linkedin_email = $linkedin_email;
    }

    /**
     * @return mixed
     */
    public function getLinkedinPassword()
    {
        return $this->linkedin_password;
    }

    /**
     * @param mixed $linkedin_password
     */
    public function setLinkedinPassword($linkedin_password)
    {
        $this->linkedin_password = $linkedin_password;
    }

    /**
     * @return ArrayCollection
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * @param Document $document
     */
    public function addDocument(Document $document)
    {
        $this->documents->add($document);
    }

    /**
     * @param Document $document
     */
    public function removeDocument(Document $document)
    {
        $this->documents->remove($document);
    }

    /**
     * @return CareerPreferences
     */
    public function getCareerPreferences()
    {
        return $this->career_preferences;
    }

    /**
     * @param CareerPreferences $career_preferences
     */
    public function setCareerPreferences(CareerPreferences $career_preferences)
    {
        $this->career_preferences = $career_preferences;
    }

    /**
     * @return ArrayCollection
     */
    public function getEducation()
    {
        return $this->education;
    }

    /**
     * @param Education $education
     */
    public function addEducation(Education $education)
    {
        $this->education->add($education);
    }

    /**
     * @param Education $education
     */
    public function removeEducation(Education $education)
    {
        $this->education->removeElement($education);
    }

    /**
     * @return ArrayCollection
     */
    public function getWorkExperience()
    {
        return $this->work_experience;
    }

    /**
     * @param mixed $work_experience
     */
    public function setWorkExperience($work_experience)
    {
        $this->work_experience = $work_experience;
    }

    /**
     * @param WorkExperience $work_experience
     */
    public function addWorkExperience(WorkExperience $work_experience)
    {
        $this->work_experience->add($work_experience);
    }

    /**
     * @param WorkExperience $work_experience
     */
    public function removeWorkExperience(WorkExperience $work_experience)
    {
        $this->work_experience->removeElement($work_experience);
    }

    /**
     * @return mixed
     */
    public function getUserReference()
    {
        return $this->user_reference;
    }

    /**
     * @param mixed $user_reference
     */
    public function addReference($user_reference)
    {
        $this->user_reference->add($user_reference);
    }

    /**
     * @param mixed $user_reference
     */
    public function removeReference($user_reference)
    {
        $this->user_reference->removeElement($user_reference);
    }

    /**
     * @return mixed
     */
    public function getBirthDate()
    {
        return $this->birth_date;
    }

    /**
     * @param $birth_date
     */
    public function setBirthDate($birth_date)
    {
        $this->birth_date = new \DateTime($birth_date);
    }

    /**
     * @return Questions
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * @param Questions $questions
     */
    public function setQuestions(Questions $questions)
    {
        $this->questions = $questions;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->user->getFullName();
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->user->getEmail();
    }

    /**
     * @return mixed
     */
    public function getProgress()
    {
        $empty_fields = [];
        $progress['value'] = 100;

        if (empty($this->getFullName())
            || empty($this->getPhoneNumber())
            || empty($this->getStreetAddress())
            || empty($this->getCity())
            || empty($this->getState())
            || empty($this->getPostalCode())
            || empty($this->getBirthDate())
            || empty($this->getLinkedinUrl())
        ) {
            $empty_fields['profile'] = 'Update Profile';
            $progress['value'] -= 20;
        }

        $career_preferences = $this->getCareerPreferences();

        if (empty($career_preferences->getIndustry())
            || count($career_preferences->getJobTitles()) === 0
            || (empty($career_preferences->getDesireSalaryValue()) && empty($career_preferences->getDesireSalaryType()))
        ) {
            $empty_fields['career_preferences'] = 'Update Career Preferences';
            $progress['value'] -= 15;
        }

        if ($this->getWorkExperience()->count() === 0 || $this->getEducation()->count() === 0) {
            $empty_fields['work_experience_education'] = 'Update Education & Work Experience';
            $progress['value'] -= 20;
        }

        if ($this->getUserReference()->count() === 0) {
            $empty_fields['reference'] = 'Update References';
            $progress['value'] -= 15;
        }

        if ($this->getDocuments()->count() === 0) {
            $empty_fields['documents'] = 'Update Documents';
            $progress['value'] -= 15;
        }

        $questions = $this->getQuestions();

        if (empty($questions->getWorkAuthorization())
            || empty($questions->getDisabilityStatus())
            || empty($questions->getGender())
            || empty($questions->getVeteranStatus())
            || empty($questions->getRaceEthnicity())
        ) {
            $empty_fields['questions'] = 'Update EEOC Questions';
            $progress['value'] -= 15;
        }

        $progress['values'] = $empty_fields;

        return $progress;
    }

    public function setTemplates($templates)
    {
        $this->templates = $templates;
    }

    public function getTemplates()
    {
        return $this->templates;
    }

    /**
     * selectTemplate
     *
     * @return self
     */
    public function selectTemplate()
    {
        $this->templateIsSelected = true;

        return $this;
    }

    /**
     * templateIsSelected
     *
     * @return bool
     */
    public function templateIsSelected()
    {
        return (bool)$this->templateIsSelected;
    }
}