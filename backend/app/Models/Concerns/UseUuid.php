<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait UseUuid
{
    protected static function bootUseUuid()
    {
        static::creating(function ($model) {
            if (! $model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    public function getKeyType()
    {
        return 'string';
    }
}