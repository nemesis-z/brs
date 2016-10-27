@extends('app')
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
	<form action="/admin/group/{{$id}}/add_student" method="post" class="row">
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
	@forelse($students as $student)
		<div class="std">
			<span>{{$student->last}} {{$student->first}} {{$student->mid}}</span>
			<a href="/admin/toggle/{{$student->id}}">{{$student->limited?'Не допущен':'Допущен'}}</a>
			<a href="/admin/delete/student/{{$student->id}}">Удалить</a>
		</div>
	@empty
		<span>В группе не найдено студентов</span>
	@endforelse
@endsection