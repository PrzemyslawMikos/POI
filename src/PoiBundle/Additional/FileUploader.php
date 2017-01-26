<?php

namespace PoiBundle\Additional;

use JMS\Serializer\Exception\Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function upload(UploadedFile $file)
    {
        $fileName = time().'_'.md5(uniqid()).'.'.$file->guessExtension();
        $file->move($this->targetDir, $fileName);
        return $fileName;
    }

    public function getMimeType(UploadedFile $file){
        return $file->getMimeType();
    }

    public function delete($file){
        try{
            unlink($this->targetDir.'/'.$file);
        }
       catch (\Exception $e){

        }
    }

}