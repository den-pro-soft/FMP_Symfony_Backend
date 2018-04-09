<?php

namespace RestBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController as Controller;
use RestBundle\Entity\Document;
use RestBundle\Entity\Job;
use RestBundle\Entity\Message;
use RestBundle\Entity\Template;
use RestBundle\Entity\Resource;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class DownloadController extends Controller
{
    /**
     * @Route("/download/document/{document}", name="download_document")
     * @Method("GET")
     * @param Document $document
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function documentAction(Document $document)
    {
        $downloadHandler = $this->get('vich_uploader.download_handler');
        $fileName = $document->getName();
        $fileField = 'document';

        return $downloadHandler->downloadObject($document, $fileField, $objectClass = null, $fileName);
    }

    /**
     * @Route("/download/attachment/{attachment}", name="download_attachment")
     * @Method("GET")
     * @param Message $attachment
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function attachmentAction(Message $attachment)
    {
        $downloadHandler = $this->get('vich_uploader.download_handler');
        $fileName = $attachment->getAttachmentName();
        $fileField = 'attachment';

        return $downloadHandler->downloadObject($attachment, $fileField, $objectClass = null, $fileName);
    }

    /**
     * @Route("/download/attachment/email/{attachment}", name="download_attachment_by_path")
     * @ParamConverter("attachment", options={"mapping": {"attachment": "attachment_path"}})
     * @Method("GET")
     * @param Message $attachment
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function attachmentByPathAction(Message $attachment)
    {
        $downloadHandler = $this->get('vich_uploader.download_handler');
        $fileName = $attachment->getAttachmentName();
        $fileField = 'attachment';

        return $downloadHandler->downloadObject($attachment, $fileField, $objectClass = null, $fileName);
    }

    /**
     * @Route("/download/job/{attachment}", name="download_job_attachment")
     * @Method("GET")
     * @param Job $attachment
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function jobAttachmentAction(Job $attachment)
    {
        $downloadHandler = $this->get('vich_uploader.download_handler');
        $fileName = $attachment->getAttachmentAlias();
        $fileField = 'attachment';

        return $downloadHandler->downloadObject($attachment, $fileField, $objectClass = null, $fileName);
    }

    /**
     * @Route("/download/template/{template}", name="download_template")
     * @Method("GET")
     * @param Template $template
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function templateAction(Template $template)
    {
        $downloadHandler = $this->get('vich_uploader.download_handler');
        $fileName = $template->getName();
        $fileField = 'template';

        $profile = $this->getUser()->getProfile();
        $profile->selectTemplate();

        $this->getDoctrine()->getManager()->flush();

        return $downloadHandler->downloadObject($template, $fileField, $objectClass = null, $fileName);
    }

    /**
     * @Route("/download/resource/{resource}", name="download_resource")
     * @Method("GET")
     * @param Resource $resource
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function resourceAction(Resource $resource)
    {
        $downloadHandler = $this->get('vich_uploader.download_handler');
        $fileName = $resource->getName();
        $fileField = 'resource';

        $this->getDoctrine()->getManager()->flush();

        return $downloadHandler->downloadObject($resource, $fileField, $objectClass = null, $fileName);
    }
}
