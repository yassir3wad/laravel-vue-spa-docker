<?php

namespace App\Utils;

class Url
{
    public static function isValidUrl($url)
    {
        $url = filter_var($url, FILTER_SANITIZE_URL);

        return filter_var($url, FILTER_VALIDATE_URL);
    }
}
