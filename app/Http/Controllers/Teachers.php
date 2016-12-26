<?
namespace App\Http\Controllers;

use App;
use Validator;
use App\Facades\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class teachers extends Controller {

	private $user;

	private function logout($log = 'дурак')  {
		$this->user->warns++;
		$this->user->save();
		DB::table('tlogs')->insert(['teacher_id'=>$this->user->id,'log'=>$log]);
		Auth::logout();
		return response()->json(['err'=>1,'logout'=>1]);
	}
	private function err($msg='Неизвестная ошибка') {
		return response()->json(['err'=>1,'msg'=>$msg]);
	}

	function __construct(Request $request) {
		$this->user = $request->user();
	}
	
	public function getLessons() {
		if(session('greeting'))$teacher = null;
		else {
			session(['greeting'=>true]);
			$teacher = $this->user->last.' '.mb_substr($this->user->first, 0, 1).'. '.mb_substr($this->user->mid, 0, 1).'.';
		}
		return view('teachers.lessons',array('teacher'=>$teacher,'lessons'=>$this->user->tgls->load('lesson')->map(function($tgl) {return $tgl->lesson;})->unique('id')->sortBy('name')));
	}

	public function getGroups(App\Lesson $lesson) {
		return view('teachers.groups',array('lid'=>$lesson->id,'groups'=>$this->user->tgls->where('lesson_id',$lesson->id)->load('group')->map(function($tgl){return $tgl->group;})->unique('id')->sortBy('name')));
	}
	public function getList(App\Lesson $lesson,App\Group $group) {
		// $tgls = $this->user->tgls->where('group_id',$group->id)->where('lesson_id',$lesson->id);
		$adds = Helper::adds();
		//$tgls = App\Tgl::where('tgls.group_id',$group->id)->where('tgls.lesson_id',$lesson->id)->join('groups','groups.id','=','tgls.group_id')->whereRaw("`tgls`.`sem` = ({$adds[0]}-`groups`.`year`)*2+{$adds[1]}")->select('tgls.*')->orderBy('tgls.c');
		$marks = Helper::getMarks($group,$lesson,$this->user);
		if(!$marks)abort(404);
		return view('teachers.list', array_merge($marks,array('lesson'=>$lesson,'group'=>$group)));
	}


	public function setMark(Request $request,App\Lesson $lesson,App\Group $group,App\Student $student) {
		return $this->err('Система временно закрыта');
		// \Illuminate\Support\Facades\DB::enableQueryLog();
		if(Validator::make($request->all(),array('type'=>array('required','regex:'.Helper::types(true)),'mark'=>'required|regex:/^[0-9]+$/'))->fails())return $this->logout('m_regex');
		$d = explode('-', date('Y-n'));
		if($student->limited)return $this->err('Студент не допущен');
		if(!in_array($this->user->id, array(260,71,35)) && $d[1]>10 && ($request->type=='t1'||$request->type=='r1'))return $this->err('Поздно вносить правки за 1-ый семестр :(');
		$max = Helper::max($lesson->id);
		if($request->mark > $max[$request->type])return $this->err('Данная оценка не может быть больше '.$max[$request->type]);
		if($student->group_id!=$group->id)return $this->logout('m:st_grp!=grp');
		$sem = Helper::sem($group->year);
		$data = array('student_id'=>$student->id,'lesson_id'=>$lesson->id,'sem'=>$sem,'type'=>$request->type);
		$mark = App\Mark::where($data)->first();
		if($mark && $mark->mark==$request->mark)return response()->json(array('ok'=>1));
		$tgl = $this->user->tgls->where('group_id',$group->id)->where('lesson_id',$lesson->id)->first();
		if(!$tgl)return $this->logout('m_!tgl');
		if($tgl->c!=1) {
			$check = App\Tgl::where(['group_id'=>$group->id,'lesson_id'=>$lesson->id,'sem'=>$sem])->where('c','<',$tgl->c)->first();
			if($check)return $this->logout('m_access');//$this->err('Вы не имеете права вносить баллы');
		}
		if($mark) {
			$from_ = $mark->mark;
			$mark->mark = $request->mark;
			$mark->save();
			DB::table('remarks')->insert(array('mark_id'=>$mark->id,'a'=>$from_,'b'=>$mark->mark));
		} else {
			$data['user_id'] = $this->user->id;
			$data['mark'] = $request->mark;
			App\Mark::create($data);
		}
		// dump(\Illuminate\Support\Facades\DB::getQueryLog());
		return response()->json(array('ok'=>1));
	}
	public function setJDate(Request $request,App\Lesson $lesson,App\Group $group) {
		if(Validator::make($request->all(),array('id'=>'regex:/^\d+$/','date'=>'required|regex:/^\d{10}$/','c'=>'required|regex:/^[1-3]$/'))->fails())return $this->logout('jd_regex');
		$tgl = $this->user->tgls->where('c',(int)$request->c)->where('group_id',$group->id)->where('lesson_id',$lesson->id)->first();
		if(!$tgl)return $this->logout('jd_!tgl');
		$id=0;
		if($request->has('id')) {
			$jd = App\Jdate::find($request->id);
			if(!$jd)return $this->logout('jd not found');
			if($jd->tgl_id!=$tgl->id)return $this->logout('jd_tgl!=tch_tgl');
			$id = $jd->id;
			$jd->zz = $request->date;
			$jd->save();
		} else $id = App\Jdate::create(array('zz'=>$request->date,'tgl_id'=>$tgl->id))->id;
		return response()->json(array('ok'=>1,'id'=>$id,'fd'=>date('d/m/y',$request->date)));
	}
	public function setJMark(Request $request, App\Student $student,App\Jdate $date) {
		if(Validator::make($request->all(),array('mark'=>'required|numeric'))->fails())return $this->logout('jm_regex');
		if($this->user->id!=$date->tgl->user_id)return $this->logout('jm_!id');
		$jm = $date->marks->where('student_id',$student->id)->first();
		if($jm) {
			$jm->delete();
			// $jm->mark = $mark;
			// $jm->save();
		} else $date->marks()->create(array('student_id'=>$student->id,'mark'=>$request->mark));
		return response()->json(array('ok'=>1));
	}

	public function deleteJDate(App\Jdate $date) {
		if($this->user->id!=$date->tgl->user_id)return $this->logout('djd_!id');
		$date->delete();
		return response()->json(array('ok'=>1));
	}

	public function exportListAll(App\Group $group) {
		return back();
		$name = $group->name;
		$sem = Helper::sem($group->year);
		$data = array('group_name'=>$group->name,'sem'=>$sem);
		$lessons = $group->tgls->unique('lesson_id')->map(function($tgl){return $tgl->lesson;});
		$data['lessons'] = $lessons;
		$data['students'] = $group->students->sortBy('last')->load(array('marks'=>
			function($q) use(&$lessons,$sem) {
				$q->whereIn('lesson_id', $lessons->map(function($l){return $l->id;})
				  ->toArray())
				  ->where('sem',$sem)
				  ->orderBy('type');
			})
		)->each(function($student) {
			$student->ms = $student->marks->groupBy('lesson_id')->map(function($x){
				return $x->sum('mark');
			});
		});
		$data['count'] = $lessons->count();
		Excel::create($name, function($excel) use(&$data) {
			$excel->sheet('New sheet', function($sheet) use(&$data) {
				$sheet->setHeight(array(2=>150));
				$sheet->setWidth(array('A'=>30));
				$sheet->cells('B2:Z2', function($cells) {
		          $cells->setAlignment('center');
		          $cells->setValignment('center');
		          $cells->setTextRotation(90);
		        });
		        $sheet->getStyle('B2:Z2')->getAlignment()->setWrapText(true);
		        $letters = explode(' ', strtoupper('a b c d e f g h i j k l m n o p q r s t u v w x y z'));
		        $sheet->setBorder("A2:".$letters[$data['count']].($data['students']->count()+2), 'thin');
		        $sheet->loadView('xls.all',$data);
			});
		})->export();
		return back();
	}

	public function exportList(App\Group $group, App\Lesson $lesson) {
		// set_time_limit(60);
		// ini_set('memory_limit', '128m');

		$ds = explode(' ', $lesson->name);
		$name = '';
		if(count($ds)>1)for($i=0;$i<count($ds);$i++)$name.=mb_strtoupper(mb_substr($ds[$i], 0, 1));
		else $name = $ds[0];
		$name.=', '.$group->name.', '.Helper::sem($group->year).' семестр';
		Excel::create($name, function($excel) use(&$group,&$lesson) {
		    $excel->sheet('New sheet', function($sheet) use(&$group,&$lesson) {
		    	$sheet->setWidth(array(
				    'A'=>5,
				    'B'=>30,
				    'C'=>18,
				    'D'=>18,
				    'E'=>18,
				    'F'=>18,
				    'G'=>18,
				    'H'=>18,
				    'I'=>18,
				    'J'=>12,
				    'K'=>12,
				    'L'=>12,
				));
				$sheet->setHeight(array(
					10=>55
				));
				$sheet->setFontFamily('Times new roman');
				$sheet->cells('A1:L80', function($cells) {
					$cells->setFontSize(16);
				});
				$tgls = $group->tgls->where('lesson_id',$lesson->id);
				$min=999;
				$tgl = null;
				$tgls->each(function($_tgl) use(&$min,&$tgl) {
					if($_tgl->c>=$min)return;
					$tgl = $_tgl;
					$min = $tgl->c;
					if($min==1)return false;
				});
				// $helper = new PHPExcel_Helper_HTML;
				// $richText = $helper->toRichTextObject('qwe<br>asd');
				// dump($richText);
				$marks=array();
				$marks = \App\Facades\Helper::getMarks($group,$lesson);
				$sem = \App\Facades\Helper::sem($group->year);
				$ms = array('','января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря');
				$names = array('акультета Автоматизации и прикладной информатики','Нефтетехнологического факультета','Геолого-промыслового факультета','','Нефтемеханического факультета','факультета Экономики и управления','Строительного факультета');
				$data = array_merge($marks,
					array('name'=>$this->user->last.' '.$this->user->first.' '.$this->user->mid,
						'date'=>'«'.date('d').'» '.$ms[date('n')].' '.date('Y').'г.',
						'sem'=>$sem,'zz'=>$names[$group->fac],'group'=>$group,'lesson'=>$lesson,'v'=>Helper::type($tgl->type,!0)));
		        $sheet->loadView('xls.sheet',$data);
		        $sheet->setBorder('A8:K'.(count($data['students'])+12), 'thin');
		    });

		})->export('xls');
		// dump(123);
	}
}

?>