<?php

namespace PoiBundle\Entity\Application;

use PoiBundle\Entity\Points;
use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\Annotation\Type;
/**
 * Points Android class
 */
class PointsAndroid
{
    private $id;

    private $longitude;

    private $latitude;

    private $rating;

    private $name;

    private $locality;

    private $description;

    private $picture;

    private $mimetype;
    /**
     * @Type("DateTime<'Y-m-d'>")
     */
    private $addeddate;

    private $typeid;

    private $userid;

    private $distance;

    public function __construct()
    {
    }

    public static function constructPoint(Points $point)
    {
        $instance = new self();
        $instance->id = $point->getId();
        $instance->longitude = $point->getLongitude();
        $instance->latitude = $point->getLatitude();
        $instance->rating = $point->getRating();
        $instance->name = $point->getName();
        $instance->locality = $point->getLocality();
        $instance->description = $point->getDescription();
        $instance->picture = $point->getPicture();
        $instance->mimetype = $point->getMimetype();
        $instance->addeddate = $point->getAddeddate();
        $instance->typeid = $point->getType()->getId();
        $instance->userid = $point->getUser()->getId();
        return $instance;
    }

    public static function constructRequest(Request $request)
    {
        $instance = new self();
        $instance->id = null;
        $instance->longitude = $request->get('longitude');
        $instance->latitude = $request->get('latitude');
        $instance->rating = $request->get('rating');
        $instance->name = $request->get('name');
        $instance->locality = $request->get('locality');
        $instance->description = $request->get('description');
        $instance->picture = $request->get('picture');
        $instance->mimetype = $request->get('mimetype');
        $instance->typeid = $request->get('typeid');
        $instance->userid = $request->get('userid');
        return $instance;
    }

    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getRating()
    {
        return $this->rating;
    }

    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setLocality($locality)
    {
        $this->locality= $locality;

        return $this;
    }

    public function getLocality()
    {
        return $this->locality;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    public function getPicture()
    {
        return $this->picture;
    }

    public function setMimetype($mimetype)
    {
        $this->mimetype = $mimetype;

        return $this;
    }

    public function getMimetype()
    {
        return $this->mimetype;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setTypeid($typeid)
    {
        $this->typeid = $typeid;

        return $this;
    }

    public function getTypeid()
    {
        return $this->typeid;
    }

    public function setUserid($userid)
    {
        $this->userid = $userid;

        return $this;
    }

    public function getUserid()
    {
        return $this->userid;
    }

    public function getDistance()
    {
        return $this->distance;
    }

    public function setDistance($distance)
    {
        $this->distance = $distance;
    }

}