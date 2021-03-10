<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

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

    public function food()
    {
        return $this->hasOne(Food::class, 'id', 'food_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
