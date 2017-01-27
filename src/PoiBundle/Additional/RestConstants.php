<?php

namespace PoiBundle\Additional;

class RestConstants
{
    // Statusy wysyłane przez REST API
    const STATUS = "STATUS";
    const STATUS_OK = "OK";
    const STATUS_WRONG_PARAMS = "WRONG_PARAMS";
    const STATUS_BAD_CREDENTIALS = "BAD_CREDENTIALS";
    const STATUS_USER_BLOCKED = "USER_BLOCKED";
    const STATUS_USERNAME_EXIST = "USERNAME_EXIST";
    const STATUS_EMAIL_EXIST = "EMAIL_EXIST";
    const STATUS_INTERNAL_SERVER_ERROR = "INTERNAL_ERROR";
    const STATUS_NOT_FOUND = "NOT_FOUND";
    const STATUS_TOKEN_EXPIRED = "TOKEN_EXPIRED";
    const STATUS_BAD_TOKEN = "BAD_TOKEN";
    const STATUS_NEW = "NEW";
    const STATUS_UPDATED = "UPDATED";

    // Czas ważności tokenu w sekundach
    const TOKEN_TIME_VALID = 3600;

    // Klucze obiektów JSON
    const JSON_TOKEN_KEY = "token";
    const JSON_USERNAME_KEY = "username";
    const JSON_PASSWORD_KEY = "password";
    const JSON_NICKNAME_KEY = "nickname";
    const JSON_EMAIL_KEY = "email";
    const JSON_PHONE_KEY = "phone";
    const JSON_ID_KEY = "id";
    const JSON_LONGITUDE_KEY = "longitude";
    const JSON_LATITUDE_KEY = "latitude";
    const JSON_RATING_KEY = "rating";
    const JSON_NAME_KEY = "name";
    const JSON_LOCALITY_KEY = "locality";
    const JSON_DESCRIPTION_KEY = "description";
    const JSON_PICTURE_KEY = "picture";
    const JSON_IMAGE_KEY = "image";
    const JSON_MIMETYPE_KEY = "mimetype";
    const JSON_ADDEDDATE_KEY = "addeddate";
    const JSON_CREATIONDATE_KEY = "creationdate";
    const JSON_TYPEID_KEY = "typeid";
    const JSON_USERID_KEY = "userid";
    const JSON_POINTID_KEY = "pointid";
    const JSON_PERMISSIONID_KEY = "permissionid";
    const JSON_FIRSTLOGIN_KEY = "firstlogin";
    const JSON_UNBLOCKED_KEY = "unblocked";
    const JSON_DISTANCE_KEY = "distance";
}