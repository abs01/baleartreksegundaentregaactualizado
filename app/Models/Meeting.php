<?php

namespace App\Models;

use App\Models\Comment;
use App\Models\Trek;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = [
      'user_id',
      'trek_id',
      'day',
      'time',
      'appDateIni',
      'appDateEnd',
    ];

    public function trek()
    {
        return $this->belongsTo(Trek::class);
    }


    public function comments()
    {
      return $this->hasMany(Comment::class);
    }

    public function user()
    {
      return $this->belongsTo(User::class);
    }

    public function users()
    {
      return $this->belongsToMany(User::class);
    }

    public function calculaMitjana()
    {
      return $this->hasMany(Comment::class)->where('status', 'y')->avg('score');
    }
}
