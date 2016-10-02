@extends('app')
@section('title', 'Результаты группы')
@section('nav')
	<a href="/student" class="waves-effect waves-light breadcrumb">Главная</a>
	<a href="/student/group/{{$group_id}}" class="waves-effect waves-light breadcrumb">Выбор предмета</a>
	<span class="breadcrumb">Результаты группы</span>
@endsection
@section('content')
	@include('common.list')
@endsection
@section('style')
	#app-layout>.container {
		width:100%;
		max-width:1200px;
		padding:5px;
	}
	.ch {
		position:absolute;
		text-align:center;
		background:#fff !important;
	}
	.tabs .tab a {
		color:#5d8db6;
	}
	.tabs .tab a:hover {
		color:#92b7d7;
	}
	.tabs .indicator {
		background-color:#92b7d7;
	}
	._absent {
		text-align:center;
	}
@endsection