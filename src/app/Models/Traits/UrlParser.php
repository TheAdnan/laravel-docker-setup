<?php

namespace App\Models\Traits;


trait UrlParser
{
    public static function parse($link) {
        $link = str_replace('https://', '', $link);
        $link = str_replace('http://', '', $link);

        if (preg_match('/.+\/\?(.+)&{0,}(.*)/s', $link, $params)) {
            $link = str_replace($params[1], '', $link);
            return $link;
        }

        return $link;

    }

}
