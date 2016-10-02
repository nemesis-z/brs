@extends('app')
@section('content')
	{{$lesson->name}}
	<br>
	@foreach($teachers as $teacher)
		<a style="display:block;margin:20px;" href="/admin/teacher/{{$teacher->id}}">{{$teacher->last}} {{mb_substr($teacher->first,0,1,'utf-8')}}. {{mb_substr($teacher->mid,0,1,'utf-8')}}.</a>
	@endforeach
@endsection