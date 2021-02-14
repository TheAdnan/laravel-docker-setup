<?php

namespace App\Models;

use App\Models\Relations\UrlRelations;
use App\Models\Traits\UrlParser;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    use UrlRelations,
        UrlParser;

    public static function boot()
    {
        parent::boot();

        self::creating(function($model){
            $model->link = UrlParser::parse($model->link);
        });
    }

    protected $fillable = [
        'link', 'user_id'
    ];
}
