@extends('layouts.backend')

@push('css')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <style>
        .swal-wide {
            width:850px !important;
        }
        .swal-wide img {
            width: 100%;
            max-width: 100% !important;
        }
    </style>
@endpush

@section('content')
    <div class="content">
        @include('layouts.alert')
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">
                            <div class="d-flex">
                                <i class="nc-icon nc-basket me-3"></i>
                                <div class="">
                                    <div class="">
                                        Order - <span class="fw-bold">{{ $order->product->name }} ({{ $order->status->name }})</span>
                                    </div>
                                    <div class="">
                                        <small class="fs-6"><span class="fw-bold">Last Update</span> {{ $order->updated_at->format('M d, Y') }}</small>
                                    </div>
                                </div>
                            </div>
                        </h4>
                        <div>
                            @if ($order->status->name == "Pending")
                                <button type="button" class="btn btn-primary me-3 swal-button" data-title="Accept Order?" data-icon="info" data-target="#toPayForm">Accept</button>
                                <form action="{{ route('admin.order.change.status', ['status' => 'to-pay', 'id' => $order->id]) }}" id="toPayForm" method="POST" class="d-none">
                                    @csrf
                                </form>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">Reject</button>
                                {{-- <button type="button" class="btn btn-danger swal-button" data-title="Rejection Message" data-icon="warning" data-input="textarea" data-target="#rejectForm">Reject</button>
                                <form action="{{ route('admin.order.change.status', ['status' => 'rejected', 'id' => $order->id]) }}" id="rejectForm" method="POST" class="d-none">
                                    @csrf
                                    <input type="hidden" name="rejection_message" id="rejection_message">
                                </form> --}}
                            @elseif ($order->status->name == 'To Review Payment')
                                <button type="button" class="btn btn-primary me-3 swal-button" data-title="Approve Payment?" data-icon="info" data-target="#approvePayment">Approve Payment</button>
                                <form action="{{ route('admin.order.change.status', ['status' => 'on-process', 'id' => $order->id]) }}" id="approvePayment" method="POST" class="d-none">
                                    @csrf
                                </form>
                                @if ($order->re_request_count < 3)
                                    {{-- <button type="button" class="btn btn-warning me-3 swal-button" data-title="Resubmit Payment Request??" data-icon="info" data-target="#reuploadPayment">Resubmit Payment</button>
                                    <form action="{{ route('admin.order.change.status', ['status' => 'to-pay', 'id' => $order->id]) }}" id="reuploadPayment" method="POST" class="d-none">
                                        @csrf
                                    </form> --}}
                                    <button type="button" class="btn btn-warning me-3" data-bs-toggle="modal" data-bs-target="#reRequestModal">Resubmit Payment</button>
                                @endif
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">Reject</button>
                                {{-- <button type="button" class="btn btn-danger swal-button" data-title="Rejection Message" data-icon="warning" data-input="textarea" data-target="#rejectForm">Reject</button>
                                <form action="{{ route('admin.order.change.status', ['status' => 'rejected', 'id' => $order->id]) }}" id="rejectForm" method="POST" class="d-none">
                                    @csrf
                                    <input type="hidden" name="rejection_message" id="rejection_message">
                                </form> --}}
                            @elseif ($order->status->name == 'On Process')
                                <button type="button" class="btn btn-primary me-3 swal-button" data-title="Deliver Item?" data-icon="info" data-target="#toDeliver">Deliver Item</button>
                                <form action="{{ route('admin.order.change.status', ['status' => 'to-deliver', 'id' => $order->id]) }}" id="toDeliver" method="POST" class="d-none">
                                    @csrf
                                </form>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">Reject</button>
                            @endif
                            @if (!empty($order->estimate_delivery))
                                <div class="">
                                    <small class="fs-6"><span class="fw-bold">ETA</span> <span class="text-decoration-underline">{{ $order->estimate_delivery->format('M d, Y') }} - {{ $order->estimate_delivery->addDays(3)->format('M d, Y') }}</span></small>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if (!empty($order->re_request_reason))
                            <div class="col-md-12">
    <h6 class="text-danger font-weight-bold">Reason for Payment Resubmission</h6>
    <p class="mb-0 text-dark font-weight-bold">{{ $order->re_request_reason ?? '' }}</p>
