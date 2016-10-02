@extends('app')
@section('title', 'Выбор предмета')
@section('nav')
	<a href="/student" class="waves-effect waves-light breadcrumb">Главная</a>
	<span class="breadcrumb">Выбор предмета</span>
@endsection
@section('content')
	@forelse($lessons as $lesson)
		<a style="display:block;margin:20px;" href="/student/group/{{$gid}}/lesson/{{$lesson->id}}">{{$lesson->name}}</a>
	@empty
		<span>Результатов не найдено</span>
	@endforelse
@endsection