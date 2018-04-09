<?php

namespace RestBundle\Model;

/**
 * Class TermsOfUseModel
 * @package RestBundle\Model
 */
class TermsOfUseModel extends PageModel
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