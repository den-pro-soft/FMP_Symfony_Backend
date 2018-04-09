<?php

namespace RestBundle\Model;

/**
 * Class TestimonialsModel
 * @package RestBundle\Model
 */
class TestimonialsModel extends PageModel
{
    protected $content = [];
    protected $testimonials = [];

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

    /**
     * @return array
     */
    public function getTestimonials()
    {
        return $this->testimonials;
    }

    /**
     * @param array $testimonials
     */
    public function setTestimonials($testimonials)
    {
        $this->testimonials = $testimonials;
    }
}