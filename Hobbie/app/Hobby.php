<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hobby extends Model
{
    public function user() {
        return $this->belongsTo('App\User');
    }

    public function tags() {
        return $this->belongsToMany('App\Tag');
    }
   
    protected $fillable = [
        'name', 'description','user_id',
    ];
}
