@extends('layouts.auth', [
    'class' => 'overflow-auto mh-100 h-100',
    'navbarClass' => 'bg-white py-1',
    // 'backgroundImagePath' => 'img/bg/jan-sendereks.jpg'
])

@push('css')
    <style>
        .section-image:after {
            background-image: linear-gradient(to bottom right, #6cb9eb ,#020080);
        }
    </style>
@endpush

@section('content')
    <div class="content mt-4">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <form class="col-md-12" action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card">
                            <div class="card-header ">
                                <h4 class="card-title">{{ __('Register') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-left mb-0">Login Credentials</h6>
                                    </div>

                                    <div class="col-md-12 text-left">
                                        <div class="form-group mb-3">
                                            <label for="email">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}" placeholder="Email" required />
                                        </div>
                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-6 text-left">
                                        <label for="password">Password <span class="text-danger">*</span></label>
                                        <div class="form-group position-relative mb-3">
                                            <input type="password" class="form-control" name="password" id="password" placeholder="Password" required />
                                            <i class="fa fa-eye position-absolute top-50 translate-middle-y btn-password" style="right: 10px;"></i>
                                        </div>
                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-6 text-left">
                                        <label for="password_confirmation">Password Confirmation <span class="text-danger">*</span></label>
                                        <div class="form-group position-relative mb-3">
                                            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Password Confirmation" required />
                                            <i class="fa fa-eye position-absolute top-50 translate-middle-y btn-password" style="right: 10px;"></i>
                                        </div>
                                        @if ($errors->has('password_confirmation'))
                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="col-12 mt-4">
                                        <h6 class="text-left mb-0">Personal Information</h6>
                                    </div>

                                    <div class="col-md-6 text-left">
                                        <div class="form-group mb-3">
                                            <label for="name">Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="{{ old('name') }}" required />
                                        </div>
                                        @if ($errors->has('name'))
                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-6 text-left">
                                        <div class="form-group mb-3">
                                            <label for="surname">Surname <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="surname" id="surname" placeholder="Surname" value="{{ old('surname') }}" required />
                                        </div>
                                        @if ($errors->has('surname'))
                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                <strong>{{ $errors->first('surname') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="col-md-6 text-left">
                                        <div class="form-group mb-3">
                                            <label for="contact_number">Contact Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="contact_number" id="contact_number" placeholder="Contact Number" value="{{ old('contact_number') }}" required />
                                        </div>
                                        @if ($errors->has('contact_number'))
                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                <strong>{{ $errors->first('contact_number') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="col-12 mt-4">
                                        <h6 class="text-left">Address</h6>
                                    </div>

                                    <div class="col-md-6 text-left">
                                        <div class="form-group mb-3">
                                            <label for="region">Region <span class="text-danger">*</span></label>
                                            <select name="region" id="region" class="form-select" data-value="{{ old('region') }}" required>
                                                <option value="">Select an option</option>
                                                @foreach ($regions as $region)
                                                    <option value="{{ $region->region_code }}">{{ $region->region_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($errors->has('region'))
                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                <strong>{{ $errors->first('region') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-6 text-left">
                                        <div class="form-group mb-3">
                                            <label for="province">Province <span class="text-danger">*</span></label>
                                            <select name="province" id="province" class="form-select" data-value="{{ old('province') }}" required>
                                                <option value="">Select an option</option>
                                            </select>
                                        </div>
                                        @if ($errors->has('province'))
                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                <strong>{{ $errors->first('province') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-6 text-left">
                                        <div class="form-group mb-3">
                                            <label for="city">City <span class="text-danger">*</span></label>
                                            <select name="city" id="city" class="form-select" data-value="{{ old('city') }}" required>
                                                <option value="">Select an option</option>
                                            </select>
                                        </div>
                                        @if ($errors->has('city'))
                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                <strong>{{ $errors->first('city') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-6 text-left">
                                        <div class="form-group mb-3">
                                            <label for="barangay">Barangay <span class="text-danger">*</span></label>
                                            <select name="barangay" id="barangay" class="form-select" data-value="{{ old('barangay') }}" required>
                                                <option value="">Select an option</option>
                                            </select>
                                        </div>
                                        @if ($errors->has('barangay'))
                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                <strong>{{ $errors->first('barangay') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-12 text-left">
                                        <div class="form-group mb-3">
                                            <label for="street">Purok/Street/Subd. <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="street" id="street" placeholder="Street / Subd." value="{{ old('street') }}" required />
                                        </div>
                                        @if ($errors->has('street'))
                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                <strong>{{ $errors->first('street') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="col-md-12 mt-4">
                                        <div class="form-check text-center">
                                            <label class="form-check-label">
                                                <input class="form-check-input" name="agree_terms_and_conditions" type="checkbox">
                                                <span class="form-check-sign"></span>
                                                    {{ __('I agree to the') }}
                                                <a href="{{ route('terms.condition') }}" target="_blank">{{ __('terms and conditions') }}</a>.
                                            </label>
                                            @if ($errors->has('agree_terms_and_conditions'))
                                                <span class="invalid-feedback" style="display: block;" role="alert">
                                                    <strong>{{ $errors->first('agree_terms_and_conditions') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer ">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-info btn-round">{{ __('Register') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                {{-- <div class="col-lg-5 col-md-5 ml-auto">
                    <div class="info-area info-horizontal mt-5">
                        <div class="icon icon-primary">
                            <i class="nc-icon nc-tv-2"></i>
                        </div>
                        <div class="description">
                            <h5 class="info-title">{{ __('Marketing') }}</h5>
                            <p class="description">
                                {{ __('We\'ve created the marketing campaign of the website. It was a very interesting collaboration.') }}
                            </p>
                        </div>
                    </div>
                    <div class="info-area info-horizontal">
                        <div class="icon icon-primary">
                            <i class="nc-icon nc-html5"></i>
                        </div>
                        <div class="description">
                            <h5 class="info-title">{{ __('Fully Coded in HTML5') }}</h5>
                            <p class="description">
                                {{ __('We\'ve developed the website with HTML5 and CSS3. The client has access to the code using GitHub.') }}
                            </p>
                        </div>
                    </div>
                    <div class="info-area info-horizontal">
                        <div class="icon icon-info">
                            <i class="nc-icon nc-atom"></i>
                        </div>
                        <div class="description">
                            <h5 class="info-title">{{ __('Built Audience') }}</h5>
                            <p class="description">
                                {{ __('There is also a Fully Customizable CMS Admin Dashboard for this product.') }}
                            </p>
                        </div>
                    </div>
                </div> --}}
                {{-- <div class="col-lg-4 col-md-6 ml-auto mr-auto">
                    <div class="card card-signup text-center">
                        <div class="card-header ">
                            <h4 class="card-title">{{ __('Register') }}</h4>
                            <div class="social">
                                <button class="btn btn-icon btn-round btn-twitter">
                                    <i class="fa fa-twitter"></i>
                                </button>
                                <button class="btn btn-icon btn-round btn-dribbble">
                                    <i class="fa fa-dribbble"></i>
                                </button>
                                <button class="btn btn-icon btn-round btn-facebook">
                                    <i class="fa fa-facebook-f"></i>
                                </button>
                                <p class="card-description">{{ __('or be classical') }}</p>
                            </div>
                        </div>
                        <div class="card-body ">
                            <form class="form" method="POST" action="{{ route('register') }}">
                                @csrf
                                <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                    <input name="email" type="email" class="form-control" placeholder="Email" required value="{{ old('email') }}">
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group position-relative{{ $errors->has('password') ? ' has-danger' : '' }}">
                                    <input name="password" type="password" class="form-control" placeholder="Password" required>
                                    <i class="fa fa-eye position-absolute top-50 translate-middle-y btn-password" style="right: 10px;"></i>
                                </div>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                                <div class="form-group position-relative">
                                    <input name="password_confirmation" type="password" class="form-control" placeholder="Password confirmation" required>
                                    <i class="fa fa-eye position-absolute top-50 translate-middle-y btn-password" style="right: 10px;"></i>
                                </div>
                                @if ($errors->has('password_confirmation'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                                <div class="form-check text-left">
                                    <label class="form-check-label">
                                        <input class="form-check-input" name="agree_terms_and_conditions" type="checkbox">
                                        <span class="form-check-sign"></span>
                                            {{ __('I agree to the') }}
                                        <a href="#something">{{ __('terms and conditions') }}</a>.
                                    </label>
                                    @if ($errors->has('agree_terms_and_conditions'))
                                        <span class="invalid-feedback" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('agree_terms_and_conditions') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="card-footer ">
                                    <button type="submit" class="btn btn-info btn-round">{{ __('Get Started') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> --}}
             </div>
        </div>
     </div> 
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            demo.checkFullPageBackgroundImage();
        });
    </script>
    @include('components.address-js')
@endpush
