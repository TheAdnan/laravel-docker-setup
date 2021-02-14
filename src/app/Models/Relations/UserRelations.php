<?php

namespace App\Models\Relations;

use App\Models\Tag;
use App\Models\Url;

trait UserRelations
{
    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    public function links()
    {
        return $this->hasMany(Url::class);
    }
}
