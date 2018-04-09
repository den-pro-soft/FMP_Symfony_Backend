<?php

namespace RestBundle\Model;

/**
 * Class HomePageModel
 * @package RestBundle\Model
 */
class HomePageModel extends PageModel
{
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
}