<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PlaceType;
use App\Models\Trek;

class InterestingPlace extends Model
{

    protected $fillable = [
        'name',
        'place_type_id',

    ];
    public function placeType()
    {
        return $this->belongsTo(PlaceType::class);
    }

    public function treks()
    {
        return $this->belongsToMany(Trek::class)->withPivot('order')->withTimestamps();
    }
}
