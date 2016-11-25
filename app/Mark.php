<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mark extends Model {
	protected $guarded = ['id'];
	protected $hidden = ['student_id','teacher_id','sem','created_at','updated_at'];
}
