<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('media/icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('media/icon.png') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <!-- Extra details for Live View on GitHub Pages -->
    
    <title>
        {{ config('app.name') }}
    </title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
        name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/2d643bcf3f.js" crossorigin="anonymous"></script>
    <!-- CSS Files -->
    <link href="{{ asset('plugins/bootstrap-5.3.3/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('paper/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('paper/css/paper-dashboard.css?v=2.0.0') }}" rel="stylesheet" />
</head>

<body class="{{ $class ?? '' }}">

    <!-- Header -->
    <nav class="navbar navbar-expand-lg fixed-top {{ $navbarClass ?? 'navbar-absolute navbar-transparent' }}">
        <div class="container">
            <div class="navbar-wrapper">
                <a class="navbar-brand" href="{{ route('dashboard') }}">
                    <img src="{{ asset('media/logo.jpeg') }}" style="width: 200px;">
                </a>
            </div>
        </div>
    </nav>

    <div class="wrapper wrapper-full-page pb-3">
        <div class="full-page section-image {{ $sectionClass ?? '' }}" filter-color="black" data-image="{{ !empty($backgroundImagePath) ? asset('paper/'. $backgroundImagePath) : "" }}">
            @yield('content')
        </div>
    </div>
    @include('layouts.footer')

</body>

</html>
