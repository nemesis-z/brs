<?
namespace App\Classes;

use App;

// 1 - фапи, 2 - нтф, 3 - гпф, 6 (4) - гф, 5 - нмф, 6 - фэу, 7 - сф

class Helper {
	private $back;
	private $closed = false;
	function __construct() {
		$this->back = (int)env('SEM_BACK', 0);
	}
	public function fac_name($ndx) {
		$names = array(1,2,3,4,5,6,7);
		return isset($names[$ndx])?$names[$ndx]:'Неизвестно';
	}
	public function types($regex = false, $avg = false) {
		$ret = array('t1','r1','p','t2','r2','s2','d','pr');
		if($avg) {
			$c = count($ret);
			$tmp = $ret[$c-1];
			$ret[$c-1] = 'avg';
			$ret[$c] = $tmp;
		}
		return !$regex?$ret:'/^('.(implode('|',$ret)).')$/';
	}
	public function max() {
		$i=0;
		$max = array(15,20,15,15,20,15,20,20);
		$ret = array();
		foreach($this->types() as $val)$ret[$val] = $max[$i++];
		return $ret;
	}
	public function closed() {
		return $this->closed;
	}
	public function type($id=null) {
		$types = array('экзамен','зачет','дифзачет','курсовая работа','факультатив','курсовой проект');
		if($id===null)return $types;
		return --$id<count($types)?$types[$id]:'';
	}
	public function c($id=null) {
		$cs = array('лекция','практика','лаб.');
		if($id===null)return $cs;
		return --$id<count($cs)?$cs[$id]:'';
	}
	public function adds() {
		$d = explode('-', date('Y-n'));
		$y = (int)$d[0];
		$add = -$this->back;
		if($d[1]>8)$add++;
		return array($y,$add);
	}
	public function sem($year) {
		$d = explode('-', date('Y-n'));
		$sem = ($d[0]-$year)*2-$this->back;
		if($d[1]>8)$sem++;
		return $sem;
	}
	public function getMarks($group,$lesson,$tgls,$teacher=false) {
		$ans = array('lec'=>false,'students'=>array(),'marks'=>array(),'jjs'=>array(), 'types'=>$this->types(false,true));
		$tgls->each(function($tgl) use(&$ans) {
			if($tgl->c==1)$ans['lec']=true;
			$tgl->dates->load('marks');
			$arr = array('c'=>$tgl->c,'info'=>$this->c($tgl->c),'dates'=>$tgl->dates->toArray());
			$marks = array();
			$tgl->dates->each(function($date) use(&$marks) {
				$date->marks->each(function($mark) use(&$marks) {
					$marks[$mark->student_id.$mark->jdate_id] = $mark->mark;
				});
			});
			$arr['marks'] = $marks;
			$ans['jjs'][] = $arr;
		});
		$sem = $this->sem($group->year);
		$group->students->load(array('marks'=>function($q) use($lesson,$sem) {
			$q->where(array('lesson_id'=>$lesson->id,'sem'=>$sem))->orderBy('type');
		}));
		foreach($group->students as $student) {
			for($i=0;$i<2;$i++) {
				$ndx = !$i?'first':'mid';
				if(strpos($student[$ndx], '-')!==false) {
					$ex = explode('-', $student[$ndx]);
					$student[$ndx] = mb_substr($ex[0], 0, 1).'.-'.mb_substr($ex[1], 0, 1).'.';
				} else $student[$ndx] = mb_substr($student[$ndx], 0, 1).'.';
			}
			$student['name']=$student->last.' '.$student->first.' '.$student->mid;
			$ans['students'][] = $student->toArray();
			$_marks = $student->marks;//App\Mark::where(['student_id'=>$student->id,'lesson_id'=>$lesson->id,'sem'=>$sem])->orderBy('type')->get();
			$marks = array('avg'=>array('mark'=>0));
			$_marks->each(function($mark) use(&$marks) {
				$marks[$mark->type]=$mark;
				$marks['avg']['mark']+=$mark->mark;
			});
			$ans['marks'][] = $marks;

		}
		return $ans;
	}
}

?>