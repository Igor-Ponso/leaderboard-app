<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasHash
{
    /**
     * Boot the trait.
     *
     * @return void
     */
    public static function bootHasHash(): void
    {
        static::creating(function ($model) {
            if (empty($model->hash)) {
                $model->hash = (string) Str::uuid();
            }
        });
    }

    /**
     * Force model binding to use 'hash' column instead of 'id'.
     */
    public function getRouteKeyName(): string
    {
        return 'hash';
    }
}
