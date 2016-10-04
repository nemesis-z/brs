<?php

namespace App;

use App\Facades\Helper;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable {
	// public $timestamps = false;
    protected $fillable = ['name', 'email', 'password', '_pass', 'first', 'last', 'mid'];
    protected $hidden = ['password', 'remember_token', '_pass', 'send'];
    

    public function isAdmin() {
        return $this->admin != 0;
    }

    public function tgls() {
        $adds = Helper::adds();
    	return $this->hasMany('App\Tgl')->join('groups','groups.id','=','tgls.group_id')->whereRaw("`tgls`.`sem` = ({$adds[0]}-`groups`.`year`)*2+{$adds[1]}")->select('tgls.*')->orderBy('tgls.c');
    }
}
