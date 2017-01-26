<?php

namespace PoiBundle\Entity\Application;

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
        $instance->rating = $request->get('rating');
        $instance->pointid = $request->get('pointid');
        $instance->userid = $request->get('userid');
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