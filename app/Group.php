<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model {
	public $timestamps = false;
    protected $guarded = ['id'];

    public function students() {
    	return $this->hasMany('App\Student');
    }
}
