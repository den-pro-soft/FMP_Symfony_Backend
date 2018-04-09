<?php

namespace RestBundle\Model;

/**
 * Class FaqModel
 * @package RestBundle\Model
 */
class FaqModel extends PageModel
{
    protected $content = [];

    /**
     * @return array
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