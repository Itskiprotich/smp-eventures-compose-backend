<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <link rel="icon" href="{{ url('images/smp.jpg') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/main.css')}}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Styles -->

</head>

<body>
    <section class="material-half-bg">
        <div class="cover"></div>
    </section>
    <section class="lockscreen-content">
        <div class="logo">
            <h1>SMP EVentures</h1>
        </div>
        <div class="lock-box"><img class="rounded-circle user-image" src="">
            @if (session()->has('error'))

            <div class="alert alert-dismissible alert-danger">
                <button class="close" type="button" data-dismiss="alert">×</button>
                <p>{{ session('error') }}</p>
            </div>
            @endif
            <h4 class="text-center user-name">{{ $admin['firstname'] }} {{ $admin['lastname'] }}</h4>
            <p class="text-center text-muted">Select A Branch</p>
            <form class="unlock-form" action="/branches/update" method="POST">
                @csrf
                <div class="form-group">
                    <label class="control-label">Branch Name</label> 
                    <select name="branch" required="required" style="width: 100%" class="form-control"
                                            id="demoSelect">
                        @foreach($branches as $tp)
                        <option value="{{$tp['id']}}">{{$tp['name']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group btn-container">
                    <button class="btn btn-primary btn-block" type="submit"><i class="fa fa-unlock fa-lg"></i>Select </button>
                </div>
            </form>
            <p><a href="{{route('exit')}}">Not {{ $admin['firstname'] }} ? Login Here.</a></p>
        </div>
    </section>
    <!-- Essential javascripts for application to work-->
    <script src="{{ asset('assets/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="{{ asset('assets/js/plugins/pace.min.js') }}"></script>
</body>

</html>