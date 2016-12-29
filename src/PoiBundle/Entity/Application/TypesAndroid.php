<?php

namespace PoiBundle\Entity\Application;

use PoiBundle\Entity\Types;
use JMS\Serializer\Annotation\Type;
/**
 * Types Android class
 */
class TypesAndroid
{
    private $id;

    private $name;

    private $description;
    
    /**
     * @Type("DateTime<'Y-m-d'>")
     */
    private $addeddate;
    
    private $image;

    private $mimetype;

    
    public function __construct()
    {
    }

    public static function constructType(Types $type)
    {
        $instance = new self();
        $instance->id = $type->getId();
        $instance->name = $type->getName();
        $instance->description = $type->getDescription();
        $instance->addeddate = $type->getAddeddate();
        $instance->image = $type->getImage();
        $instance->mimetype = $type->getMimetype();
        return $instance;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getAddeddate()
    {
        return $this->addeddate;
    }

    /**
     * @param mixed $addeddate
     */
    public function setAddeddate($addeddate)
    {
        $this->addeddate = $addeddate;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getMimetype()
    {
        return $this->mimetype;
    }

    /**
     * @param mixed $mimetype
     */
    public function setMimetype($mimetype)
    {
        $this->mimetype = $mimetype;
    }

}