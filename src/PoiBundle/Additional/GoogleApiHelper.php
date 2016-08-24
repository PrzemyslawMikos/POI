<?php

namespace PoiBundle\Additional;

class GoogleApiHelper{

    public static function getGoogleAddress($lat, $lng, $lang)
    {
        $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false&language='.$lang;
        $json = @file_get_contents($url);
        $data = json_decode($json);
        $status = $data->status;
        if($status=="OK")
        {
            return $data->results[0]->formatted_address;
        }
        else
        {
            return false;
        }
    }

}
