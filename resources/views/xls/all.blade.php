<html>
	<tr>
		<td colspan="{{$count+1}}" align="center">{{$sem}} семестр</td>
	</tr>
	<tr>
		<td valign="middle" align="center">{{$group_name}}</td>
		@foreach($lessons as $lesson)
			<td>{{$lesson->name}}</td>
		@endforeach
	</tr>
	@foreach($students as $student))
		<tr style="text-align: center;">
			<td>{{$student->_name()}}</td>
			@foreach($lessons as $lesson)
				<td>{{isset($student->ms[$lesson->id])?$student->ms[$lesson->id]:0}}</td>
			@endforeach
		</tr>
	@endforeach
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</html>