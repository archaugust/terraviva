<?php
namespace AppBundle;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;

class FileUploader extends Controller
{
    private $targetDir;
    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function upload(UploadedFile $file, $imgAlias)
    {
        $fileName = $imgAlias .'.'.$file->getClientOriginalExtension();
        $file->move($this->targetDir, $fileName);
        return $fileName;
    }

    public function uploadImage(UploadedFile $file, $imgAlias)
    {
        $ext = strtolower($file->getClientOriginalExtension());
        $fileName = $imgAlias .'.'. $ext;

        $images = array('jpg','jpeg','gif','png');
        if (in_array($ext, $images)) {
            $file->move($this->targetDir, $fileName);
            return $fileName;
        }
        else {
            $session = new Session();

            $session->getFlashBag()->add(
                'danger',
                'File not uploaded. Please upload images only.'
            );

            return null;
        }
    }

    public function setTargetDir($targetDir) {
        $this->targetDir = $targetDir;
    }
}