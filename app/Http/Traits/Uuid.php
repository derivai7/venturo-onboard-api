<?php

namespace App\Http\Traits;

use Closure;
use Ramsey\Uuid\Exception\UnableToBuildUuidException;
use Ramsey\Uuid\Uuid as Generator;

/**
 * @method static creating(Closure $param)
 */
trait Uuid
{
    protected static function bootUuid()
    {
        static::creating(function ($model) {
            try {
                $model->id = Generator::uuid4()->toString();
            } catch (UnableToBuildUuidException $e) {
                abort(500, $e->getMessage());
            }
        });
    }
}
