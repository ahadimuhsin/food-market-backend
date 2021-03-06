<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Food extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $dates = ['deleted_at'];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)
            ->getPreciseTimestamp(3);
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)
            ->getPreciseTimestamp(3);
    }

    public function toArray(){
        $toArray = parent::toArray();
        $toArray['picturePath'] = $this->picturePath;
        return $toArray;
    }
    /*
    mengembalikan gambar dengan full url
    */
    public function getPicturePathAttribute()
    {
        return url('').Storage::url($this->attributes['picturePath']);
    }
}
