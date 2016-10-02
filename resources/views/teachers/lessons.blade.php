@extends('app')
@section('title', 'Выбор предмета')
@section('nav')
	<span class="breadcrumb">Главная</span>
@endsection
@section('content')
	<div class="hint">Выберите предмет</div>
	@forelse($lessons as $lesson)
		<a style="display:block;margin:20px;" href="/teacher/lesson/{{$lesson->id}}">{{$lesson->name}}</a>
	@empty
		<span>Извините, предметов не найдено</span>
	@endforelse
@endsection
@if($teacher)
	@section('script')
	Materialize.toast('Здравствуйте, {{$teacher}}', 3000, 'rounded');
	@endsection
@endif