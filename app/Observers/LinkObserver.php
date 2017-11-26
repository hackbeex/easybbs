<?php

namespace App\Observers;

use Cache;
use App\Models\Link;

class LinkObserver
{
    public function saved(Link $link)
    {
        Cache::forget($link->cacheKey);
    }
}