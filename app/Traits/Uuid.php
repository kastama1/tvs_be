<?php

namespace App\Traits;

trait Uuid
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

        });
    }
}
