@extends('app')
@section('nav')
	<a href="/admin" class="waves-effect waves-light breadcrumb">Главная</a>
@endsection
@section('script')
	$('select').material_select();
@endsection
@section('content')
	{{$teacher->last}} {{mb_substr($teacher->first,0,1,'utf-8')}}. {{mb_substr($teacher->mid,0,1,'utf-8')}}.
	<form class="row" method="post" action="/admin/teacher/{{$teacher->id}}/add">
		{{csrf_field()}}
		<div class="input-field col s4">
			<select class="browser-default" name="lesson_id">
				@foreach($add['lessons'] as $lesson)
					<option value="{{$lesson->id}}">{{$lesson->name}}</option>
				@endforeach
			</select>
		</div>
		<div class="input-field col s4">
			<select class="browser-default" name="group_id">
				@foreach($add['groups'] as $group)
					<option value="{{$group->id}}">{{$group->name}}</option>
				@endforeach
			</select>
		</div>
		<div class="input-field col s2">
			<select class="browser-default" name="c">
				@for($i=0;$i<count($add['cs']);$i++)
					<option value="{{$i+1}}">{{$add['cs'][$i]}}</option>
				@endfor
			</select>
		</div>
		<div class="input-field col s2">
			<select class="browser-default" name="type">
				@for($i=0;$i<count($add['types']);$i++)
					<option value="{{$i+1}}">{{$add['types'][$i]}}</option>
				@endfor
			</select>
		</div>
		<button class="btn-flat waves-effect waves-light" type="submit">Добавить</button>
	</form>
	@if(session('teacher'))
		<a target="_blank" href="/admin/teacher/{{session('teacher')}}">Этот предмет уже добавлен</a>
	@endif
	@forelse($lessons as $lesson)
		<a style="display:block;margin:20px;" href="/admin/lesson/{{$lesson->id}}">{{$lesson->name}}</a>
	@empty
		<span>Нет прикрепленных предметов</span>
	@endforelse
@endsection