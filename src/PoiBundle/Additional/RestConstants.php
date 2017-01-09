<?php
/**
 * Created by PhpStorm.
 * User: Przemek
 * Date: 05.01.2017
 * Time: 18:52
 */

namespace PoiBundle\Additional;


class RestConstants
{
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

    const JSON_USER_ID = "userid";
    const JSON_TOKEN = "token";

    // Czas ważności tokenu w sekundach
    const TOKEN_TIME_VALID = 3600;
}