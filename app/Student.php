<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model {
	public $timestamps = false;
	protected $guarded = ['id'];
	
	public function marks() {
    	return $this->hasMany('App\Mark');
    }
    public function limited() {
    	return $this->hasOne('App\Limit');
    }
    public function group() {
    	return $this->belongsTo('App\Group');
    }
}
