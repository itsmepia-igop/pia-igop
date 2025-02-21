@extends('layouts.backend')

@push('css')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive-bs5/css/responsive.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
@endpush

@section('content')
    <div class="content">
        @include('layouts.alert')
        <div class="row">
            <div class="col-md-12">
                <div class="">
                    <!-- <button type="button" class="btn btn-primary btn-round btn-add float-end">Add Payment Option +</button> -->
                    <h4 class="mt-0">
                        Payment Options
                    </h4>
                </div>

                <form action="{{ route('admin.settings.save.payments') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- <div class="row payment-list">
                        @if (count($options))
                            @foreach ($options as $option)
                                <div class="payment-item col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="text-end">
                                                <button type="button" class="btn-close"></button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="qr-display">
                                                @if (!empty($option->qr))
                                                    <img src="{{ Storage::url($option->qr) }}" alt="Payment QR Code" style="width: 250px; height: 200px; object-fit: cover;">
                                                    <input type="hidden" class="form-control" id="data[{{ $loop->index }}][previous_qr]" name="data[{{ $loop->index }}][previous_qr]" value="{{ Storage::url($option->qr) }}" >
                                                @endif
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="data[{{ $loop->index }}][bank]">Bank Name</label>
                                                <input type="text" class="form-control" id="data[{{ $loop->index }}][bank]" name="data[{{ $loop->index }}][bank]" value="{{ $option->bank ?? '' }}" placeholder="Bank Name" required />
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="data[{{ $loop->index }}][number]">Account/Phone Number</label>
                                                <input type="text" class="form-control" id="data[{{ $loop->index }}][number]" name="data[{{ $loop->index }}][number]" value="{{ $option->number ?? '' }}" placeholder="Account/Phone Number" required />
                                            </div>
                                            <div class="design-form my-3">
                                                <label for="data[{{ $loop->index }}][qr]">QR Code (optional)</label>
                                                <input type="file" class="form-control" id="data[{{ $loop->index }}][qr]" name="data[{{ $loop->index }}][qr]" placeholder="QR Code" accept="image/*" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="payment-item col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="text-end">
                                            <button type="button" class="btn-close disabled"></button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label for="data[0][bank]">Bank Name</label>
                                            <input type="text" class="form-control" id="data[0][bank]" name="data[0][bank]" placeholder="Bank Name" value="G-Cash" required />
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="data[0][number]">Account/Phone Number</label>
                                            <input type="text" class="form-control" id="data[0][number]" name="data[0][number]" placeholder="Account/Phone Number" required />
                                        </div>
                                        <div class="design-form my-3">
                                            <label for="data[0][qr]">QR Code (optional)</label>
                                            <input type="file" class="form-control" id="data[0][qr]" name="data[0][qr]" placeholder="QR Code" accept="image/*" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div> --}}
                    
                    <div class="payment-item col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="qr-display">
                                    @if (!empty($option->qr))
                                        <img src="{{ Storage::url($option->qr) }}" alt="Payment QR Code" style="width: 250px; height: 200px; object-fit: cover;">
                                        <input type="hidden" class="form-control" id="data[0][previous_qr]" name="data[0][previous_qr]" value="{{ Storage::url($option->qr) }}" >
                                    @endif
                                </div>
                                <div class="form-group mb-3">
                                    <label for="data[0][bank]">Bank Name</label>
                                    <input type="text" class="form-control disabled" id="data[0][bank]" name="data[0][bank]" placeholder="Bank Name" value="G-Cash" readonly />
                                </div>
                                <div class="form-group mb-3">
                                    <label for="data[0][number]">Account/Phone Number</label>
                                    <input type="text" class="form-control" id="data[0][number]" name="data[0][number]" placeholder="Account/Phone Number" value="{{ $option->number ?? '' }}" required />
                                </div>
                                <div class="design-form my-3">
                                    <label for="data[0][qr]">QR Code (optional)</label>
                                    <input type="file" class="form-control" id="data[0][qr]" name="data[0][qr]" placeholder="QR Code" accept="image/*" {{ !empty($option->qr) ? '' : 'required' }} />
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-info btn-round">Save Payment Details</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="card payment-item col-md-4 d-none">
                        <div class="card-body">
                            <div class="">
                                @if (!empty($qr_payment))
                                    <img src="{{ Storage::url(json_decode($qr_payment->content)->path) }}" alt="Payment QR Code" style="width: 250px; height: 200px; object-fit: cover;">
                                @endif
                            </div>
                            <div class="form-group mb-3">
                                <label for="number">Phone Number</label>
                                <input type="text" class="form-control" name="number" id="number" maxlength="11" placeholder="Phone Number" value="{{ old('number', !empty($qr_payment) ? (json_decode($qr_payment->content)->number ?? '') : '') }}" required />
                                @if ($errors->has('number'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('number') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="design-form my-3">
                                <label for="file">QR Code</label>
                                <input type="file" class="form-control" id="file" name="file" placeholder="QR Code" accept="image/*" required />
                                @if ($errors->has('file'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('file') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div> --}}

                    <!-- <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-info btn-round">Save Payment Details</button>
                        </div>
                    </div> -->
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.btn-add').on('click', function () {
                var $index = $('.payment-list .payment-item:last-child').index();
                var $item = $('.payment-list .payment-item:last-child').clone();
                    $item.find('label').each(function () {
                        var label = $(this).attr('for');
                        $(this).attr('for', label.replace(/\d+/, $index + 1));
                    });
                    $item.find('.form-control').each(function () {
                        var id = $(this).attr('id');
                        $(this).attr('id', id.replace(/\d+/, $index + 1));
                        $(this).attr('name', id.replace(/\d+/, $index + 1));
                        $(this).val('');
                    });
                    $item.find('.qr-display').remove();

                $('.payment-list').append($item);
                $('.payment-list').find('.btn-close').removeClass('disabled');
            });

            $(document).on('click', '.btn-close', function () {
                $(this).closest('.payment-item').remove();

                $('.payment-list .payment-item').each(function () {
                    var $item = $(this);
                    var $index = $(this).index();
                    $item.find('label').each(function () {
                        var label = $(this).attr('for');
                        $(this).attr('for', label.replace(/\d+/, $index));
                    });
                    $item.find('.form-control').each(function () {
                        var id = $(this).attr('id');
                        $(this).attr('id', id.replace(/\d+/, $index));
                        $(this).attr('name', id.replace(/\d+/, $index));
                    });
                });

                if ($('.payment-list .payment-item').length > 1) {
                    $('.payment-list').find('.btn-close').removeClass('disabled');
                } else {
                    $('.payment-list').find('.btn-close').addClass('disabled');
                }
            });
        });
    </script>
@endpush