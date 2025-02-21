@extends('layouts.auth', [
    'class' => 'overflow-auto mh-100 h-100',
    'navbarClass' => 'bg-white py-1',
    'sectionClass' => 'bg-light'
])

@push('css')
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
@endpush

@section('content')
    <div class="content mt-4">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('user.product.checkout.store', $product->id) }}" method="post" id="checkoutForm" enctype="multipart/form-data">
                        @csrf
    
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0"><i class="nc-icon nc-basket me-3"></i>Checkout - <span class="fw-bold">{{ $product->name }}</span></h4>
                                <div class="text-left">Required Fields (<span class="text-danger">*</span>)</div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <img src="{{ Storage::url($product->file->path) }}" alt="{{ $product->file->file_name }}" class="w-100"  style="object-fit: cover;">
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Description</h6>
                                        <p>{{ $product->description }}</p>
                                        {{-- @if (!$product->is_customize) --}}
                                        <h6>Available Quantity</h6>
                                        <p>{{ $product->quantity }}</p>
                                        {{-- @endif --}}
                                        <h6>Price</h6>
                                        <p>₱{{ number_format($product->price, 2) }}</p>
                                        @if ($product->is_customize)
                                            <h6>Raw Materials</h6>
                                            <ul class="list-group">
                                                @foreach ($product->raw_materials as $material)
                                                    <li class="list-group-item mb-0">({{ $material->count }}) {{ $material->material->name }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
    
                                <hr>
                                <div class="row">
                                    <div class="col-md-6 text-left">
                                        <div class="form-group">
                                            <label for="size">Sizes <span class="text-danger">*</span></label>
                                            <select class="form-control js-select2" data-tags="true" id="size" name="size" placeholder="Size" required>
                                                <option value="">Select an option or Input preferred size...</option>
                                                @foreach (json_decode($product->sizes ?? '[]') as $size)
                                                    <option placeholder ='Input preferred size'value="{{ $size }}" {{ old('size') == $size ? 'selected' : '' }}>{{ $size }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('size'))
                                                <span class="invalid-feedback" style="display: block;" role="alert">
                                                    <strong>{{ $errors->first('size') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    @if ($product->is_customize)
                                        <div class="col-md-6 text-left">
                                            <div class="form-group">
                                                <label for="thickness">Thickness <span class="text-danger">*</span></label>
                                                <select class="form-control" id="thickness" name="thickness" placeholder="Thickness" required>
                                                    <option value="">Select an option</option>
                                                    @foreach (json_decode($product->thickness ?? '[]') as $thickness)
                                                        <option value="{{ $thickness }}" {{ old('thickness') == $thickness ? 'selected' : '' }}>{{ $thickness }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('thickness'))
                                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                                        <strong>{{ $errors->first('thickness') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="">
                                                <label for="design">Design (optional)</label>
                                                <input type="file" class="form-control" id="design" name="design" placeholder="Design (optional)" accept="image/*" />
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="note">Note</label>
                                                <textarea class="form-control" id="note" name="note" placeholder="Note">{{ old('note') }}</textarea>
                                                @if ($errors->has('note'))
                                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                                        <strong>{{ $errors->first('note') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
    
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h6>Delivery Address</h6>
                                    </div>
                                    <div class="col-md-6 text-left">
                                        <div class="form-group text-left mb-3">
                                            <label for="region">Region <span class="text-danger">*</span></label>
                                            <select name="region" id="region" class="form-select" data-value="{{ old('region', auth()->user()->profile->region) }}" required>
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
                                    <div class="col-md-6 text-left">
                                        <div class="form-group text-left mb-3">
                                            <label for="province">Province <span class="text-danger">*</span></label>
                                            <select name="province" id="province" class="form-select" data-value="{{ old('province', auth()->user()->profile->province) }}" required>
                                                <option value="">Select an option</option>
                                            </select>
                                            @if ($errors->has('province'))
                                                <span class="invalid-feedback" style="display: block;" role="alert">
                                                    <strong>{{ $errors->first('province') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-left">
                                        <div class="form-group text-left mb-3">
                                            <label for="city">City <span class="text-danger">*</span></label>
                                            <select name="city" id="city" class="form-select" data-value="{{ old('city', auth()->user()->profile->city) }}" required>
                                                <option value="">Select an option</option>
                                            </select>
                                            @if ($errors->has('city'))
                                                <span class="invalid-feedback" style="display: block;" role="alert">
                                                    <strong>{{ $errors->first('city') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-left">
                                        <div class="form-group text-left mb-3">
                                            <label for="barangay">Barangay <span class="text-danger">*</span></label>
                                            <select name="barangay" id="barangay" class="form-select" data-value="{{ old('barangay', auth()->user()->profile->barangay) }}" required>
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
                                            <input type="text" class="form-control" name="street" id="street" placeholder="Street / Subd." value="{{ old('street', auth()->user()->profile->street ?? '') }}" required />
                                            @if ($errors->has('street'))
                                                <span class="invalid-feedback" style="display: block;" role="alert">
                                                    <strong>{{ $errors->first('street') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 mt-5">
                                        <div class="ms-auto w-25">
                                            <div class="d-flex gap-2 justify-content-end align-items-end">
                                                <p class="mb-0"><i>Avialable Quantity:</i></p>
                                                <h5 class="mb-0">{{ $product->quantity }}</h5>
                                            </div>
                                            <div class="form-group text-left mb-3">
                                                <label for="quantity">Quantity <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" id="quantity" name="quantity" placeholder ="Minimum of 300 pcs" value="{{ old('quantity', '300') }}" max="{{ $product->quantity }}" required />
                                                @if ($errors->has('quantity'))
                                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                                        <strong>{{ $errors->first('quantity') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <hr>
                                <div class="d-flex gap-3 align-items-center justify-content-end">
                                    <h5 class="mb-0"><span class="fw-bold">Total: </span>₱<span class="total">{{ number_format($product->price * 300, 2) }}</span></h5>
                                    <button type="submit" class="btn btn-primary" id="btn-checkout">Checkout</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('products.modal')
@endsection

@push('scripts')
    <script src="{{ asset('plugins/select2/select2.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.js-select2').select2();

            $('#btn-checkout').on('click', function () {
                var quantity = $('#quantity')[0];
                if (quantity.value < 300) {
                    quantity.setCustomValidity('Order quantity must be at least 300.');
                } else {
                    quantity.setCustomValidity('');
                }
            });

            $('#quantity').on('input', function () {
                $(this)[0].setCustomValidity('');

                var price = {{ Js::from(number_format($product->price, 2)) }}
                var quantity = $(this).val();

                if (quantity && parseFloat(quantity) > $(this).attr('max')) {
                    quantity = 0;
                    $('#btn-checkout').prop('disabled', true);
                } else {
                    $('#btn-checkout').prop('disabled', false);
                }
                var total = parseFloat(price) * parseFloat(quantity ? quantity : 0);
                
                $('.card-footer .total').text(total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $('#total').val(total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            });
        });
    </script>
    @include('components.address-js')
@endpush