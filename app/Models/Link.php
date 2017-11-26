<?php

namespace App\Models;

use Cache;

class Link extends Model
{
    protected $fillable = ['title', 'link'];

    public $cacheKey = 'easybbs_links';
    protected $cacheExpireInMinutes = 1440;

    public function getAllCached()
    {
        return Cache::remember($this->cacheKey, $this->cacheExpireInMinutes, function(){
            return $this->all();
        });
    }
}
