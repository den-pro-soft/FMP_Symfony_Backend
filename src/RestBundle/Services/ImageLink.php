<?php

namespace RestBundle\Services;

use RestBundle\Entity\Author;
use RestBundle\Entity\Blog;
use RestBundle\Entity\Document;
use RestBundle\Entity\Job;
use RestBundle\Entity\Message;
use RestBundle\Entity\Page;
use RestBundle\Entity\Template;
use RestBundle\Entity\Resource;
use RestBundle\Entity\Testimonial;
use RestBundle\Entity\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

/**
 * Class ImageLink
 * @package ApiBundle\Service
 */
class ImageLink
{
    /**@var RequestContext $context */
    protected $context;

    /** @var UploaderHelper $uploaderBundle */
    protected $uploaderBundle;

    /** @var Router $router */
    private $router;

    /** @var integer $port */
    private $port;

    /**
     * ImageLink constructor.
     * @param UploaderHelper $uploaderBundle
     * @param Router $router
     */
    public function __construct(UploaderHelper $uploaderBundle, Router $router)
    {
        $this->context = $router->getContext();
        $this->uploaderBundle = $uploaderBundle;
        $this->router = $router;
        //$this->port = $_SERVER['SERVER_PORT'] !== '80' ? ':' . $_SERVER['SERVER_PORT'] : null;
        $this->port = ":8443";
    }

    public function getServerUrl()
    {
        return 'http://' . $this->context->getHost() . $this->port;
    }

    public function getApiImageUrl($obj, $fileName)
    {
        return $this->getServerUrl() . $this->uploaderBundle->asset($obj, $fileName);
    }

    public function setImageLink($object)
    {
        if ($object instanceof Blog || $object instanceof Page) {
            $object->setImage($this->getApiImageUrl($object, 'image'));
        }

        if ($object instanceof Testimonial || $object instanceof Author) {
            if ($object->getAvatarName()) {
                $object->setAvatar($this->getApiImageUrl($object, 'avatar'));
            }
        }

        if ($object instanceof Document) {
            $link = $this->router->generate('download_document', ['document' => $object->getId()]);
            $object->setDocument($link);
        }

        if ($object instanceof Message && $object->getAttachmentName() !== null) {
            $link = $this->router->generate('download_attachment', ['attachment' => $object->getId()]);
            $object->setAttachment($link);
            $object->setMessage($object->getAttachmentName());
        }

        if ($object instanceof Job && $object->getAttachmentName() !== null) {
            $link = $this->router->generate('download_job_attachment', ['attachment' => $object->getId()]);
            $object->setAttachment($link);
        }

        if ($object instanceof Template) {
            $link = $this->router->generate('download_template', ['template' => $object->getId()]);
            $object->setTemplate($link);
            $object->setPreview($this->getApiImageUrl($object, 'preview'));
        }

        if ($object instanceof Resource) {
            $link = $this->router->generate('download_resource', ['resource' => $object->getId()]);
            $object->setResource($link);
            $object->setPreview($this->getApiImageUrl($object, 'preview'));
        }

        return $object;
    }
}