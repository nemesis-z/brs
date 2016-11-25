<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
	public $timestamps = false;
	protected $guarded = ['id'];

    public function _name() {
        return $this->last.' '.mb_substr($this->first,0,1,'utf-8').'. '.mb_substr($this->mid,0,1,'utf-8').'.';
    }
	
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
