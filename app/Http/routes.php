<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use App\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

// нтф - worplan.xls - хуйня

/*function rp() { 
	$pass=""; 
	$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789"; 
	for ($i = 0; $i < 8; $i++)$pass .= substr($alphabet, rand(0, strlen($alphabet)-1),1); 
	return $pass; 
}*/

Route::get('/', function () {
	/*Excel::batch('_gpf_e', function($reader, $file) {
		return;
		$reader->noHeading();
		$x = -1;
		$g = null;
		$cyr = array('а','б','в','г','д','е', 'ё', 'ж','з','и','й','к','л','м','н','о','п','р','с','т','у', 
'ф','х','ц','ч','ш','щ','ъ','ы','ь', 'э', 'ю','я','А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У', 
'Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э', 'Ю','Я',' ' ); 
	$lat = array( 'a','b','v','g','d','e','e', 'zh','z','i','y','k','l','m','n','o','p','r','s','t','u', 
'f' ,'h' ,'ts' ,'ch','sh' ,'sh' ,'a' ,'i', 'y' ,'e','yu' ,'ya','A','B','V','G','D','E','E','Zh', 
'Z','I','Y','K','L','M','N','O','P','R','S','T','U', 
'F' ,'H' ,'Ts' ,'Ch','Sh' ,'Sh' ,'A' ,'I', 'Y' ,'E', 'Yu' ,'Ya','' );
		$reader->each(function($row) use(&$x, &$g, $cyr, $lat, $file) {
			if($x!=999)++$x;
			if($x!=4&&$x<11)return;
			if($x==11&&$row[2]!=1)dump('err: '.$file);
			if(!$row[2]) {
				$x=999;
				return;
			}
			if($x==999)return dump('999');
			if($x==4) {
				$name = explode(": ", $row[2]);
				$name = $name[1];
				$g = App\Group::where('name',$name)->first();
				if(!$g)dump('group: '.$name);
				return;
			}
			$lesson = App\Lesson::firstOrCreate(array('name'=>$row[3]));
			$arr = array($row[6],$row[8],$row[10]);
			$type = -1;
			$tmp = array(16,18,17,20,22,19);
			for($i=0;$i<count($tmp);$i++)if($row[$tmp[$i]]) {
				$type=$i+1;
				break;
			}
			if($type==-1)return;// dump($row->toArray());
			if(!$g)return;
			for($i=0;$i<3;$i++) {
				$name = $arr[$i];
				if(!$name)continue;
				$flm = explode(" ", $name);
				if(count($flm)!=3) {
					// dump('name: '.$name);
					continue;
				}
				$last = $flm[0];
				$fm = explode('.', $flm[1]);
				$login=strtolower(str_replace($cyr,$lat,mb_convert_encoding($last.$fm[0].$fm[1], 'utf-8')));
				$user = App\User::where('name',$login)->first();
				if(!$user) {
					$pass = rp();
					$user = new App\User(array(
						'name'=>$login,
						'password'=>bcrypt($pass),
						'first'=>$fm[0],
						'last'=>$last,
						'mid'=>$fm[1],
						'_pass'=>$pass
					));
					// $user->save();
				}
				$tgl = new App\Tgl(array(
					'user_id'=>$user->id,
					'group_id'=>$g->id,
					'lesson_id'=>$lesson->id,
					'type'=>$type,
					'c'=>$i+1,
					'num'=>$row[2],
					'sem'=>\App\Facades\Helper::sem($g->year)
				));
				// $tgl->save();
			}
		});
	});
	return;
	Excel::load('asd.xlsx', function($reader) {
		return;
		// фапи
		$reader->noHeading();
		$g = null;
		$reader->each(function($sheet) use(&$g) {
			$sheet[1] = str_replace(' ', '', $sheet[1]);
			$sheet[2] = trim($sheet[2]);
			if(!$sheet[1])return;
			if(!$sheet[2]) {
				if(preg_match('/^\d/', $sheet[1]))return;
				preg_match('/^([а-яА-Я]+-)(\d+)(.*?)-?([а-яА-Я]*)$/',mb_strtoupper($sheet[1],'utf-8'),$name);
				$name = $name[1].$name[2].$name[3].mb_strtolower($name[4],'utf-8');
				preg_match('/\d+/', $name, $m);
				$year = "20$m[0]";
				$g = new App\Group(array('name'=>$name,'year'=>$year,'fac'=>1));
				// $g->save();
				return;
			}
			if(!$g)return dump('err');
			$row = explode(' ', $sheet[2]);
			if(count($row)!=3) {
				dump($g->name);
				dump($sheet[2]);
				return;
			}
			$s = new App\Student(array(
				'group_id'=>$g->id,
	    		'first'=>$row[1],
	    		'last'=>$row[0],
	    		'mid'=>$row[2],
	    		'number'=>(string)$sheet[3]
	    	));
			// $s->save();
		});
	});
	// return;
	Excel::load('e.xlsx', function($reader) {
		return;
		// хз
		$reader->noHeading();
		$g = null;
		$reader->each(function($sheet) use(&$g) {
			if(!$sheet[1])return;
			$sheet[1] = trim($sheet[1]);
			if(strlen($sheet[1])<20) {
				preg_match('/^([а-яА-Я]+-)(\d+)(.*?)-?([а-яА-Я]*)$/',mb_strtoupper($sheet[1],'utf-8'),$name);
				$name = $name[1].$name[2].$name[3].mb_strtolower($name[4],'utf-8');
				preg_match('/\d+/', $name, $m);
				$year = "20$m[0]";
				$g = new App\Group(array('name'=>$name,'year'=>$year,'fac'=>5));
				// $g->save();
				return;
			}
			if(!$g)return dump('err');
			$row = explode(' ', $sheet[1]);
			$s = new App\Student(array(
				'group_id'=>$g->id,
	    		'first'=>$row[1],
	    		'last'=>$row[0],
	    		'mid'=>$row[2],
	    		'number'=>''
	    	));
			// $s->save();
		});
	});
	// return;
	Excel::load('d.xlsx', function($reader) {
		return;
		// нтф
		$reader->noHeading();
		$reader->each(function($sheet) {
			preg_match('/^([а-яА-Я]+-)(\d+)(.*?)-?([а-яА-Я]*)$/',mb_strtoupper($sheet->getTitle(),'utf-8'),$name);
			$name = $name[1].$name[2].$name[3].mb_strtolower($name[4],'utf-8');
			
			preg_match('/\d+/', $name, $m);
			$year = "20$m[0]";
			$g = new App\Group(array('name'=>$name,'year'=>$year,'fac'=>2));
			// $g->save();
			dump($g->id);
			$sheet->each(function($row) use(&$g) {
				if(!$row[2])return;
				$s = new App\Student(array(
					'group_id'=>$g->id,
		    		'first'=>$row[2],
		    		'last'=>$row[1],
		    		'mid'=>$row[3],
		    		'number'=>(string)$row[4]
		    	));
		    	// $s->save();
			});
		});
	});
	// return;
	Excel::load('c.xlsx', function($reader) {
		return;
		// фэу
		$reader->noHeading();
		$reader->each(function($sheet) {
			preg_match('/^([а-яА-Я]+-)(\d+)(.*?)-?([а-яА-Я]*)$/',mb_strtoupper($sheet->getTItle(),'utf-8'),$name);
			$name = $name[1].$name[2].$name[3].mb_strtolower($name[4],'utf-8');
			preg_match('/\d+/', $name, $m);
			$year = "20$m[0]";
			$g = new App\Group(array('name'=>$name,'year'=>$year,'fac'=>6));
			// $g->save();
			// dump($g->id);
			$sheet->each(function($row) use(&$g) {
				if(!$row[7])return;
				$arr = explode(" ", $row[1]);
				if(count($arr)!=3)return dump($arr);
				$s = new App\Student(array(
					'group_id'=>$g->id,
		    		'first'=>$arr[1],
		    		'last'=>$arr[0],
		    		'mid'=>$arr[2],
		    		'number'=>(string)$row[7]
		    	));
		    	// $s->save();
		    });
		});
	});
	// return;
	Excel::load('b.xlsx', function($reader) {
		return;
		// гф
		$reader->noHeading();
		$reader->each(function($sheet) {
			$g = null;
			$sheet->each(function($row) use(&$g) {
				if(!$row[2]) {
					preg_match('/^([а-яА-Я]+-)(\d+)(.*?)-?([а-яА-Я]*)$/',mb_strtoupper($row[1],'utf-8'),$name);
					$name = $name[1].$name[2].$name[3].mb_strtolower($name[4],'utf-8');
					preg_match('/\d+/', $name, $m);
					$year = "20$m[0]";
					$g = new App\Group(array('name'=>$name,'year'=>$year,'fac'=>4));
					// $g->save();
					dump($g->id);
					return;
				}
				if(!$g)return dump('err');
				$arr = explode(" ", $row[1]);
				if(count($arr)!=3)return dump($arr);
				$s = new App\Student(array(
					'group_id'=>$g->id,
		    		'first'=>$arr[1],
		    		'last'=>$arr[0],
		    		'mid'=>$arr[2],
		    		'number'=>(string)$row[2]
		    	));
				// $s->save();
			});
		});
	});
	// return;
	Excel::load('a.xlsx', function($reader) {
		return;
		//гпф
		$reader->each(function($sheet) {
			preg_match('/^([а-яА-Я]+-)(\d+)(.*?)-?([а-яА-Я]*)$/',mb_strtoupper($sheet->getTitle(),'utf-8'),$name);
			$name = $name[1].$name[2].$name[3].mb_strtolower($name[4],'utf-8');
			preg_match('/\d+/', $name, $m);
			$year = "20$m[0]";
			$g = new App\Group(array('name'=>$name,'year'=>$year,'fac'=>3));
			// $g->save();
		    // dump($g->id);
		    $sheet->each(function($row) use(&$g) {
		    	if(!$row->imya)return;
				$s = new App\Student(array(
					'group_id'=>$g->id,
		    		'first'=>$row->imya,
		    		'last'=>$row->familiya,
		    		'mid'=>$row->otchestvo,
		    		'number'=>(string)$row->zk
		    	));
				// $s->save();
		    });
		});
	});
	return;*/
	if(Auth::guest())return redirect('/login');
	else return redirect('/teacher');
});

