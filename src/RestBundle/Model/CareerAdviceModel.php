<?php

namespace RestBundle\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class CareerAdviceModel
 * @package RestBundle\Model
 * @Serializer\ExclusionPolicy("all")
 */
class CareerAdviceModel extends PageModel
{
    /**
     * @Serializer\Groups({"list"})
     * @Serializer\Expose()
     */
    protected $content;

    /**
     * @Serializer\Groups({"list"})
     * @Serializer\Expose()
     */
    protected $count;


    /**
     * @Serializer\Groups({"list"})
     * @Serializer\Expose()
     */
    protected $total_count;

    /**
     * @Serializer\Groups({"list"})
     * @Serializer\Expose()
     */
    protected $blogs;

    /**
     * @Serializer\Groups({"list"})
     * @Serializer\Expose()
     */
    protected $top;

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getBlogs()
    {
        return $this->blogs;
    }

    /**
     * @param mixed $blogs
     */
    public function setBlogs($blogs)
    {
        $this->blogs = $blogs;
    }

    /**
     * @return mixed
     */
    public function getTop()
    {
        return $this->top;
    }

    /**
     * @param mixed $tops
     */
    public function setTop($tops)
    {
        $this->top = $tops;
    }

    /**
     * @return mixed
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param mixed $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * @return mixed
     */
    public function getTotalCount()
    {
        return $this->total_count;
    }

    /**
     * @param mixed $total_count
     */
    public function setTotalCount($total_count)
    {
        $this->total_count = $total_count;
    }
}