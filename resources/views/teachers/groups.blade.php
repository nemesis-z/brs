@extends('app')
@section('title', 'Выбор группы')
@section('nav')
	<a href="/teacher" class="waves-effect waves-light breadcrumb">Главная</a>
	<span class="breadcrumb">Выбор группы</span>
@endsection
@section('content')
	<div class="hint">Выберите группу</div>
	@forelse($groups as $group)
		<a style="display:block;margin:20px;" href="/teacher/lesson/{{$lid}}/group/{{$group->id}}">{{$group->name}}</a>
	@empty
		<span>Извините, результатов не найдено</span>
	@endforelse
@endsection