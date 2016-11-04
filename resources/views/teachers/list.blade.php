@extends('app')
@section('title', 'Результаты группы')
@section('nav')
	<a href="/teacher" class="waves-effect waves-light breadcrumb">Главная</a>
	<a href="/teacher/lesson/{{$lesson->id}}" class="waves-effect waves-light breadcrumb">Выбор группы</a>
	<span class="breadcrumb">Результаты группы</span>
@endsection
@section('content')
	<div class="hint">Группа {{$group->name}}, {{$lesson->name}}</div>
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
	.table_wrapper {
		display:inline-block;
		position:relative;
	}
	.btn-floating {
		position:absolute;
		right:-58px;
		top: -5px;
	}
	.tdd {
		cursor:pointer;
	}
	.tdd:hover>.hoh, .soh {
		display:none;
	}
	.tdd:hover>.soh {
		display:block;
	}
	.jt23 {
		padding: 10px 0;
	    text-align: center;
	    border-top: 1px solid #eee;
	    border-bottom: 1px solid #eee;
	    margin: 5px 0;
	}
@endsection
@section('script')
jQuery.extend( jQuery.fn.pickadate.defaults, {
    monthsFull: [ 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря' ],
    monthsShort: [ 'янв', 'фев', 'мар', 'апр', 'май', 'июн', 'июл', 'авг', 'сен', 'окт', 'ноя', 'дек' ],
    weekdaysFull: [ 'воскресенье', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота' ],
    weekdaysLetter: [ 'вс', 'пн', 'вт', 'ср', 'чт', 'пт', 'сб' ],
    weekdaysShort: [ 'вс', 'пн', 'вт', 'ср', 'чт', 'пт', 'сб' ],
    today: 'сегодня',
    clear: false,
    close: 'ok',
    firstDay: 1,
    formatSubmit: 'dd/mm/yyyy',
    closeOnSelect: !true
});
function removePicker(p) {
	p.stop();
	$('.picker__input--target').remove();
}
+function() {
	var is = false;
	function delDate() {
		var el = $(this);
		var id = el.data('x');
		if(is||isNaN(id=parseInt(id)))return;
		is = true;
		loader();
		a('/delete/date/'+id,{},function(d) {
			if(!d.ok)return alert("Что-то пошло не так");
			var ndx = el.index();
			el.parent().parent().children('tr').each(function(){
				$(this).children().eq(ndx).remove();
			})
		}).always(function() {
			is = false;
			loader(!0);
		});
	}
	function _absent() {
		if(is)return;
		var that=$(this),absent=that.attr('absent');
		var _dd=+that.parents('table').find('tr:eq(0)').children('td').eq(that.index()).data('x'),_ds=+that.parent().data('x');
		if(!_ds||!_dd)location.href="/";
		is = true;
		loader();
		a('/jmark/'+_ds+'/'+_dd,{mark:-1},function(d) {
			if(!d.ok)return alert("Что-то пошло не так");
			absent&&that.removeAttr('absent')||that.attr('absent',1);
			that.text(absent?'':'H');
		}).always(function() {
			is = false;
			loader(!is);
		});
	}
	$('.tdd').click(delDate);
	$('.table_wrapper').each(function(ndx,el) {
		el = $(el);
		$('<a>').addClass('btn-floating btn-large waves-effect waves-light red').html('<i class="material-icons">add</i>').click(function() {
			if(is)return;
			is = true;
			setTimeout(function() {
				var sd=null;
				$('<input>').css('display','none').appendTo('body').pickadate({
					onSet: function(ctx) {
						if(!ctx.select)return;
						sd = Math.round(ctx.select/1000)+5*60*60;
					},
					onClose: function() {
						removePicker(this);
						if(!sd) {
							is = false;
							return;
						}
						loader();
						a('/date/{{$lesson->id}}/{{$group->id}}',{date:sd,c:el.data('c')},function(d) {
							if(!d.ok)return alert('Что-то пошло не так');
							el.find('tr').each(function(ndx) {
								if(!ndx)$('<td>').attr('data-x',d.id).addClass('tdd').html('<span class="hoh rotate">'+d.fd+'</span><span class="soh rotate">Удалить</span>').appendTo(this).click(delDate);
								else $('<td>').addClass('_absent').click(_absent).appendTo(this);
							});
						}).always(function() {
							is = false;
							loader(!is)
						});
						removePicker(this);
					}
				}).pickadate('picker').open();
			},1);
		}).appendTo(el);
	});
	@if($lec)
	$('._mark').click(function() {
		if(is)return;
		var that=$(this), avg=that.parent().find('.avg'), o=that.offset(), val=+that.text()||0;
		var _dt=that.data('x'), _ds=+that.parent().data('x');
		if(!_ds||!_dt)return location.href='/';
		$('<input type="text" class="ch">').css({height:that.outerHeight(!0)-1,width:that.outerWidth(!0)-1,left:o.left+1, top:o.top+1}).appendTo('body').focus().blur(function() {
			var _that=$(this),_val=_that.val();
			if(isNaN(_val))alert("NAN");
			else if(_val&&_val!=val) {
				_val = +_val;
				is = true;
				loader();
				a('/mark/{{$lesson->id}}/{{$group->id}}/'+_ds,{mark:_val,type:_dt},function(d) {
					if(!d.ok)return alert("Что-то пошло не так");
					avg.text(+avg.text()-(val-_val));
					that.text(_val);
				}).always(function() {
					is = false;
					loader(!is);
				});
			}
			_that.remove();
		});
	});
	@endif
	$('._absent').click(_absent);
}();
@endsection