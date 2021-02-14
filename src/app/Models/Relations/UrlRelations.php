<?php

namespace App\Models\Relations;

use App\Models\Tag;
use App\User;

trait UrlRelations
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
