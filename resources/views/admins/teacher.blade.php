@extends('app')
@section('title', 'Преподаватель: '.$teacher->last)
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
	@forelse($tgls as $tgl)
		<div style="margin:20px;">
			<a href="/admin/lesson/{{$tgl->lesson->id}}">{{$tgl->lesson->name}}</a>
			(<a href="/admin/group/{{$tgl->group->id}}">{{$tgl->group->name}}</a>, {{$tgl->type_name()}})
			<a href="/admin/delete/lesson/{{$tgl->id}}">Открепить</a>
		</div>
	@empty
		<span>Нет прикрепленных предметов</span>
	@endforelse
@endsection