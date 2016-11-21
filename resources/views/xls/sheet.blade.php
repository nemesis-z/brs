<html>
    <tr>    
        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
        <td colspan="3" align="right">«Утверждаю»</td>
    </tr>
    <tr>    
        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
        <td colspan="3" align="right">Декан ф-та _____________</td>
    </tr>
    <tr>    
        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
        <td colspan="3" align="right">{{$date}}</td>
    </tr>
    <tr>
    	<td colspan="11" align="center" style="font-weight:bold;font-size:18px;">АТТЕСТАЦИОННАЯ ВЕДОМОСТЬ</td>
    </tr>
    <tr>
    	<td colspan="11" align="center" style="font-size:14px;">
    		В {{$sem%2?'осеннем':'весеннем'}} ({{$sem}}) семестре {{date('Y')}}/{{date('Y')+1}} учебного года {{$zz}}
    	</td>
    </tr>
    <tr>
    	<td colspan="11" align="center" style="font-size:14px;">
    		Группы {{$group->name}} по дисциплине «{{$lesson->name}}»
    	</td>
    </tr>
    <tr>
    	<td colspan="11" align="center">
    		имеющей итоговую семестровую аттестацию в виде {{$v}}
    	</td>
    </tr>
    <tr>
    	<td rowspan="3"></td><td rowspan="3"></td>
    	<td colspan="2" align="center" style="font-weight:bold">1-ая аттестация</td>
    	<td colspan="4" align="center" style="font-weight:bold">2-ая аттестация</td>
    	<td rowspan="3" valign="middle" align="center" style="font-style:italic;">Досдача</td>
    	<td rowspan="3" valign="middle" align="center" style="font-style:italic;font-weight:bold;wrap-text: true;">Итого (баллы)</td>
    	<td rowspan="3" valign="middle" align="center" style="font-style:italic;wrap-text: true;">Преми- альные (до 20 баллов)</td>
    </tr>
    <tr style="text-align: center;vertical-align: middle;">
    	<td></td><td></td>
    	<td>Текущая атт.</td>
    	<td>Рубежная атт.</td>
    	<td>Посещаем.</td>
    	<td>Текущая атт.</td>
    	<td>Рубежная атт.</td>
    	<td style="wrap-text:true;">Самост. работа</td>
    </tr>
    <tr style="text-align: center;vertical-align: middle;">
    	<td></td><td></td>
    	<td>Баллы (0-{{$maxs['t1']}})</td>
		<td>Баллы (0-{{$maxs['r1']}})</td>
		<td>Баллы (0-{{$maxs['p']}})</td>
		<td>Баллы (0-{{$maxs['t2']}})</td>
		<td>Баллы (0-{{$maxs['r2']}})</td>
		<td>Баллы (0-{{$maxs['s2']}})</td>
    </tr>
    <tr style="text-align: center">
		<td>№</td>
		<td>1</td>
		<td>2</td>
		<td>3</td>
		<td>4</td>
		<td>5</td>
		<td>6</td>
		<td>7</td>
		<td>8</td>
		<td>9</td>
		<td>10</td>
	</tr>
	<?$i=0;?>
	@foreach($students as $s)
		<?$d=$marks[$i];?>
		<tr style="text-align: center;">
			<td>{{++$i}}.</td>
			<td align="left">{{$s['name']}}</td>
			@foreach($maxs as $let=>$val)
				<td>{{isset($d[$let])?$d[$let]['mark']:0}}</td>
			@endforeach
		</tr>
	@endforeach
	<tr>
		<td colspan="11" align="center">{{$name}}</td>
	</tr>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</html>
