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

        <!-- Header -->
    <nav class="navbar navbar-expand-lg fixed-top bg-white py-1">
            <div class="navbar-wrapper">
                <a class="navbar-brand" href="{{ route('dashboard') }}">
                    <img src="{{ asset('media/logo.jpeg') }}" style="width: 200px; margin-left:103px">
                </a>
            </div>
        </div>
    </nav>  
@extends('layouts.app')

@section('content')
<div class="container py-5">


    <div class="card verification-card border-1">
        <div class="card-body text-center">
            <h4 class="card-title">Email Verification Required</h4>
            <p class="para">
                Please verify your email address by clicking the link we sent to your email. 
                If you didn’t receive the email, click the button below to resend the verification link.
            </p>

            @if (session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

            <form method="POST" action="{{ route('verification.resend') }}" class="mt-4">
                @csrf
                <button type="submit" class="btn btn-primary btn-md">
                    <i class="fas fa-envelope"></i> Resend Verification Email
                </button>
            </form>
        </div>
    </div>
</div>
@endsection