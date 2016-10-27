@extends('app')
@section('title', 'Админка')
@section('nav')
	<a href="/teacher" class="waves-effect waves-light breadcrumb">Страница преподов</a>
@endsection
@section('style')
	.open_e {
		cursor:pointer;
	}
	.exp_le {
		display:none;
	}
	.exp_le.opened {
		display:block;
	}
	form.exp_le {
		padding:5px;
		margin:5px;
	}
@endsection
@section('script')
	$('.open_e').click(function() {
		$(this).next().toggleClass('opened');
	});
@endsection
@section('content')
	<div class="open_e">Предметы</div>
	<div class="exp_le">
		<div class="open_e" style="margin-left:5px;">Добавить</div>
		<form class="exp_le z-depth-1" action="/admin/add/lesson" method="post">
			{!!csrf_field()!!}
			<input type="text" name="name" placeholder="Название предмета">
			<button class="btn-flat waves-effect waves-light" type="submit">Добавить</button>
		</form>
		@foreach($lessons as $lesson)
			<a style="display:block;margin:20px;" href="/admin/lesson/{{$lesson->id}}">{{$lesson->name}}</a>
		@endforeach
	</div>
	<div class="open_e">Преподаватели</div>
	<div class="exp_le">
		<div class="open_e" style="margin-left:5px;">Добавить</div>
		<form class="exp_le z-depth-1" action="/admin/add/teacher" method="post">
			{!!csrf_field()!!}
			<input type="text" name="name" placeholder="Логин">
			<input type="text" name="_pass" placeholder="Пароль">
			<input type="text" name="last" placeholder="Фамилия">
			<input type="text" name="first" placeholder="Имя">
			<input type="text" name="mid" placeholder="Отчество">
			<input type="email" name="email" placeholder="Электронный адрес">
			<button class="btn-flat waves-effect waves-light" type="submit">Добавить</button>
		</form>
		@foreach($teachers as $teacher)
			<a style="display:block;margin:20px;" href="/admin/teacher/{{$teacher->id}}">{{$teacher->last}} {{mb_substr($teacher->first,0,1,'utf-8')}}. {{mb_substr($teacher->mid,0,1,'utf-8')}}.</a>
		@endforeach
	</div>
	<div class="open_e">Группы</div>
	<div class="exp_le">
		<div class="open_e" style="margin-left:5px;">Добавить</div>
		<form class="exp_le z-depth-1" action="/admin/add/group" method="post">
			{!!csrf_field()!!}
			<input type="text" name="name" placeholder="Название">
			<input type="number" name="year" placeholder="Год">
			<button class="btn-flat waves-effect waves-light" type="submit">Добавить</button>
		</form>
		@foreach($groups as $group)
			<a style="display:block;margin:20px;" href="/admin/group/{{$group->id}}">{{$group->name}}</a>
		@endforeach
	</div>
@endsection