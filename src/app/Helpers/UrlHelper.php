<?php

namespace App\Helpers;

use App\Models\Url;
use Illuminate\Support\Facades\DB;

class UrlHelper
{
    public static function findAndCompare($url, $user_id = null) {
        $parsedUrl = Url::parse($url);

        if (!$parsedUrl) $parsedUrl = $url;

        if(is_null($user_id)) {
            $found = DB::table('urls')
                ->where('link', 'like', '%' . $parsedUrl . '%')
                ->count();
        }
        else {
            $found = DB::table('urls')
                ->where('user_id', '=', $user_id)
                ->where('link', 'like', '%' . $parsedUrl . '%')
                ->count();
        }

        return $found;
    }
}
