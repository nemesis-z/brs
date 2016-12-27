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
	public function max($g=0,$type=false) {
		$i=0;
		$max = array(15,20,15,15,20,15,20,0,20);
		if(in_array($g, array(36,94,561,66,317,308,1116,28,1166,361,146,507,354,511,512,1148)))$max = array(10,25,15,10,25,15,20,0,20);
		$ret = array();
		foreach($this->types(false,true) as $val)$ret[$val] = $max[$i++];
		return $type&&isset($ret[$type])?$ret[$type]:$ret;
	}
	public function closed() {
		return $this->closed;
	}
	public function type($id=null,$z=false) {
		if(!$z)$types = array('экзамен','зачет','дифзачет','курсовая работа','факультатив','курсовой проект');
		else $types = array('экзамена','зачета','дифзачета','курсовой работы','факультатива','курсового проекта');
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
	private function onlyMarks(&$group,&$lesson,&$tsa) {
		$ans = array('students'=>array(),'marks'=>array(),'maxs'=>$this->max($lesson->id));
		$sem = $this->sem($group->year);
		$group->students->load(array('marks'=>function($q) use($lesson,$sem) {
			$q->where(array('lesson_id'=>$lesson->id,'sem'=>$sem))->orderBy('type');
		}));
		foreach($group->students->sortBy('last') as $student) {
			$max = self::max($group->id,'p');
			for($i=0;$i<2;$i++) {
				$ndx = !$i?'first':'mid';
				if(strpos($student[$ndx], '-')!==false) {
					$ex = explode('-', $student[$ndx]);
					$student[$ndx] = mb_substr($ex[0], 0, 1).'-'.mb_substr($ex[1], 0, 1).'.';
				} else $student[$ndx] = mb_substr($student[$ndx], 0, 1).'.';
			}
			$student['name']=$student->last.' '.$student->first.' '.$student->mid;
			$ans['students'][] = $student->toArray();
			$_marks = $student->marks;//App\Mark::where(['student_id'=>$student->id,'lesson_id'=>$lesson->id,'sem'=>$sem])->orderBy('type')->get();
			$marks = array('avg'=>array('mark'=>0));
			$skip = false;
			$_marks->each(function($mark) use(&$marks,&$skip) {
				if($mark->type=='p')$skip = true;
				$marks[$mark->type]=$mark;
				$marks['avg']['mark']+=$mark->mark;
			});
			if(!$skip) {
				$marks['p'] = array();
				$marks['p']['mark'] = isset($tsa['j'.$student->id])?$tsa['j'.$student->id]:$max;
				$marks['p']['auto'] = 1;
				$marks['avg']['mark'] += $marks['p']['mark'];
			}
			$ans['marks'][] = $marks;

		}
		return $ans;
	}
	public function getMarks($group,$lesson,$teacher=false) {
		$tgls = App\Tgl::with('dates')->where(array('group_id'=>$group->id,'lesson_id'=>$lesson->id,'sem'=>Helper::sem($group->year)))->orderBy('c')->get();
		if(!$tgls->count())return false;
		$ans = array('lec'=>true,'jjs'=>array(),'types'=>$this->types(false,true),'lid'=>$lesson->id);
		$tsa = array();
		$avg = 0;
		$init = true;
		$tgls->each(function($tgl) use(&$ans,&$teacher,&$tsa,&$avg,&$init) {
			if($teacher&&$init) {
				$init = false;
				if($tgl->user_id!=$teacher->id)$ans['lec']=false;
			}
			$tgl->dates->load('marks');
			$arr = array('c'=>$tgl->c,'info'=>$this->c($tgl->c),'dates'=>$tgl->dates->toArray());
			$marks = array();
			$tgl->dates->each(function($date) use(&$marks,&$tsa) {
				$date->marks->each(function($mark) use(&$marks,&$tsa) {
					$marks[$mark->student_id.$mark->jdate_id] = $mark->mark;
					if(!isset($tsa['j'.$mark->student_id]))$tsa['j'.$mark->student_id]=1;
					else $tsa['j'.$mark->student_id]++;
				});
			});
			$avg += $tgl->dates->count();
			$arr['marks'] = $marks;
			if(!$teacher||$tgl->user_id==$teacher->id)$ans['jjs'][] = $arr;
		});
		dump($tsa);
		$max = self::max($group->id,'p');
		foreach ($tsa as $key => $v) {
			$v = $avg - $v;
			if($v<1) {
				$tsa[$key] = 0;
				continue;
			}
			$tsa[$key] = round($v/$avg*$max);
		}
		$ans = array_merge($ans,self::onlyMarks($group,$lesson,$tsa));
		return $ans;
	}
}

?>