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
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="{{ asset('paper/demo/demo.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    @stack('css')
</head>

<body class="{{ $class ?? '' }}">

    <!-- Header -->
    <nav class="navbar navbar-expand-lg fixed-top {{ $navbarClass ?? 'navbar-absolute navbar-transparent' }}">
        <div class="container">
            <div class="navbar-wrapper">
                {{-- <div class="navbar-toggle">
                    <button type="button" class="navbar-toggler">
                        <span class="navbar-toggler-bar bar1"></span>
                        <span class="navbar-toggler-bar bar2"></span>
                        <span class="navbar-toggler-bar bar3"></span>
                    </button>
                </div> --}}
                <a class="navbar-brand" href="{{ route('dashboard') }}">
                    <img src="{{ asset('media/logo.jpeg') }}" style="width: 200px;">
                </a>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-bar navbar-kebab"></span>
                <span class="navbar-toggler-bar navbar-kebab"></span>
                <span class="navbar-toggler-bar navbar-kebab"></span>
            </button>
            @auth
                <div class="collapse navbar-collapse justify-content-end" id="navigation">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="nc-icon nc-bank"></i>
                                <p>
                                    <span class="d-block">{{ __('Home') }}</span>
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.orders.list') }}" 
                                {{-- id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" --}}
                                >
                                <i class="nc-icon nc-basket"></i>
                                <p>
                                    <span class="d-block">{{ __('Orders') }}</span>
                                </p>
                            </a>
                            {{-- <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="{{ route('user.orders.list', 'pending') }}">{{ __('Pending Orders') }}</a>
                                <a class="dropdown-item" href="{{ route('user.orders.list', 'to-pay') }}">{{ __('To Pay Orders') }}</a>
                                <a class="dropdown-item" href="{{ route('user.orders.list', 'to-review') }}">{{ __('To Review Orders') }}</a>
                                <a class="dropdown-item" href="{{ route('user.orders.list', 'approved') }}">{{ __('Approved Orders') }}</a>
                                <a class="dropdown-item" href="{{ route('user.orders.list', 'rejected') }}">{{ __('Rejected Orders') }}</a>
                                <a class="dropdown-item" href="{{ route('user.orders.list', 'completed') }}">{{ __('Completed Orders') }}</a>
                                <a class="dropdown-item" href="{{ route('user.orders.list') }}">{{ __('All Orders') }}</a>
                            </div> --}}
                        </li>
                        <li class="nav-item btn-rotate dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <div class="position-relative" style="display: inline-block;">
                                    @if(!empty(auth()->user()->unreadNotifications) && count(auth()->user()->unreadNotifications) > 0)
                                        <span class="rounded-circle p-1 bg-success position-absolute" style="right: -20%;"></span>
                                    @endif
                                    <i class="nc-icon nc-bell-55"></i>
                                </div>
                                <p>
                                    <span class="d-lg-none d-md-block">{{ __('Notifications') }}</span>
                                </p>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                                @if(!empty(auth()->user()->unreadNotifications) && count(auth()->user()->unreadNotifications) > 0)
                                    @foreach(auth()->user()->unreadNotifications->take(5) as $notification)
                                        <a class="dropdown-item px-3" href="{{ $notification->data['link'] ?? '' }}?read={{ $notification->id }}">
                                            <div>
                                                {{ $notification->data['message'] }}
                                            </div>
                                            <small class="text-end d-block fst-italic" data-bs-toggle="tooltip"  title="{{ $notification->created_at->format('d/m/Y h:i A') }}">{{ $notification->created_at->diffForHumans() }}</small>
                                        </a>
                                    @endforeach
                                @else
                                    <span class="dropdown-item px-3">
                                        <div>No Unread Notifications</div>
                                    </span>
                                @endif
                                <hr class="my-0">
                                <a class="dropdown-item px-3" href="{{ route('notifications') }}">
                                    <div class="text-center">
                                        View all notifications
                                    </div>
                                </a>
                            </div>
                        </li>
                        <li class="nav-item btn-rotate dropdown">
                            <a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink2"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="nc-icon nc-settings-gear-65"></i>
                                <p>
                                    <span class="d-lg-none d-md-block">{{ __('Account') }}</span>
                                </p>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink2">
                                <form class="dropdown-item" action="{{ route('logout') }}" id="formLogOut" method="POST" style="display: none;">
                                    @csrf
                                </form>
                                {{-- <a class="dropdown-item" href="{{ route('dashboard') }}">{{ __('Home') }}</a>
                                <a class="dropdown-item" href="{{ route('user.orders.list') }}">{{ __('My Orders') }}</a> --}}
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('My profile') }}</a>
                                <a class="dropdown-item" onclick="document.getElementById('formLogOut').submit();">{{ __('Log out') }}</a>
                            </div>
                        </li>
                    </ul>
                </div>
            @endauth
            @guest
                <div class="collapse navbar-collapse justify-content-end" id="navigation">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="{{ route('register') }}" class="nav-link">
                                <i class="nc-icon nc-book-bookmark"></i>{{ __('Register') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link">
                                <i class="nc-icon nc-tap-01"></i>{{ __('Login') }}
                            </a>
                        </li>
                    </ul>
                </div>
            @endguest
        </div>
    </nav>

    <div class="wrapper wrapper-full-page pb-3">
        <div class="full-page section-image {{ $sectionClass ?? '' }}" filter-color="black" data-image="{{ !empty($backgroundImagePath) ? asset('paper/'. $backgroundImagePath) : "" }}">
            @yield('content')
        </div>
    </div>
    @include('layouts.footer')

    <!--   Core JS Files   -->
    <script src="{{ asset('paper/js/core/jquery.min.js') }}"></script>
    <script src="{{ asset('paper') }}/js/core/popper.min.js"></script>
    <script src="{{ asset('plugins/bootstrap-5.3.3/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('paper/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('paper/js/plugins/perfect-scrollbar.jquery.min.js') }}"></script>
    <!-- Chart JS -->
    <script src="{{ asset('paper') }}/js/plugins/chartjs.min.js"></script>
    <!--  Notifications Plugin    -->
    <script src="{{ asset('paper/js/plugins/bootstrap-notify.js') }}"></script>
    <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('paper/js/paper-dashboard.js?v=2.0.0') }}" type="text/javascript"></script>
    <!-- Paper Dashboard DEMO methods, don't include it in your project! -->
    <script src="{{ asset('paper') }}/demo/demo.js"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    
    @stack('scripts')

    @auth
        @if (session('login-sucess'))
            @php
                session()->forget('login-sucess')
            @endphp
            <script>
                $(document).ready(function () {
                    Swal.fire({
                        title: "Login Successfully!",
                        text: "",
                        icon: 'success',
                        confirmButtonText: 'Close',
                    })
                });
            </script>
        @endif
    @endauth

    <script>
        $(document).ready(function () {
            $('body').on('input', 'input[type=number]', function () {
                if ($(this).attr('min')) {
                    if ($(this).val() < $(this).attr('min')) {
                        $(this).val($(this).attr('min'));
                    }
                }
            });
            
            $('body').on('click', '.btn-password', function () {
                var parent = $(this).closest('div');
                
                if ($(this).hasClass('fa-eye')) {
                    $(this).removeClass('fa-eye');
                    $(this).addClass('fa-eye-slash');
                    parent.find('input').attr('type', 'text');
                } else {
                    $(this).removeClass('fa-eye-slash');
                    $(this).addClass('fa-eye');
                    parent.find('input').attr('type', 'password');
                }
            });
        });
    </script>
</body>

</html>
