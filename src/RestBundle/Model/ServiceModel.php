<?php

namespace RestBundle\Model;

/**
 * Class ServiceModel
 * @package RestBundle\Model
 */
class ServiceModel extends PageModel
{
    protected $link;
    protected $content = [];

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param array $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param mixed $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }
}