@extends('app')
@section('title','Авторизация')
@section('nav')
    <a class="waves-effect waves-light" href="/student">Студент</a>
@endsection
@section('content')
<div class="row">
    <div class="col s12 m8 l6 offset-m2 offset-l3">
        <form class="card" method="post" action="{{url('/login')}}">
            {!! csrf_field() !!}
            <div class="card-content">
                <div class="card-title">Авторизация преподавателя</div>
                <div class="row" style="margin-bottom: 0;">
                    <div class="input-field col s12">
                        <input id="login" type="text" class="validate{{$errors->has('name')?' invalid':''}}" name="name" value="{{ old('name') }}">
                        <label for="login">Логин</label>
                        @if($errors->has('name'))
                            <div class="error_block">{{ $errors->first('name') }}</div>
                        @endif
                    </div>
                    <div class="input-field col s12">
                        <input id="password" type="password" class="validate{{$errors->has('password')?' invalid':''}}" name="password">
                        <label for="password">Пароль</label>
                        @if($errors->has('password'))
                            <div class="error_block">{{ $errors->first('password') }}</div>
                        @endif
                    </div>
                    <div class="col s12">
                        <button class="btn-flat waves-effect waves-light right" type="submit">Войти</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
