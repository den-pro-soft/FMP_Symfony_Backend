<?php

namespace RestBundle\Model;

/**
 * Class ContactUsModel
 * @package RestBundle\Model
 */
class ContactUsModel extends PageModel
{
    protected $url;
    protected $form_title;
    protected $form_content;
    protected $phone_title;
    protected $phone_number;
    protected $phone_time;

    /**
     * @return mixed
     */
    public function getFormTitle()
    {
        return $this->form_title;
    }

    /**
     * @param mixed $form_title
     */
    public function setFormTitle($form_title)
    {
        $this->form_title = $form_title;
    }

    /**
     * @return mixed
     */
    public function getFormContent()
    {
        return $this->form_content;
    }

    /**
     * @param mixed $form_content
     */
    public function setFormContent($form_content)
    {
        $this->form_content = $form_content;
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
    public function getPhoneTime()
    {
        return $this->phone_time;
    }

    /**
     * @param mixed $phone_time
     */
    public function setPhoneTime($phone_time)
    {
        $this->phone_time = $phone_time;
    }

    /**
     * @return mixed
     */
    public function getPhoneTitle()
    {
        return $this->phone_title;
    }

    /**
     * @param mixed $phone_title
     */
    public function setPhoneTitle($phone_title)
    {
        $this->phone_title = $phone_title;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }
}