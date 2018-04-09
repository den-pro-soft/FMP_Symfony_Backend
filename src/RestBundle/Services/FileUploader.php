<?php
namespace RestBundle\Services;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class FileUploader
 * @package RestBundle\Services
 */
class FileUploader
{
    /** @var string $targetDir */
    private $targetDir;

    /**
     * FileUploader constructor.
     * @param string $targetDir
     */
    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    /**
     * @param UploadedFile $file
     * @param bool $is_avatar
     * @return string
     */
    public function upload(UploadedFile $file, $is_avatar = false)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $path = $this->targetDir;

        if ($is_avatar) {
            $path = $this->targetDir . '/files/';
        }

        return $file->move($path, $fileName);
    }

    /**
     * uploadImage
     *
     * @param UploadedFile $file
     */
    public function uploadImage(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        return $file->move($this->targetDir. "/images/", $fileName);
    }

    /**
     * @param File $file
     * @return string
     */
    public function remove(File $file)
    {
        $path = $file->getPathname();
        unlink($path);

        return true;
    }

    /**
     * @return string
     */
    public function getTargetDir()
    {
        return $this->targetDir;
    }
}