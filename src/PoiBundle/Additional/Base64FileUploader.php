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
        $fileName = time().'_'.md5(uniqid()).'.'.$mimetype;
        file_put_contents($this->targetDir.'/'.$fileName, $data);
        return $fileName;
    }

    public function delete($file){
        unlink($this->targetDir.'/'.$file);
    }
}