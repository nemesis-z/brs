<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tgl extends Model {
    public $timestamps = false;
    protected $guarded = ['id','num','hrs','active'];

    public function lesson() {
        return $this->belongsTo('App\Lesson');
    }
    public function group() {
    	return $this->belongsTo('App\Group');
    }
    public function dates() {
    	return $this->hasMany('App\Jdate');
    }
}
