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
	@for($j=0;$j<count($marks[0]["students"]);$j++)
		<tr style="text-align: center;">
			<td>{{$marks[0]["students"][$j]["name"]}}</td>
			@for($i=0;$i<$lessons->count();$i++)
				<td>{{$marks[$i]["marks"][$j]["avg"]["mark"]}}</td>
			@endfor
		</tr>
	@endfor
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</html>