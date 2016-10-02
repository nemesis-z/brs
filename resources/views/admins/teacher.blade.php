@extends('app')
@section('script')
	$('select').material_select();
	$('#ac_lesson').autocomplete({
		data: {
			@foreach
		}
	});
@endsection
@section('content')
	{{$teacher->last}} {{mb_substr($teacher->first,0,1,'utf-8')}}. {{mb_substr($teacher->mid,0,1,'utf-8')}}.
	<form class="row">
		<div class="input-field col s4">
			<input type="text" id="autocomplete-input" id="ac_lesson">
          	<label for="autocomplete-input">Предмет</label>
		</div>
		<div class="input-field col s4">
			<input type="text" id="autocomplete-input" id="ac_group">
          	<label for="autocomplete-input">Группа</label>
		</div>
		<div class="input-field col s2">
			<select>
				@for($i=0;$i<count($add['cs']);$i++)
					<option value="{{$i+1}}">{{$add['cs'][$i]}}</option>
				@endfor
			</select>
		</div>
		<div class="input-field col s2">
			<select>
				@for($i=0;$i<count($add['types']);$i++)
					<option value="{{$i+1}}">{{$add['types'][$i]}}</option>
				@endfor
			</select>
		</div>
		<button class="btn-flat waves-effect waves-light" type="submit">Добавить</button>
	</form>
	@forelse($lessons as $lesson)
		<a style="display:block;margin:20px;" href="/admin/lesson/{{$lesson->id}}">{{$lesson->name}}</a>
	@empty
		<span>Нет прикрепленных предметов</span>
	@endforelse
@endsection