<?php
/**
 * Created by PhpStorm.
 * User: przemyslaw.mikos
 * Date: 7/12/2016
 * Time: 10:47 AM
 */

namespace PoiBundle\Additional;

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
        //New name of file, time_md5.extension
        $fileName = time().'_'.md5(uniqid()).'.'.$file->guessExtension();
        //Move file to target directory(directory specified in 'Workshop\app\config\services.yml)
        $file->move($this->targetDir, $fileName);
        //Return new filename saved to DB
        return $fileName;
    }

    public function getMimeType(UploadedFile $file){
        return $file->getMimeType();
    }

    public function delete($file){
        unlink($this->targetDir.'/'.$file);
    }

}