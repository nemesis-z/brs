<?

namespace App\Http\Controllers;

use App;
use Validator;
use App\Facades\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class admins extends Controller {

	private $user;
	private $acts = array();

	function __construct(Request $request) {
		$this->user = $request->user();
	}
	private function log($a,$b=null,$error=0) {
		DB::table('alogs')->insert(['admin_id'=>$this->user->id,'a'=>$a,'b'=>$b,'is_error'=>$error]);
	}

	// adds
	private function add_lesson($request) {
		$v = Validator::make($request->all(), ['name'=>'required|unique:lessons|regex:/^[А-Я а-я\-]+$/u']);
		if($v->fails()) {
			$this->log('add_lesson',$request->name,1);
			return back()->with('msg',$v->errors()->first('name'));
		}
		$data = App\Lesson::create($request->all());
		$this->log('add_lesson',$request->name);
		return back()->with('msg','Предмет успешно добавлен');
	}
	private function add_teacher($request) {
		$v = Validator::make($request->all(), [
				'name'=>'required|unique:users|regex:/^[A-Za-z0-9_]+$/',
				'email'=>'email',
				'_pass'=>'required|regex:/^[A-Za-z0-9_]+$/',
				'first'=>'required|regex:/^[А-Яа-я]+$/u',
				'last'=>'required|regex:/^[А-Яа-я]+$/u',
				'mid'=>'required|regex:/^[А-Яа-я]+$/u'
			]);
		if($v->fails()) {
			if($v->errors()->has('name'))return back()->with('msg','Логин уже занят');
			$this->log('add_teacher',null,1);
			return back()->with('msg','Неправильно введены данные');
		}
		$request->merge(['password'=>bcrypt($request->_pass)]);
		$data = App\User::create($request->all());
		$this->log('add_teacher',$data->id);
		return back()->with('msg','Преподаватель успешно добавлен');
	}
	private function add_group($request) {
		if($this->user->admin<0)return back()->with('msg','Вы не имеете прав на данное действие');
		$v = Validator::make($request->all(),['name'=>'required|unique:groups|regex:/^[А-Яа-я\-0-9]+$/u','year'=>'required|regex:/^201[0-9]$/']);
		if($v->fails()) {
			if($v->errors()->has('name'))return back()->with('msg','Такая группа уже есть в базе');
			$this->log('add_group',$request->name.' => '.$request->year,1);
			return back()->with('msg','Неправильно введены данные');
		}
		$request->merge(['fac'=>$this->user->admin]);
		$data = App\Group::create($request->all());
		$this->log('add_group',$data->id);
		return back()->with('msg','Группа успешно добавлена');
	}

	//
	public function add_tgl(Request $request, App\User $teacher) {
		$sv = 'required|regex:/^\d+$/';
		if(Validator::make($request->all(),array('lesson_id'=>$sv,'group_id'=>$sv,'type'=>$sv,'c'=>$sv))->fails()) {
			$this->log('add_tgl',null,1);
			return back()->with('msg','Неправильно введены данные');
		}
		$group = App\Group::find($request->group_id);
		if(!$group||$this->user->admin!=$group->fac) {
			if(!$group)$this->log('add_tgl','group_not_found:'.$request->group_id,1);
			else $this->log('add_tgl','_fac',1);
			Auth::logout();
			return redirect('/');
		}
		$sem = Helper::sem($group->year);
		$request->merge(array('sem'=>$sem));
		$check = App\Tgl::where($request->except('_token'))->first();
		if($check)return back()->with('teacher', $check->user_id);
		try {
			$tgl = $teacher->tgls()->create($request->all());
		} catch (\Illuminate\Database\QueryException $e) {
			$this->log('add_tgl','pdo',1);
			Auth::logout();
			return redirect('/');
        }
        $this->log('add_tgl',$tgl->id);
		return back();
	}
	public function add_student(Request $request,App\Group $group) {
		if($this->user->admin!=$group->fac) {
			$this->log('add_student','_fac',1);
			Auth::logout();
			return redirect('/');
		}
		$data = $request->all();
		foreach($data as $key => $value)$data[$key] = trim($value);
		$sv = 'required|regex:/^[А-Я\-а-я]+$/u';
		if(Validator::make($data,['first'=>$sv,'last'=>$sv,'mid'=>$sv,'number'=>'required|regex:/^[А-Яа-я0-9]+$/u'])->fails()) {
			$this->log('add_student',null,1);
			return back()->with('msg','Неправильно введены данные');
		}
		$student = $group->students()->create($request->all());
		$this->log('add_student',$student->id);
		return back()->with('msg','Студент успешно добавлен');
	}

	//
	public function toggle_student(App\Student $student) {
		$sem = Helper::sem($student->group->year);
		$check = App\Limit::where(array('student_id'=>$student->id,'sem'=>$sem))->first();
		if($check)$check->delete();
		else $student->limited()->create(array('sem'=>$sem));
		return back();
	}

	// deletes 

	public function delete_student(App\Student $student) {
		if($student->group->fac!=$this->user->admin) {
			$this->log('delete_student','_fac',1);
			Auth::logout();
			return redirect('/');
		}
		$student->delete();
		return back()->with('msg', 'Студент успешно удален');
	}

	public function delete_tgl(App\Tgl $tgl) {
		if($tgl->group->fac!=$this->user->admin) {
			$this->log('delete_tgl','_fac',1);
			Auth::logout();
			return redirect('/');
		}
		$tgl->delete();
		return back()->with('msg', 'Предмет успешно откреплен');
	}

	//pages
	public function lesson(App\Lesson $lesson) {
		$adds = Helper::adds();
		$teachers = App\Tgl::join('groups','groups.id','=','tgls.group_id')->join('users','users.id','=','tgls.user_id')->whereRaw("`tgls`.`sem` = ({$adds[0]}-`groups`.`year`)*2+{$adds[1]}")->select('users.*')->where('tgls.lesson_id',$lesson->id)->where('groups.fac',$this->user->admin)->groupBy('users.id')->orderBy('users.last')->get();
		return view('admins.lesson',['lesson'=>$lesson,'teachers'=>$teachers]);
	}
	public function group(App\Group $group) {
		// \Illuminate\Support\Facades\DB::enableQueryLog();
		if($this->user->admin!=$group->fac) {
			$this->log('group','_fac',1);
			Auth::logout();
			return redirect('/');
		}
		return view('admins.group', ['tgls'=>$group->tgls->load('lesson')->sortBy('lesson.name')->load('user'),'group'=>$group,'students'=>$group->students->load('limited')]);
	}
	public function teacher(App\User $teacher) {
		$add = array();
		$add['lessons'] = App\Lesson::all()->sortBy('name');
		$add['groups'] = App\Group::where('fac',$this->user->admin)->orderBy('name')->get();
		$add['types'] = Helper::type();
		$add['cs'] = Helper::c();
		$tgls = $teacher->tgls->load(['group'=>function($q) {
			$q->where('fac',$this->user->admin);
		}])->load('lesson')->sortBy('lesson.name');
		return view('admins.teacher', ['add'=>$add,'teacher'=>$teacher,'tgls'=>$tgls->filter(function($t){return $t->group!=null;})]);
	}

	public function main() {
		$fac = $this->user->admin;
		$fac = $fac<0?0:$fac;
		$adds = Helper::adds();
		$lessons = App\Tgl::join('lessons', 'lessons.id', '=', 'tgls.lesson_id')->join('groups', 'groups.id', '=', 'tgls.group_id')->whereRaw("`tgls`.`sem` = ({$adds[0]}-`groups`.`year`)*2+{$adds[1]}")->select('lessons.*')->groupBy('lessons.id')->orderBy('lessons.name')->get();
		return view('admins.main', ['teachers'=>App\User::where('admin',0)->orderBy('last')->get(), 'lessons'=>$lessons, 'groups'=>App\Group::where('fac',!$fac?'>=':'=',$fac)->orderBy('name')->orderBy('year','desc')->get()]);
	}

	//cast
	public function addToCategory(Request $request, $cat) {
		return call_user_func(array($this,'add_'.$cat), $request);
	}
}

?>