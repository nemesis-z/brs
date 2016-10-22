<?
namespace App\Http\Controllers;

use App;
use Validator;
use App\Facades\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
		$tgls = $this->user->tgls->where('group_id',$group->id)->where('lesson_id',$lesson->id);
		if(!$tgls)abort(404);
		return view('teachers.list', array_merge(Helper::getMarks($group,$lesson,$tgls),array('lesson'=>$lesson,'group'=>$group)));
	}


	public function setMark(Request $request,App\Lesson $lesson,App\Group $group,App\Student $student) {
		// \Illuminate\Support\Facades\DB::enableQueryLog();
		if(Validator::make($request->all(),array('type'=>array('required','regex:'.Helper::types(true)),'mark'=>'required|regex:/^[0-9]+$/'))->fails())return $this->logout('m_regex');
		$d = explode('-', date('Y-n'));
		if($student->limited)return $this->err('Студент не допущен');
		if($d[1]<8 && $request->type<'d')return $this->err('Поздно вносить правки за 1-ый семестр :(');
		$max = Helper::max();
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
}

?>