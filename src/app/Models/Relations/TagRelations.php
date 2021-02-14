<?php

namespace App\Models\Relations;

use App\Models\Url;
use App\User;

trait TagRelations
{
    public function url()
    {
        return $this->belongsTo(Url::class, 'id', 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
