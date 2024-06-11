<?php

namespace App\Http\Traits;

use Closure;

/**
 * @method static saving(Closure $param)
 * @method static creating(Closure $param)
 * @method static deleting(Closure $param)
 */
trait RecordSignature
{
    protected static function bootRecordSignature()
    {
        static::saving(function ($model) { //otomatis mengisi ketika edit
            if (!empty(auth()->user()->id)) {
                $model->updated_by = auth()->user()->id;
            }
        });

        static::creating(function ($model) { //otomatis mengisi ketika membuat
            if (!empty(auth()->user()->id)) {
                $model->created_by = auth()->user()->id;
            }
        });

        static::deleting(function ($model) { //otomatis mengisi ketika menghapus
            if (!empty(auth()->user()->id)) {
//                $model->where('id', $model->id)->update(["deleted_by" => auth()->user()->id]);
                $model->deleted_by = auth()->user()->id;
            }
        });
    }
}
