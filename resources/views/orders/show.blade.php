@extends('layouts.auth', [
    'class' => 'overflow-auto mh-100 h-100',
    'navbarClass' => 'bg-white py-1',
    'sectionClass' => 'bg-light'
])

@push('css')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
@endpush

@section('content')
    <div class="content mt-4">
        <div class="container">
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
                            <div class="text-end">
                                @if ($order->status->name == 'Pending')
                                    <button type="button" class="btn btn-danger me-3" data-bs-toggle="modal" data-bs-target="#cancelModal">Cancel Order</button>
                                    {{-- <button type="button" class="btn btn-danger me-3 swal-button" data-title="Cancel Order?" data-icon="warning" data-target="#cancel">Cancel Order</button>
                                    <form action="{{ route('user.order.cancel', $order->id) }}" id="cancel" method="POST" class="d-none">
                                        @csrf
                                    </form> --}}
                                @elseif ($order->status->name == 'To Pay')
                                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadPayment">Upload Payment</a>
                                    <button type="button" class="btn btn-danger me-3" data-bs-toggle="modal" data-bs-target="#cancelModal">Cancel Order</button>
                                    {{-- <button type="button" class="btn btn-danger me-3 swal-button" data-title="Cancel Order?" data-icon="warning" data-target="#cancel">Cancel Order</button>
                                    <form action="{{ route('user.order.cancel', $order->id) }}" id="cancel" method="POST" class="d-none">
                                        @csrf
                                    </form> --}}
                                @elseif ($order->status->name == 'To Deliver')
                                    <button type="button" class="btn btn-primary me-3 swal-button" data-title="Receive Item?" data-icon="info" data-target="#receive">Receive Item</button>
                                    <form action="{{ route('user.order.receive', $order->id) }}" id="receive" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                    @if (!empty($order->estimate_delivery))
                                        <div class="">
                                            <small class="fs-6"><span class="fw-bold">ETA</span> <span class="text-decoration-underline">{{ $order->estimate_delivery->format('M d, Y') }} - {{ $order->estimate_delivery->addDays(3)->format('M d, Y') }}</span></small>
                                        </div>
                                    @endif
                                @elseif ($order->status->name == 'Completed' && !in_array(auth()->user()->id, $order->product->feedbacks->pluck('user_id')->toArray()))
                                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFeedback">Rate</a>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if (!empty($order->re_request_reason))
                                    <div class="col-md-12">
                                        <h6>Re-Requesting of Payment Reason</h6>
                                        <p class="mb-0">{{ $order->re_request_reason ?? '' }}</p>
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
                                    <p>₱{{ number_format($order->product->price, 2) }}</p>
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
                                <div class="col-md-6">
                                    <h6>Size</h6>
                                    <p>{{ $order->size ?? '' }}</p>
                                </div>
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
                        </div>
                        <div class="card-footer">
                            <hr>
                            <div class="d-flex gap-3 align-items-center justify-content-end">
                                <h5 class="mb-0"><span class="fw-bold">Total: </span>₱<span class="total">{{ number_format($order->total, 2) }}</span></h5>
                            </div>
                        </div>
                    </div>

                    @if ($feedback = $order->product->feedbacks->where('user_id', auth()->user()->id)->first())
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><li class="nc-icon nc-chat-33 me-3"></li><span class="fw-bold">Your Feedback</span></h4>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-sm-2">
                                        @if (!empty($feedback->file))
                                            <img src="{{ Storage::url($feedback->file->path) }}" alt="{{ $feedback->file->file_name }}" class="w-100"  style="object-fit: cover;">
                                        @endif
                                    </div>
                                    <div class="col-sm-10">
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <h6 class="mb-0">User</h6>
                                            <p class="mb-0">{{ $feedback->user->profile->name .' '. $feedback->user->profile->surname }}</p>
                                        </div>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <h6 class="mb-0">Rate</h6>
                                            <p class="mb-0">{{ $feedback->rate }}/10</p>
                                        </div>
                                        <h6>Message</h6>
                                        <p class="mb-0">{{ $feedback->message ?? '' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @foreach ($products as $product)
                <h4 class="fw-bold">Products you might like</h4>
                <div class="row product-list">
                    <div class="col-lg-4 col-md-6 product-item">
                        <a href="{{ route('product.show', $product->id) }}" class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">{{ $product->name }}</h4>
                                <span class="fw-bold">₱ {{ number_format($product->price, 2) }}</span>
                                <hr>
                            </div>
                            <div class="card-body">
                                <div class="image rounded">
                                    <img src="{{ Storage::url($product->file->path) }}" alt="{{ $product->file->file_name }}" class="w-100" style="height: 200px; object-fit: cover; transition: all 0.5s ease;">
                                </div>
                                <p class="mb-0 mt-3" 
                                    style="display: -webkit-box;
                                           max-width: 100%;
                                           -webkit-line-clamp: 3;
                                           -webkit-box-orient: vertical;
                                           overflow: hidden;
                                        ">{{ $product->description }}</p>
                                <hr>
                                <div class="text-center mb-2">
                                    <small class="text-primary fw-bold">View Details</small>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @if ($order->status->name == 'To Pay')
        <div class="modal fade" id="uploadPayment" tabindex="-1" aria-labelledby="uploadPaymentLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mb-0" id="uploadPaymentLabel">
                            <span class="fw-bold">Total: </span>₱<span class="total">{{ number_format($order->total, 2) }}</span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('user.order.upload.payment', $order->id) }}" method="POST" id="paymentForm" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="design-form mb-3">
                                        <div class="">
                                            @if (!empty($order->payment))
                                                <h4>Previous Payment</h4>
                                                <img src="{{ Storage::url($order->payment_file->path) }}" alt="{{ $order->payment_reference ?? '' }}" style="object-fit: cover;">
                                            @endif
                                        </div>
                                        <label for="payment">Payment</label>
                                        <input type="file" class="form-control" id="payment" name="payment" placeholder="Payment" accept="image/*" {{ !empty($order->payment) ?: 'required' }} />
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="payment_reference">Reference No.</label>
                                        <input type="text" class="form-control" id="payment_reference" name="payment_reference" placeholder="Enter Reference no." value="{{ old('payment_reference', $order->payment_reference ?? '') }}" required />
                                        @if ($errors->has('payment_reference'))
                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                <strong>{{ $errors->first('payment_reference') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="payment_type">Payment Type (Sent From)</label>
                                        <select name="payment_type" id="payment_type" class="form-control js-select2" style="width: 100%;" data-tags="true" data-dropdown-parent="#uploadPayment" required>
                                            <option value="">Select a Payment Type</option>
                                            <option value="G-Cash">G-Cash</option>
                                            <option value="BDO">BDO Unibank, Inc.</option>
                                            <option value="Metrobank">Metropolitan Bank and Trust Company (Metrobank)</option>
                                            <option value="BPI">Bank of the Philippine Islands (BPI)</option>
                                            <option value="PNB">Philippine National Bank (PNB)</option>
                                            <option value="Landbank">Land Bank of the Philippines (Landbank)</option>
                                            <option value="SecurityBank">Security Bank Corporation</option>
                                            <option value="Chinabank">China Banking Corporation (Chinabank)</option>
                                            <option value="UnionBank">Union Bank of the Philippines (UnionBank)</option>
                                            <option value="DBP">Development Bank of the Philippines (DBP)</option>
                                            <option value="RCBC">Rizal Commercial Banking Corporation (RCBC)</option>
                                            <option value="PSBank">Philippine Savings Bank (PSBank)</option>
                                            <option value="ChinaBankSavings">China Bank Savings</option>
                                            <option value="CitySavingsBank">City Savings Bank</option>
                                            <option value="Maybank">Maybank Philippines, Inc.</option>
                                            <option value="EastWestBank">EastWest Bank</option>
                                            <option value="BDONetworkBank">BDO Network Bank</option>
                                            <option value="FICO">First Isabela Cooperative Bank (FICO Bank)</option>
                                            <option value="CebuanaLhuillierBank">Cebuana Lhuillier Rural Bank, Inc.</option>
                                            <option value="RuralBankAngeles">Rural Bank of Angeles, Inc.</option>
                                            <option value="RuralBankSanLeonardo">Rural Bank of San Leonardo (N.E.) Inc.</option>
                                            <option value="Maya">Maya</option>
                                            <option value="UnionDigitalBank">UnionDigital Bank</option>
                                            <option value="GoTymeBank">GoTyme Bank</option>
                                            <option value="Tonik">Tonik</option>
                                            <option value="UNO">UNO Digital Bank</option>
                                            <option value="OFBank">OFBank</option>
                                            {{-- @if (!empty($options))
                                                @foreach ($options as $option)
                                                    <option value="{{ $option->bank }}" {{ old('payment_type', $order->payment_type ?? '') == $option->bank ? 'selected' : '' }}>{{ $option->bank }}</option>
                                                @endforeach
                                            @endif --}}
                                        </select>
                                        @if ($errors->has('payment_type'))
                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                <strong>{{ $errors->first('payment_type') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <hr>
                                    <p><i class="fa fa-info-circle"></i> <strong>IMPORTANT:</strong> Please ensure you send the exact amount required. We cannot process refunds for incorrect payments. Double-check your payment before completing the transaction. Thank you for your understanding!</p>
                                </div>
                                <div class="col-lg-6">
                                        <div class="type_details">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">
                                                    G-Cash
                                                </h4>
                                            </div>
                                            <div class="card-body text-center">
                                                <div class="qr-display">
                                                    @if (!empty($option->qr))
                                                        <img src="{{ Storage::url($option->qr) }}" alt="Payment QR Code" style="width: 450px; height: 400px; object-fit: cover;">
                                                    @endif
                                                </div>
                                                <h5 class="mt-3"><b>Account/Phone Number</b>: {{ $option->number ?? '' }}</h5>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <p class="text-center"><i>Click <a href="{{ route('user.payment.options') }}" target="_blank">here</a> to view payment options.</i></p> --}}
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary btn-upload-payment" data-target="#paymentForm">Upload Payment</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($order->status->name == 'Completed' && !in_array(auth()->user()->id, $order->product->feedbacks->pluck('user_id')->toArray()))
        <div class="modal fade" id="addFeedback" tabindex="-1" aria-labelledby="addFeedbackLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mb-0" id="addFeedbackLabel">
                            <span class="fw-bold">Feedback</span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('user.order.feedback', $order->id) }}" method="POST" id="feedbackForm" enctype="multipart/form-data">
                            @csrf

                            <div class="rating-container mb-3">
                                <div class="stars">
                                    <span class="star" data-value="1"></span>
                                    <span class="star" data-value="2"></span>
                                    <span class="star" data-value="3"></span>
                                    <span class="star" data-value="4"></span>
                                    <span class="star" data-value="5"></span>
                                </div>
                                <p>Rating: <span id="ratingValue">0</span> / 10</p>
                            </div>
                            
                            <div class="mb-3">
                                <label for="img">Image</label>
                                <input type="file" class="form-control" id="img" name="img" placeholder="Image" accept="image/*" required />
                            </div>
                            <input type="hidden" name="rate" id="rating-value" value="0">
                            <div class="mb-3">
                                <label for="message">Message</label>
                                <textarea class="form-control px-2" style="height: 250px; max-height: 100%;" id="message" name="message" placeholder="Message" required></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" form="feedbackForm">Add Feedback</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    @if (in_array($order->status->name, ["Pending", "To Pay"]))
        <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title mb-0" id="cancelLabel">Cancel Order</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('user.order.cancel', $order->id) }}" method="post" id="cancel">
                            @csrf
                            <p>Select a reason for canceling your order.</p>
                            <div class="form-check mb-2">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" value="Payment Issues" id="cancel_reason" name="cancel_reason" />
                                    Payment Issues
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" value="Order Placed by Mistake" id="cancel_reason" name="cancel_reason" />
                                    Order Placed by Mistake
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" value="Change of Mind" id="cancel_reason" name="cancel_reason" />
                                    Change of Mind
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" value="Product Details Discrepancy" id="cancel_reason" name="cancel_reason" />
                                    Product Details Discrepancy
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" value="Privacy Concerns" id="cancel_reason" name="cancel_reason" />
                                    Privacy Concerns
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" value="Address or Contact Errors" id="cancel_reason" name="cancel_reason" />
                                    Address or Contact Errors
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" value="Price or Promotion Issue" id="cancel_reason" name="cancel_reason" />
                                    Price or Promotion Issue
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" value="Other" id="cancel_reason" name="cancel_reason" />
                                    Other (please specify)
                                    <input type="text" class="form-control other-text" name="specific_reason">
                                </label>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" form="cancel">Continue</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('plugins/isotope-3.0.6/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/select2.min.js') }}"></script>
    <script>
        const options = {{ Js::from($options ?? []) }};
        $(document).ready(function () {
            $('.js-select2').each(function () {
                $(this).select2({
                    dropdownParent: $(this).data('dropdown-parent') ? $($(this).data('dropdown-parent')) : ''
                });
            })

            var $list = $('.product-list').isotope({
                // options
                itemSelector: '.product-item',
                masonry: true
            });

            $('.swal-button').on('click', function () {
                var $this = $(this);
                Swal.fire({
                    title: $this.data('title'),
                    icon: $this.data('icon'),
                    showCancelButton: true,
                    cancelButtonText: 'Cancel',
                    confirmButtonText: 'Confirm',
                }).then((result) => {
                    if (result.value) {
                        $($this.data('target')).submit();
                    }
                });
            });
            
            let savedRating = 0;

            $(".star").on("mousemove", function (e) {
                const starIndex = parseInt($(this).data("value"));
                const isHalf = (e.pageX - $(this).offset().left) < ($(this).width() / 2);
                const hoverRating = isHalf ? starIndex - 0.5 : starIndex;

                // Call the function to highlight stars based on hover rating
                highlightStars(hoverRating);
            });

            $(".star").on("mouseleave", function () {
                // Revert to saved rating on mouse leave
                highlightStars(savedRating);
            });

            $(".star").on("click", function (e) {
                const starIndex = parseInt($(this).data("value"));
                const isHalf = (e.pageX - $(this).offset().left) < ($(this).width() / 2);
                savedRating = isHalf ? starIndex - 0.5 : starIndex;

                // Display saved rating and call the highlight function
                $("#ratingValue").text(savedRating * 2);
                $("#rating-value").val(savedRating * 2);
                highlightStars(savedRating);
            });

            function highlightStars(rating) {
                // Remove previous highlights
                $(".star").removeClass("active half-active");
                
                // Add new highlights based on the current rating
                $(".star").each(function (index) {
                    const starValue = index + 1;
                    if (starValue <= rating) {
                        $(this).addClass("active"); // Full star
                    } else if (starValue - 0.5 === rating) {
                        $(this).addClass("half-active"); // Half star
                    }
                });
            }

            $('.other-text').on('focus', function () {
                $(this).closest('label').find('input[type=radio]').prop('checked', true);
            });

            $('#payment_type').on('change', function () {
                if (options.length) {
                    console.log('test');
                    $('.type_details').html('');

                    var value = $(this).val();
                    if (value !== '') {
                        var option = options.find(item => { return item.bank == value });

                        $('.type_details').html(`
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        ${option.bank}
                                    </h4>
                                </div>
                                <div class="card-body text-center">
                                    <div class="qr-display">
                                        ${option.qr ? `<img src="/storage/${option.qr}" alt="Payment QR Code" style="width: 450px; height: 400px; object-fit: cover;">` : ''}
                                    </div>
                                    <h5 class="mt-3"><b>Account/Phone Number</b>: ${option.number}</h5>
                                </div>
                            </div>
                        `);
                    }
                }
            });
            

            $('.btn-upload-payment').on('click', function () {
                var $this = $(this);

                if (!$($this.data('target'))[0].checkValidity())
                {
                    console.log($($this.data('target'))[0].reportValidity());
                    return false;
                }

                const imageLoc = null;
                const file = $('#payment')[0].files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imageLoc = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }

                Swal.fire({
                    html: `
                            <p>Continue to upload the payment?</p>
                            ${ imageLoc ? `<img src="${ imageLoc }" style="height: 100px; object-fit: contain; margin: auto;" />` : '' }
                            <p><b>Reference</b>: ${ $('#payment_reference').val() }</p>
                            <p class="mb-0"><b>Payment Type</b>: ${ $('#payment_type').val() }</p>
                        `,
                    icon: $this.data('icon'),
                    showCancelButton: true,
                    cancelButtonText: 'Cancel',
                    confirmButtonText: 'Confirm',
                }).then((result) => {
                    if (result.value) {
                        $($this.data('target')).submit();
                    }
                });
            });
        });
    </script>
@endpush