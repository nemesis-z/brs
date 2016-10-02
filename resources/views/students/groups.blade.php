@extends('app')
@section('title', 'Выбор группы')
@section('nav')
	<span class="breadcrumb">Главная</span>
@endsection
@section('style')
	.g_header {
		background:#3d748f;
		color:#fff;
		padding:5px;
		text-align:center;
	}
	.g_link {
		padding:5px;
		display:block;
	}
	.g_wrap {
		background:#fcfcfc;
		padding:5px;
	}
@endsection
@section('content')
	<div class="row">
	@foreach($groups as $group)
		<div class="col s12 m4 l3">
			<div class="g_wrap">
				<div class="g_header">{{$group['key']}}</div>
				@foreach($group['gs'] as $g)
					<a class="g_link" href="/student/group/{{$g->id}}">{{$g->name}}</a>
				@endforeach
			</div>
		</div>
	@endforeach
	</div>
@endsection