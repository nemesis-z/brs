<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jdate extends Model {
	protected $guarded = ['id'];

	public function marks() {
		return $this->hasMany('App\Jmark');
	}
	public function tgl() {
		return $this->belongsTo('App\Tgl');
	}
}