Route::get('/login', 'Auth\AuthController@getLogin');
Route::post('/login', 'Auth\AuthController@postLogin');
Route::get('/logout', 'Auth\AuthController@getLogout');

Route::group(['middleware' => 'reqs'], function() {
	Route::post('/mark/{lesson}/{group}/{student}','teachers@setMark');
	Route::post('/date/{lesson}/{group}','teachers@setJDate');
	Route::post('/jmark/{student}/{date}','teachers@setJMark');
});

Route::group(['prefix' => 'teacher', 'middleware' => 'auth'], function() {
	Route::get('/', 'teachers@getLessons');
	Route::get('/lesson/{lesson}', 'teachers@getGroups');
	Route::get('/lesson/{lesson}/group/{group}','teachers@getList');
});

Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function() {
	Route::get('/', 'admins@main');
	Route::post('/add/{cat}', 'admins@addToCategory')->where('cat','teacher|lesson|group');
	Route::get('/lesson/{lesson}', 'admins@lesson');
	Route::get('/group/{group}', 'admins@group');
	Route::get('/teacher/{teacher}', 'admins@teacher');
	Route::get('/toggle/{student}', 'admins@toggle_student');
	Route::post('/group/{group}/add_student', 'adminss@add_student');
	Route::post('/teacher/{teacher}/add', 'admins@add_tgl');
});

Route::group(['prefix' => 'student'], function() {
	Route::get('/', 'students@getGroups');
	Route::get('/group/{group}', 'students@getLessons');
	Route::get('/group/{group}/lesson/{lesson}','students@getList');
});