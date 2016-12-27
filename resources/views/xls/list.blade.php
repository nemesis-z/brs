<html>
	<tr>
		<td style="width:5px;"></td>
		<td style="width:20px;"></td>
		<td style="width:12px;"></td>
		<td style="width:12px;"></td>
		<td style="width:12px;"></td>
		<td style="width:12px;"></td>
		<td style="width:12px;"></td>
		<td style="width:12px;"></td>
		<td style="width:12px;"></td>
	</tr>
	<tr>
		<td colspan="9" align="center">ГРОЗНЕНСКИЙ ГОСУДАРСТВЕННЫЙ НЕФТЯНОЙ ТЕХНИЧЕСКИЙ УНИВЕРСИТЕТ</td>
	</tr>
	<tr>
		<td colspan="9" align="center">имени академика М.Д.Миллионщикова</td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td colspan="3">Список группы утверждаю</td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td colspan="3">Декан факультета</td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td colspan="3">{{$date}}</td>
	</tr>
	<tr></tr>
	<tr><td colspan="9" align="center">ЗАЧЕТНО-ЭКЗАМЕНАЦИОННАЯ ВЕДОМОСТЬ №</td></tr>
	<tr>
		<td colspan="2">Семестр</td>
		<td colspan="7" style="text-align: center;border-bottom:1px solid;">{{$ov}}({{$sem}})</td>
	</tr>
	<tr>
		<td colspan="2">Форма контроля</td>
		<td colspan="7" style="text-align: center;border-bottom:1px solid;">{{$fc}}</td>
	</tr>
	<tr>
		<td colspan="2">Факультет</td>
		<td colspan="7" style="text-align: center;border-bottom:1px solid;">{{$fac}}</td>
	</tr>
	<tr>
		<td colspan="2">Группа</td>
		<td colspan="3" style="text-align: center;border-bottom:1px solid;">{{$group->name}}</td>
		<td colspan="2">Курс</td>
		<td colspan="2" style="text-align: center;border-bottom:1px solid;">{{$crs}}</td>
	</tr>
	<tr>
		<td colspan="2">Дисциплина</td>
		<td colspan="7" style="text-align: center;border-bottom:1px solid;font-weight: bold;">{{$lesson->name}}</td>
	</tr>
	<tr>
		<td colspan="3">Фамилия, имя, отчество преподавателей</td>
		<td colspan="6" style="text-align: center;border-bottom:1px solid;"></td>
	</tr>
	<tr>
		<td colspan="9" style="text-align: center;border-bottom:1px solid;"></td>
	</tr>
	<tr></tr>
	<tr>
		<td align="center" valign="middle">№</td>
		<td align="center" valign="middle" style="wrap-text: true;">Фамилия, имя, отчество</td>
		<td align="center" valign="middle" style="wrap-text: true;">№ зачетной книжки</td>
		<td align="center" valign="middle" style="wrap-text: true;">Баллы за семестр</td>
		<td align="center" valign="middle" style="wrap-text: true;">Баллы за {{!$type?'экзамен':'зачет'}}</td>
		<td align="center" valign="middle" style="wrap-text: true;">Итоговый балл</td>
		<td align="center" valign="middle" style="wrap-text: true;">Отметка {{!$type?'об экзамене':'о зачете'}}</td>
		<td align="center" valign="middle" style="wrap-text: true;">Дата сдачи {{!$type?'экзамена':'зачета'}}</td>
		<td align="center" valign="middle">Подпись</td>
	</tr>
	<tr>
		<td align="center">1</td>
		<td align="center">2</td>
		<td align="center">3</td>
		<td align="center">4</td>
		<td align="center">5</td>
		<td align="center">6</td>
		<td align="center">7</td>
		<td align="center">8</td>
	</tr>
	<?$i=0;?>
	@foreach($marks["students"] as $s)
		<tr>
			<td align="left">{{++$i}}.</td>
			<td>{{$s["name"]}}</td>
			<td align="center">{{$s["number"]}}</td>
			<td align="center">{{$marks["marks"][$i-1]["avg"]["mark"]}}</td>
			@if($marks["marks"][$i-1]["avg"]["mark"]<10||$limits->where('student_id',$s["id"])->count())
				<td colspan="4" align="center" style="font-weight:bold;">НЕДОПУСК</td>
			@endif
		</tr>
	@endforeach
	<tr></tr>
	<tr></tr>
	<tr></tr>
	<tr style="height:28px;">
		<td colspan="3" style="wrap-text: true;">Ведомость получил преподаватель _________________</td>
		<td></td>
		<td colspan="5">Результаты сдачи зачетов и экзаменов</td>
	</tr>
	<tr>
		<td colspan="4">«___»________________2016г.</td>
		<td></td>
		<td colspan="2" style="border-top:1px solid;">Зачтено</td>
		<td colspan="2" style="border-top:1px solid;border-left:1px solid;">Отлично</td>
	</tr>
	<tr>
		<td colspan="4">Ведомость возвращена</td>
		<td></td>
		<td colspan="2" style="border-top:1px solid;">Не зачтено</td>
		<td colspan="2" style="border-top:1px solid;border-left:1px solid;">Хорошо</td>
	</tr>
	<tr>
		<td colspan="4">«___»________________2016г.</td>
		<td></td>
		<td colspan="2" style="border-top:1px solid;">Неявка</td>
		<td colspan="2" style="border-top:1px solid;border-left:1px solid;">Удовлет.</td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td colspan="2" style="border-top:1px solid;"></td>
		<td colspan="2" style="border-top:1px solid;border-left:1px solid;">Неудовлет.</td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td colspan="2" style="border-top:1px solid;"></td>
		<td colspan="2" style="border-top:1px solid;border-left:1px solid;">Неявка</td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td colspan="2" style="border-top:1px solid;border-bottom: 1px solid;"></td>
		<td colspan="2" style="border-top:1px solid;border-left:1px solid;border-bottom: 1px solid;">Итого</td>
	</tr>
	<tr>
		<td colspan="9" align="center" style="font-weight: bold;">Указания по оформлению ведомости</td>
	</tr>
	<tr>
		<td valign="top" align="right">1)</td>
		<td colspan="8" style="height:42;wrap-text: true;">Факультет составляет ведомости в двух экземплярах и передает их на кафедру. Не позднее 10 часов утра следующего после отчетности дня, преподаватель сдает один экземпляр ведомости в деканат факультета, второй экземпляр, заверенный методистом факультета остается на кафедре;</td>
	</tr>
	<tr>
		<td valign="top" align="right">2)</td>
		<td colspan="8" style="height:28;wrap-text: true;">Ведомости сдаются в деканат только преподавателями, которые принимали отчетность. Передача их через других лиц не допускается;</td>
	</tr>
	<tr>
		<td align="right">3)</td>
		<td colspan="8" style="wrap-text: true;">Преподаватель расписывается против каждой оценки;</td>
	</tr>
	<tr>
		<td valign="top" align="right">4)</td>
		<td colspan="8" style="height:28;wrap-text: true;">Все записи в ведомости производятся обязательно чернилами. Помарка и подчистка не допускаются. Поправки должны быть оговорены подписью преподавателя.</td>
	</tr>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</html>