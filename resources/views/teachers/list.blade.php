@extends('app')
@section('title', 'Результаты группы')
@section('nav')
	<a href="/teacher" class="waves-effect waves-light breadcrumb">Главная</a>
	<a href="/teacher/lesson/{{$lesson_id}}" class="waves-effect waves-light breadcrumb">Выбор группы</a>
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
	.table_wrapper {
		display:inline-block;
		position:relative;
	}
	.btn-floating {
		position:absolute;
		right:-58px;
		top: -5px;
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
    close: 'отмена',
    firstDay: 1,
    formatSubmit: 'dd/mm/yyyy',
    closeOnSelect: true
});
function removePicker(p) {
	p.stop();
	$('.picker__input--target').remove();
}
+function() {
	var is = false;
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
	$('.table_wrapper').each(function(ndx,el) {
		el = $(el);
		$('<a>').addClass('btn-floating btn-large waves-effect waves-light red').html('<i class="material-icons">add</i>').click(function() {
			if(is)return;
			is = true;
			setTimeout(function() {
				$('<input>').css('display','none').appendTo('body').pickadate({
					onSet: function(ctx) {
						loader();
						var d = Math.round(ctx.select/1000);
						a('/date/{{$lesson_id}}/{{$group_id}}',{date:d,c:el.data('c')},function(d) {
							if(!d.ok)return alert('Что-то пошло не так');
							el.find('tr').each(function(ndx) {
								if(!ndx)$('<td>').attr('data-x',d.id).addClass('tdd').html('<span class="rotate">'+d.fd+'</span>').appendTo(this);
								else $('<td>').addClass('_absent').click(_absent).appendTo(this);
							});
						}).always(function() {
							is = false;
							loader(!is)
						});
						removePicker(this);
					},
					onClose: function() {
						removePicker(this);
						is = false;
					}
				}).pickadate('picker').open();
			},1);
		}).appendTo(el);
	});
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
				a('/mark/{{$lesson_id}}/{{$group_id}}/'+_ds,{mark:_val,type:_dt},function(d) {
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
	$('._absent').click(_absent);
}();
@endsection