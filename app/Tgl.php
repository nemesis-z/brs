<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Facades\Helper;

class Tgl extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];

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
    public function user() {
        return $this->belongsTo('App\User');
    }
    public function type_name() {
        return Helper::type($this->type);
    }
    public function c_name() {
        return Helper::c($this->c);
    }
}
