<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <!-- Fonts -->
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'> --}}
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    {{-- <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'> --}}
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <!-- Styles -->
    {{-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"> --}}
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/css/materialize.min.css">
    <style>
        * {
            margin:0;
        }
        .container table, .container th, .container td {
            border: 1px solid #eee;
        }
        .container th, .container td {
            padding: 3px;
        }
        .wrapper {
            margin: 0 auto;
            width: 100%;
            max-width: 1000px;
        }
        #main_nav {
            position: relative;
            background: none;
        }
        #granim {
            z-index: -1;
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            display: block;
            width: 100%;
            height: 100%;
        }
        .container {
            margin-top:10px;
        }
        .error_block {
            color: #F44336;
            font-size: .8em;
            margin-top: -10px;
        }
        .breadcrumb {
            font-size: 15px;
        }
        .hint {
            text-align: center;
            border-bottom: 1px solid #eee;
            padding: 10px;
        }
        #main_nav {
            min-width: 520px;
        }
        #_loader {
            position: fixed;
            left:50%;
            top:50%;
            margin-left:-25px;
            margin-top:-25px;
            background: #fff;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            padding: 7px;
            display: none;
        }
        #_loader.active {
            display: block;
        }
        .breadcrumb {
            padding: 0 5px;
        }
        .breadcrumb:before {
            margin: 0 2px 0 -8px;
        }
        .rotate {
            transform: rotate(-90deg);
            display: block;
            width: 15px;
            margin-top: 50px;
            padding-top:4px;
        }
        .tdd {
            width:26px;
        }
        @if(Auth::guest())
        .container {
            width:100%;
            max-width: 1200px;
        }
        @endif  
        @yield('style')
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body id="app-layout">
    <nav id="main_nav">
        <canvas id="granim"></canvas>
        <div class="wrapper row">
            <div class="col s10">
                @yield('nav')
            </div>
            <div class="col s2">
                <ul class="right">
                    @if (Auth::guest())
                        <li><a class="waves-effect waves-light" href="{{ url('/login') }}">Войти</a></li>
                    @else
                        @if(!Auth::user()->isAdmin()||Request::is('admin*'))
                            <li><a class="waves-effect waves-light" href="{{ url('/logout') }}">Выйти</a></li>
                        @else
                            <li><a class="waves-effect waves-light" href="/admin">Админка</a></li>
                        @endif
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        @yield('content')
    </div>
    <div id="_loader">
        <div class="preloader-wrapper small">
          <div class="spinner-layer spinner-blue">
            <div class="circle-clipper left">
              <div class="circle"></div>
            </div><div class="gap-patch">
              <div class="circle"></div>
            </div><div class="circle-clipper right">
              <div class="circle"></div>
            </div>
          </div>

          <div class="spinner-layer spinner-red">
            <div class="circle-clipper left">
              <div class="circle"></div>
            </div><div class="gap-patch">
              <div class="circle"></div>
            </div><div class="circle-clipper right">
              <div class="circle"></div>
            </div>
          </div>

          <div class="spinner-layer spinner-yellow">
            <div class="circle-clipper left">
              <div class="circle"></div>
            </div><div class="gap-patch">
              <div class="circle"></div>
            </div><div class="circle-clipper right">
              <div class="circle"></div>
            </div>
          </div>

          <div class="spinner-layer spinner-green">
            <div class="circle-clipper left">
              <div class="circle"></div>
            </div><div class="gap-patch">
              <div class="circle"></div>
            </div><div class="circle-clipper right">
              <div class="circle"></div>
            </div>
          </div>
        </div>
    </div>
    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/js/materialize.min.js"></script>
    {{-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> --}}
    <script src="{{ URL::asset('js/granim.js') }}"></script>
    <script>
    function alert(m,t,r) {
        Materialize.toast(m,t||3000,r||'rounded');
    }
    function a(_a,_d,_f) {
        return $.post(_a,_d,function(d) {
            if(d.err) {
                if(d.logout)location.href = "/";
                else alert(d.msg);
                return;
            }
            _f&&_f(d);
        }, 'json');
    }
    function loader(hide) {
        if(hide)$('#_loader').removeClass('active').children().removeClass('active');
        else $('#_loader').addClass('active').children().addClass('active');
    }
    $(function() {
        @if(session('msg'))
            alert("{{session('msg')}}");
        @endif
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
        new Granim({
            element: '#granim',
            name: 'granim',
            elToSetClassOn: 'body',
            direction: 'diagonal',
            isPausedWhenNotInView: !0,
            opacity: [1, 1],
            stateTransitionSpeed: 1000,
            states : {
                "default-state": {
                    gradients: [
                        ['#834d9b', '#d04ed6'],
                        ['#1CD8D2', '#93EDC7']
                    ],
                    transitionSpeed: 5000,
                    loop: true
                },
                "dark-state": {
                    gradients: [
                        ['#757F9A', '#D7DDE8'],
                        ['#5C258D', '#4389A2']
                    ],
                    transitionSpeed: 5000,
                    loop: true
                }
            }
        });
        @yield('script')
    });
    </script>
    <div style="display:none;">{{ round(microtime(true) - LARAVEL_START,4) }}</div>
</body>
</html>
