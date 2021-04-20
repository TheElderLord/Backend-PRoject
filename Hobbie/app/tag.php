<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tag extends Model
{

    public function hobbies() {
        return $this->belongsToMany('App\Hobby');
    }

    public function filteredHobbies() {
        return $this->belongsToMany('App\Hobby')
            ->WherePivot('tag_id',$this->id)
            ->orderBy('updated_at','DESC');
    }

    protected $fillable = [
        'name','style',
    ];
}
