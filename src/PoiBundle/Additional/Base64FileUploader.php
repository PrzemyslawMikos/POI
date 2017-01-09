<?php
/**
 * Created by PhpStorm.
 * User: Przemek
 * Date: 05.01.2017
 * Time: 00:24
 */

namespace PoiBundle\Additional;


class Base64FileUploader
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function upload($text, $mimetype)
    {
        $data = base64_decode($text);
        //New name of file, time_md5.extension
        $fileName = time().'_'.md5(uniqid()).'.'.$mimetype;
        //Move file to target directory(directory specified in 'Workshop\app\config\services.yml)
        file_put_contents($this->targetDir.'/'.$fileName, $data);
        //Return new filename saved to DB
        return $fileName;
    }

    public function delete($file){
        unlink($this->targetDir.'/'.$file);
    }
}