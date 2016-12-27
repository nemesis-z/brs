@extends('app')
@section('title', 'Группа: '.$group->name)
@section('nav')
	<a href="/admin" class="waves-effect waves-light breadcrumb">Главная</a>
@endsection
@section('style')
	.std {
		padding:10px;
	}
	.std span {
		margin-left: 20px;
	    float: left;
	    min-width: 300px;
	    display:block;
	}
	.std a {
		margin-right:10px;
	}
@endsection	
@section('content')
	<h5>{{$group->name}}</h5>
	<form action="/admin/group/{{$group->id}}/add_student" method="post" class="row">
		{!!csrf_field()!!}
		<div class="input-field col s3">
			<input type="text" name="last">
			<label>Фамилия</label>
		</div>
		<div class="input-field col s3">
			<input type="text" name="first">
			<label>Имя</label>
		</div>
		<div class="input-field col s3">
			<input type="text" name="mid">
			<label>Отчество</label>
		</div>
		<div class="input-field col s3">
			<input type="text" name="number">
			<label>Номер зачетки</label>
		</div>
		<button class="btn-flat waves-effect waves-light" type="submit">Добавить</button>
	</form>
	<div style="text-align: center;">
		<a href="/teacher/get/all/{{$group->id}}">Получить все баллы группы</a>
	</div>
	<h4>Студенты</h4>
	<?$i=0;?>
	@forelse($students as $student)
		<div class="std">
			<span>{{++$i}}) {{$student->last}} {{$student->first}} {{$student->mid}}</span>
			<a href="/admin/toggle/{{$student->id}}">{{$student->limited?'Не допущен':'Допущен'}}</a>
			<a href="/admin/delete/student/{{$student->id}}">Удалить</a>
		</div>
	@empty
		<span>В группе не найдено студентов</span>
	@endforelse
	<h4>Предметы</h4>
	@forelse($tgls as $tgl)
		<div class="row" style="border-bottom:1px solid #eee;padding-bottom:10px;margin-bottom:10px;">
			<a class="col s5" href="/admin/lesson/{{$tgl->lesson->id}}">{{$tgl->lesson->name}} ({{$tgl->type_name()}})</a>
			<a class="col s2" href="/admin/teacher/{{$tgl->user->id}}">{{$tgl->user->_name()}}</a>
			<a class="col s2" href="/admin/delete/lesson/{{$tgl->id}}" style="text-align: center;">Открепить</a>
			<div class="col s3"><a href="/teacher/get/list/{{$group->id}}/{{$tgl->lesson->id}}" style="text-align: right;">Получить ведомость</a><br>
			<a href="/teacher/get/all/{{$group->id}}">Все баллы группы</a></div>
		</div>
	@empty
		<span>К группе не прикреплено предметов</span>
	@endforelse
@endsection