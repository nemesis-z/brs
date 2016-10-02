@extends('app')
@section('content')
	<form action="/admin/group/{{$id}}/add_student" method="post">
		{!!csrf_field()!!}
		<input type="text" name="last" placeholder="Фамилия">
		<input type="text" name="first" placeholder="Имя">
		<input type="text" name="mid" placeholder="Отчество">
		<input type="text" name="number" placeholder="Номер зачетки">
		<input type="submit" value="Добавить"> 
	</form>
	@forelse($students as $student)
		<div>
			<span style="margin-left:20px;">{{$student->last}} {{$student->first}} {{$student->mid}}</span>
			<a href="/admin/delete/student/{{$student->id}}">Убрать</a>
		</div>
	@empty
		<span>В группе не найдено студентов</span>
	@endforelse
@endsection