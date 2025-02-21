@extends('layouts.auth', [
    'class' => 'overflow-auto mh-100 h-100',
    'navbarClass' => 'bg-white py-1',
    'sectionClass' => 'bg-light'
])  

@push('css')

@endpush

@section('content')
    <div class="content mt-4">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"><li class="nc-icon nc-basket me-3"></li><span class="fw-bold">{{ $product->name }}</span></h4>
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
                                        <h6>Quantity</h6>
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
                            @if ($product->quantity >= config('app.threshold'))
                                <div class="d-flex gap-3 align-items-center justify-content-end">
                                    <a href="{{ route('user.product.checkout', $product->id) }}" class="btn btn-primary">Buy Now</a>
                                </div>
                            @else
                                <div class="text-center">
                                    <h6><i>No stock available</i></h6>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"><li class="nc-icon nc-chat-33 me-3"></li><span class="fw-bold">Feedbacks</span></h4>
                        </div>
                        <div class="card-body">
                            @forelse ($feedbacks as $feedback)
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

                                @if (!$loop->last)
                                    <hr>
                                @endif
                            @empty
                                <p>No feedbacks yet.</p>
                            @endforelse
                        </div>
                        <div class="card-footer d-flex justify-content-center">
                            {{ $feedbacks->links() }}
                        </div>
                    </div>
                </div>
            </div>

            @if (count($products))
                <h4 class="fw-bold">Related Products</h4>
                <div class="row product-list">
                    @foreach ($products as $product)
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
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('plugins/isotope-3.0.6/isotope.pkgd.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            var $list = $('.product-list').isotope({
                // options
                itemSelector: '.product-item',
                masonry: true
            });
        });
    </script>
@endpush