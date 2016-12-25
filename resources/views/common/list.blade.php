@if($marks)
	<div class="container" style="margin-bottom: 10px;">
		<ul class="tabs row">
			<li class="tab col s6 waves-effect"><a href="#sheet">Ведомость</a></li>
			<li class="tab col s6 waves-effect"><a href="#jrnls">Журналы</a></li>
		</ul>
	</div>
	<table class="centered" id="sheet">
		<tr>
			<td rowspan="3" style="width:20px;"></td>
			<td rowspan="3" style="width:240px;"></td>
			<td colspan="2" style="font-size:18px;">1-ая аттестация</td>
			<td colspan="4" style="font-size:18px;">2-ая аттестация</td>
			<td rowspan="3" style="width:65px;">Досдача</td>
			<td rowspan="3" style="width:65px;">Итого (баллы)</td>
			<td rowspan="3" style="width:65px;">Преми-<br>альные<br>(до 20<br>баллов)</td>
		</tr>
		<tr>
			<!-- <td>Пропуски</td> -->
			<td>Текущая атт.</td>
			<td>Рубежная атт.</td>
			<!-- <td>Пропуски</td> -->
			<td>Посещаем.</td>
			<td>Текущая атт.</td>
			<td>Рубежная атт.</td>
			<td>Самост. работа</td>
		</tr>
		<tr>
			<!-- <td style="height:50px;"></td> -->
			<td>Баллы (0-{{$maxs['t1']}})</td>
			<td>Баллы (0-{{$maxs['r1']}})</td>
			<!-- <td></td> -->
			<td>Баллы (0-{{$maxs['p']}})</td>
			<td>Баллы (0-{{$maxs['t2']}})</td>
			<td>Баллы (0-{{$maxs['r2']}})</td>
			<td>Баллы (0-{{$maxs['s2']}})</td>
		</tr>
		<tr>
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
			<!-- <td>11</td> -->
			<!-- <td>12</td> -->
		</tr>
		<?$i=0;?>
		@foreach($students as $s)
			<?$d=$marks[$i];?>
			<tr data-x="{{$s['id']}}">
				<td>{{++$i}}</td>
				<td style="text-align: left;">{{$s['name']}}</td>
				@foreach($maxs as $let=>$val)
					<td class="{{$let=='avg'?$let:'_mark'}}" {{isset($d[$let]['auto'])?'data-auto="1"':''}} data-x="{{$let}}">{{isset($d[$let])?$d[$let]['mark']:0}}</td>
				@endforeach
			</tr>
		@endforeach
	</table>
@endif
<div id="jrnls">
@foreach($jjs as $jj)
	<?$i=0;?>
	<div>
		@if(Auth::check())
			<div class="jt23">Чтобы удалить дату - кликните по ней</div>
		@endif
		<div style="text-align: center;">{{$jj['info']}}</div>
		<div class="table_wrapper" data-c="{{$jj['c']}}">
			<table style="width:auto;">
				<tr>
					<td style="width:20px;height:70px;"></td>
					<td style="width:240px;"></td>
					@foreach($jj['dates'] as $date)
						<td data-x="{{$date['id']}}" class="tdd">
							<span class="hoh rotate">{{date("d/m/y",$date['zz'])}}</span>
							@if(Auth::check())<span class="soh rotate">Удалить</span>@endif
						</td>
					@endforeach
				</tr>
				@foreach($students as $s)
					<tr data-x="{{$s['id']}}">
						<td>{{++$i}}</td>
						<td>{{$s['name']}}</td>
						@foreach($jj['dates'] as $date)
							<td class="_absent" {{isset($jj['marks'][$s['id'].$date['id']])?'absent=1':''}}>{{isset($jj['marks'][$s['id'].$date['id']])?'H':''}}</td>
						@endforeach
					</tr>
				@endforeach
			</table>
		</div>
	</div>
@endforeach
</div>