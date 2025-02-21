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
    @stack('css')
</head>

<body class="{{ $class ?? '' }}">
    <div class="wrapper">

        @include('layouts.sidebar')
    
        <div class="main-panel">
            <!-- Header -->
            <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
                <div class="container-fluid">
                    <div class="navbar-wrapper">
                        <div class="navbar-toggle">
                            <button type="button" class="navbar-toggler">
                                <span class="navbar-toggler-bar bar1"></span>
                                <span class="navbar-toggler-bar bar2"></span>
                                <span class="navbar-toggler-bar bar3"></span>
                            </button>
                        </div>
                        <a class="navbar-brand" href="#pablo">{{ config('app.name') }}</a>
                    </div>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation"
                        aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-bar navbar-kebab"></span>
                        <span class="navbar-toggler-bar navbar-kebab"></span>
                        <span class="navbar-toggler-bar navbar-kebab"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end" id="navigation">
                        <ul class="navbar-nav">
                            <li class="nav-item btn-rotate dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    @if(!empty(auth()->user()->unreadNotifications) && count(auth()->user()->unreadNotifications) > 0)
                                        <span class="rounded-circle p-1 bg-success position-absolute start-50"></span>
                                    @endif
                                    <i class="nc-icon nc-bell-55"></i>
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
                                    <hr class="my-1">
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
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('My profile') }}</a>
                                    <a class="dropdown-item" onclick="document.getElementById('formLogOut').submit();">{{ __('Log out') }}</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>            
            
            @yield('content')

            @include('layouts.footer', ['containerCss' => 'container-fluid'])
        </div>

        <!-- Customize Sidebar -->
        {{-- <div class="fixed-plugin">
            <div class="dropdown show-dropdown">
                <a href="#" data-toggle="dropdown">
                    <i class="fa fa-cog fa-2x"> </i>
                </a>
                <ul class="dropdown-menu">
                    <li class="header-title"> Sidebar Background</li>
                    <li class="adjustments-line">
                        <a href="javascript:void(0)" class="switch-trigger background-color">
                            <div class="badge-colors text-center">
                                <span class="badge filter badge-light active" data-color="white"></span>
                                <span class="badge filter badge-dark" data-color="black"></span>
                            </div>
                            <div class="clearfix"></div>
                        </a>
                    </li>
                    <li class="header-title"> Sidebar Active Color</li>
                    <li class="adjustments-line text-center">
                        <a href="javascript:void(0)" class="switch-trigger active-color">
                            <span class="badge filter badge-primary" data-color="primary"></span>
                            <span class="badge filter badge-info" data-color="info"></span>
                            <span class="badge filter badge-success" data-color="success"></span>
                            <span class="badge filter badge-warning" data-color="warning"></span>
                            <span class="badge filter badge-danger active" data-color="danger"></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div> --}}
    </div>

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
    
    @stack('scripts')
    {{-- @vite(['resources/js/pages/customize.js']); --}}

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

            var hide_scroll = false;
            $(document).on('show.bs.modal', function () {
                if ($('html').hasClass('perfect-scrollbar-on')) {
                    hide_scroll = true;
                    $('html').removeClass('perfect-scrollbar-on');
                }
            });
            $(document).on('hide.bs.modal', function () {
                if (hide_scroll) {
                    hide_scroll = false;
                    $('html').addClass('perfect-scrollbar-on');
                }
            });
        });
    </script>
</body>

</html>
