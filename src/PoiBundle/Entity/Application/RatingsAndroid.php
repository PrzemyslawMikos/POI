<?php

namespace PoiBundle\Entity\Application;

use PoiBundle\Additional\RestConstants;
use Symfony\Component\HttpFoundation\Request;

class RatingsAndroid
{

    private $rating;
    private $pointid;
    private $userid;

    public function __construct()
    {
    }

    public static function constructRequest(Request $request)
    {
        $instance = new self();
        $instance->rating = $request->get(RestConstants::JSON_RATING_KEY);
        $instance->pointid = $request->get(RestConstants::JSON_POINTID_KEY);
        $instance->userid = $request->get(RestConstants::JSON_USERID_KEY);
        return $instance;
    }

    public function getRating()
    {
        return $this->rating;
    }

    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    public function getPointid()
    {
        return $this->pointid;
    }

    public function setPointid($pointid)
    {
        $this->pointid = $pointid;
    }

    public function getUserid()
    {
        return $this->userid;
    }

    public function setUserid($userid)
    {
        $this->userid = $userid;
    }

}