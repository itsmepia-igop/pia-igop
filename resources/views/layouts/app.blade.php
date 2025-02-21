<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"> <!-- Adjust path if needed -->
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
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="{{ asset('paper/demo/demo.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    @stack('css')
  
    <style>
        body{
            background-image: linear-gradient(to bottom right, #6cb9eb ,#020080) !important;
        }
        .verification-card {
            margin-top:30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .verification-card .card-title {
            color: #007bff;
        }
        .verification-card .btn-primary {
            background-color: #0056b3;
            background-color: #007bff;
            border-color: #0056b3;
            font-size: 15px;
            transition: background-color 0.3s ease;
        }
        .verification-card .btn-primary:hover {
            background-color: #007bff;
            border-color: #003d82;
        }
        .verification-card .alert {
            margin-top: 1px;
        }
        .para{
            font-size: 15px;
        }
        .card-title{
            font-size: 25px;
        }
    </style>
</head>
<body>
 

    <main class="py-4">
        @yield('content') <!-- This is where child views will inject their content -->
    </main>

    <script src="{{ asset('js/app.js') }}"></script> <!-- Adjust path if needed -->
</body>
</html>