</div>
                            @endif
                            @if ($order->status->name == "Rejected")
                                <div class="col-md-12">
                                    <h6>Rejection Message</h6>
                                    <p class="mb-0">{{ $order->rejection_message ?? '' }}</p>
                                </div>
                            @elseif ($order->status->name == "Cancelled")
                                <div class="col-md-12">
                                    <h6>Cancellation Reason</h6>
                                    <p class="mb-0">{{ $order->cancel_reason ?? '' }}</p>
                                </div>
                            @endif
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <img src="{{ Storage::url($order->product->file->path) }}" alt="{{ $order->product->file->file_name }}" class="w-100"  style="object-fit: cover;">
                            </div>
                            <div class="col-md-6">
                                <h6>Description</h6>
                                <p>{{ $order->product->description }}</p>
                                <h6>Price</h6>
                                <p>₱ {{ number_format($order->product->price, 2) }}</p>
                                @if ($order->product->is_customize)
                                    <h6>Raw Materials</h6>
                                    <ul class="list-group">
                                        @foreach ($order->product->raw_materials as $material)
                                            <li class="list-group-item mb-0">({{ $material->count }}) {{ $material->material->name }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <h5><span class="fw-bold">Order Information</h5>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="d-flex gap-2 align-items-end">
                                    <h6 class="mb-0">Order Reference: </h6>
                                    <p class="mb-0">{{ $order->reference }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6>Delivery Address</h6>
                                <p>{{ $order->street .', '. $order->city .', '. $order->province .', '. $order->region }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Quantity</h6>
                                <p>{{ $order->quantity }}</p>
                            </div>
                            @if (!empty($order->size))
                                <div class="col-md-6">
                                    <h6>Size</h6>
                                    <p>{{ $order->size ?? '' }}</p>
                                </div>
                            @endif
                            @if ($order->product->is_customize)
                                <div class="col-md-6">
                                    <h6>Thickness</h6>
                                    <p>{{ $order->thickness ?? '' }}</p>
                                </div>
                                @if (!empty($order->note))
                                    <div class="col-md-6">
                                        <h6>Note</h6>
                                        <p>{{ $order->note}}</p>
                                    </div>
                                @endif
                                @if (!empty($order->design))
                                    <div class="col-md-6">
                                        <h6>Custom Design</h6>
                                        <img src="{{ Storage::url($order->design->path) }}" alt="" style="max-width: 300px;">
                                    </div>
                                @endif
                            @endif
                        </div>
                        @if (!empty($order->payment_reference) || !empty($order->payment))
                            <hr>
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5><span class="fw-bold">Payment Information</h5>
                                </div>
                                @if (!empty($order->payment_reference))
                                    <div class="col-md-12">
                                        <h6>Reference No.</h6>
                                        <p>{{ $order->payment_reference }}</p>
                                    </div>
                                @endif
                                @if (!empty($order->payment_type))
                                    <div class="col-md-12">
                                        <h6>Payment Type</h6>
                                        <p>{{ $order->payment_type }}</p>
                                    </div>
                                @endif
                                @if (!empty($order->payment))
                                    <div class="col-md-12">
                                        <h6>Payment</h6>
                                        <a href="#" class="btn-image"><img src="{{ Storage::url($order->payment_file->path) }}" style="max-width: 300px;"></a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <hr>
                        <div class="d-flex gap-3 align-items-center justify-content-end">
                            <h5 class="mb-0"><span class="fw-bold">Total: </span>₱<span class="total">{{ number_format($order->total, 2) }}</span></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (in_array($order->status->name, ["Pending", "To Review Payment", "On Process"]))
        <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title mb-0" id="rejectLabel">Reject Order</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.order.change.status', ['status' => 'rejected', 'id' => $order->id]) }}" method="post" id="reject">
                            @csrf
                            <p>Select a reason for rejecting the order.</p>
                            <div class="form-check mb-2">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" value="Out of Stock" id="rejection_message" name="rejection_message" />
                                    Out of Stock
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" value="Payment Issues" id="rejection_message" name="rejection_message" />
                                    Payment Issues
                                </label>
                            </div>
                            {{-- <div class="form-check mb-2">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" value="Delay in Delivery" id="rejection_message" name="rejection_message" />
                                    Delay in Delivery
                                </label>
                            </div> --}}
                            <div class="form-check mb-2">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" value="Damaged Goods" id="rejection_message" name="rejection_message" />
                                    Damaged Goods
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" value="Regulatory Restrictions" id="rejection_message" name="rejection_message" />
                                    Regulatory Restrictions
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" value="Vendor or Partner Issues" id="rejection_message" name="rejection_message" />
                                    Vendor or Partner Issues
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" value="Address or Contact Errors" id="rejection_message" name="rejection_message" />
                                    Address or Contact Errors
                                </label>
                            </div>
                            {{-- <div class="form-check mb-2">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" value="Natural Disasters or Unforeseen Events" id="rejection_message" name="rejection_message" />
                                    Natural Disasters or Unforeseen Events
                                </label>
                            </div> --}}
                            <div class="form-check mb-2">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" value="Other" id="rejection_message" name="rejection_message" />
                                    Other (please specify)
                                    <input type="text" class="form-control other-text" name="specific_reason">
                                </label>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" form="reject">Continue</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    
    @if (in_array($order->status->name, ["To Review Payment"]))
        <div class="modal fade" id="reRequestModal" tabindex="-1" aria-labelledby="reRequestLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title mb-0" id="reRequestLabel">Resubmit Payment</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.order.change.status', ['status' => 'to-pay', 'id' => $order->id]) }}" method="post" id="re_request">
                            @csrf
                            <p>Select a reason for re requestion of payment.</p>
                            <div class="form-check mb-2">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" value="Payment Failed" id="re_request_reason" name="re_request_reason" />
                                    Payment Failed
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" value="Incorrect Payment Information" id="re_request_reason" name="re_request_reason" />
                                    Incorrect Payment Information
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" value="Payment Not Received" id="re_request_reason" name="re_request_reason" />
                                    Payment Not Received
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" value="Incorrect Amount" id="re_request_reason" name="re_request_reason" />
                                    Incorrect Amount
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" value="Fraud Prevention Checks" id="re_request_reason" name="re_request_reason" />
                                    Fraud Prevention Checks
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" value="Other" id="re_request_reason" name="re_request_reason" />
                                    Other (please specify)
                                    <input type="text" class="form-control other-text" name="specific_reason">
                                </label>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" form="re_request">Continue</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.swal-button').on('click', function () {
                var $this = $(this);
                Swal.fire({
                    title: $this.data('title'),
                    icon: $this.data('icon'),
                    input: $this.data('input') || null,
                    showCancelButton: true,
                    cancelButtonText: 'Cancel',
                    confirmButtonText: 'Confirm',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#rejection_message').val(result.value || '');
                        $($this.data('target')).submit();
                    }
                });
            });

            $('.btn-image').on('click', function () {
                var $this = $(this);
                Swal.fire({
                    html: $this.html(),
                    confirmButtonText: 'Close',
                    customClass: 'swal-wide'
                });
            });

            $('.other-text').on('focus', function () {
                $(this).closest('label').find('input[type=radio]').prop('checked', true);
            });
        });
    </script>
@endpush