@extends('layouts.auth', [
    'class' => 'overflow-auto mh-100 h-100',
    'navbarClass' => 'bg-white py-1',
    'sectionClass' => 'bg-light'
])

@section('content')
    <div class="content mt-4">
        <div class="container">
            @include('layouts.alert')
            <div class="row">
                <div class="col-md-12 text-center">
                    <form class="col-md-12" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card">
                            <div class="card-header">
                                <h5 class="title">{{ __('Edit Profile') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <h6 class="text-left mb-0">Personal Information</h6>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group text-left mb-3">
                                            <label for="name">Name <span class="text-danger">*</span></label>
                                            <input  type="text" class="form-control" name="name" id="name" placeholder="Name" value="{{ old('name', $profile->name ?? '') }}" required />
                                            @if ($errors->has('name'))
                                                <span class="invalid-feedback" style="display: block;" role="alert">
                                                    <strong>{{ $errors->first('name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group text-left mb-3">
                                            <label for="surname">Surname <span class="text-danger">*</span></label>
                                            <input  type="text" class="form-control" name="surname" id="surname" placeholder="Surname" value="{{ old('surname') }}" required />
                                            @if ($errors->has('surname'))
                                                <span class="invalid-feedback" style="display: block;" role="alert">
                                                    <strong>{{ $errors->first('surname') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group text-left mb-3">
                                            <label for="contact_number">Contact Number <span class="text-danger">*</span></label>
                                            <input  type="text" class="form-control" name="contact_number" id="contact_number" placeholder="Contact Number" value="{{ old('contact_number') }}" required />
                                            @if ($errors->has('contact_number'))
                                                <span class="invalid-feedback" style="display: block;" role="alert">
                                                    <strong>{{ $errors->first('contact_number') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group text-left mb-3">
                                            <label for="email">Email</label>
                                            <input  type="email" class="form-control" name="email" id="email" placeholder="Email" value="{{ auth()->user()->email ?? '' }}" readonly />
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 mt-4">
                                        <h6 class="text-left">Address</h6>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group text-left mb-3">
                                            <label for="region">Region <span class="text-danger">*</span></label>
                                            <select name="region" id="region" class="form-select" data-value="{{ old('region', $profile->region ?? '') }}" required>
                                                <option value="">Select an option</option>
                                                @foreach ($regions as $region)
                                                    <option value="{{ $region->region_code }}">{{ $region->region_name }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('region'))
                                                <span class="invalid-feedback" style="display: block;" role="alert">
                                                    <strong>{{ $errors->first('region') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group text-left mb-3">
                                            <label for="province">Province <span class="text-danger">*</span></label>
                                            <select name="province" id="province" class="form-select" data-value="{{ old('province', $profile->province ?? '') }}" required>
                                                <option value="">Select an option</option>
                                            </select>
                                            @if ($errors->has('province'))
                                                <span class="invalid-feedback" style="display: block;" role="alert">
                                                    <strong>{{ $errors->first('province') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group text-left mb-3">
                                            <label for="city">City <span class="text-danger">*</span></label>
                                            <select name="city" id="city" class="form-select" data-value="{{ old('city', $profile->city ?? '') }}" required>
                                                <option value="">Select an option</option>
                                            </select>
                                            @if ($errors->has('city'))
                                                <span class="invalid-feedback" style="display: block;" role="alert">
                                                    <strong>{{ $errors->first('city') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group text-left mb-3">
                                            <label for="barangay">Barangay <span class="text-danger">*</span></label>
                                            <select name="barangay" id="barangay" class="form-select" data-value="{{ old('barangay', $profile->barangay ?? '') }}" required>
                                                <option value="">Select an option</option>
                                            </select>
                                            @if ($errors->has('barangay'))
                                                <span class="invalid-feedback" style="display: block;" role="alert">
                                                    <strong>{{ $errors->first('barangay') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group text-left mb-3">
                                            <label for="street">Purok/Street/Subd. <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="street" id="street" placeholder="Street / Subd." value="{{ old('street', $profile->street ?? '') }}" required />
                                            @if ($errors->has('street'))
                                                <span class="invalid-feedback" style="display: block;" role="alert">
                                                    <strong>{{ $errors->first('street') }}</strong>
                                                </span>
                                            @endif
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

@push('scripts')
    @include('components.address-js')
@endpush