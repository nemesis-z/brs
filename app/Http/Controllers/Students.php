<?php

namespace App\Http\Controllers;

use App;
use App\Facades\Helper;
use Illuminate\Support\Facades\Auth;

class students extends Controller {
	public function getGroups() {
		$gg = App\Group::orderBy('year','desc')->orderBy('name')->get();
		$groups = array();
		$k = '';
		foreach($gg as $g) {
			preg_match('/^[а-яА-Я]+/u', $g->name, $arr);
			$tmpk = count($arr)?$arr[0]:'Неизвестно';
			if($k!=$tmpk) {
				$k = $tmpk;
				$groups[] = array('key'=>$k,'gs'=>array());
			}
			$groups[count($groups)-1]['gs'][] = $g;
		}
		return view('students.groups',array('groups'=>$groups));
	}
	public function getLessons($group) {
		$tgls = App\Tgl::with('lesson')->where(array('group_id'=>$group->id,'sem'=>Helper::sem($group->year)))->groupBy('lesson_id')->get();
		if(!$tgls->count())abort(404);
		return view('students.lessons',array('lessons'=>$tgls->map(function($tgl){return $tgl->lesson;})->unique('id')->sortBy('name'),'gid'=>$group->id));
	}
	public function getList($group,$lesson) {
		$tgls = App\Tgl::with('dates')->where(array('group_id'=>$group->id,'lesson_id'=>$lesson->id,'sem'=>Helper::sem($group->year)))->orderBy('c')->get();
		if(!$tgls->count())abort(404);
		return view('students.list',array_merge(Helper::getMarks($group,$lesson,$tgls),array('lesson_id'=>$lesson->id,'group_id'=>$group->id)));
	}
}