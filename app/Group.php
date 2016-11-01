<?php

namespace App;

use App\Facades\Helper;
use Illuminate\Database\Eloquent\Model;

class Group extends Model {
	public $timestamps = false;
    protected $guarded = ['id'];

    public function students() {
    	return $this->hasMany('App\Student');
    }
    public function tgls() {
    	$adds = Helper::adds();
    	return $this->hasMany('App\Tgl')->whereRaw("`tgls`.`sem` = ({$adds[0]}-".$this->year.")*2+{$adds[1]}")->select('tgls.*')->orderBy('tgls.c');
    }
}
