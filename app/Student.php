<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model {
	public $timestamps = false;
	protected $guarded = ['id'];
	
	public function marks() {
    	return $this->hasMany('App\Mark');
    }
}
