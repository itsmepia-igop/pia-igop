@extends('layouts.backend', [
    'class' => '',
    'elementActive' => 'profile'
]) 

@section('content')
    <div class="content">
        <div class="container">
            @include('layouts.alert')
            
            <div class="row">
                <div class="col-md-6 text-center">
                    <form class="col-md-12" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card">
                            <div class="card-header">
                                <h5 class="title">{{ __('Edit Profile') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group text-left mb-3">
                                            <label for="name">Name</label>
                                            <input  type="text" class="form-control" name="name" id="name" placeholder="Name" value="{{ old('name', $profile->name ?? '') }}" required />
                                        </div>
                                        @if ($errors->has('name'))
                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group text-left mb-3">
                                            <label for="surname">Surname</label>
                                            <input  type="text" class="form-control" name="surname" id="surname" placeholder="Surname" value="{{ old('surname', $profile->surname ?? '') }}" required />
                                        </div>
                                        @if ($errors->has('surname'))
                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                <strong>{{ $errors->first('surname') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="form-group text-left mb-3">
                                            <label for="email">Email</label>
                                            <input  type="email" class="form-control" name="email" id="email" placeholder="Email" value="{{ auth()->user()->email ?? '' }}" readonly />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer ">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-info btn-round">{{ __('Save Changes') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 text-center">
             
                    <form class="col-md-12" action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card">
                            <div class="card-header">
                                <h5 class="title">{{ __('Change Password') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <label class="col-md-3 col-form-label">{{ __('Old Password') }}</label>
                                    <div class="col-md-9">
                                        <div class="form-group position-relative">
                                            <input type="password" name="old_password" class="form-control" placeholder="Old password" required>
                                            <i class="fa fa-eye position-absolute top-50 translate-middle-y btn-password" style="right: 10px;"></i>
                                        </div>
                                        @if ($errors->has('old_password'))
                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                <strong>{{ $errors->first('old_password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-md-3 col-form-label">{{ __('New Password') }}</label>
                                    <div class="col-md-9">
                                        <div class="form-group position-relative">
                                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                                            <i class="fa fa-eye position-absolute top-50 translate-middle-y btn-password" style="right: 10px;"></i>
                                        </div>
                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-md-3 col-form-label">{{ __('Password Confirmation') }}</label>
                                    <div class="col-md-9">
                                        <div class="form-group position-relative">
                                            <input type="password" name="password_confirmation" class="form-control" placeholder="Password Confirmation" required>
                                            <i class="fa fa-eye position-absolute top-50 translate-middle-y btn-password" style="right: 10px;"></i>
                                        </div>
                                        @if ($errors->has('password_confirmation'))
                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer ">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-info btn-round">{{ __('Save Changes') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection