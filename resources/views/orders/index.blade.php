@extends('layouts.auth', [
    'class' => 'overflow-auto mh-100 h-100',
    'navbarClass' => 'bg-white py-1',
    'sectionClass' => 'bg-light'
])

@section('content')
    <div class="content mt-4">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-12">
                    <h3 class="fw-bold">Orders</h3>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-12 d-flex flex-wrap gap-md-2 justify-content-md-end justify-content-center btn-filter-group">
                    <button class="btn btn-primary my-0" data-filter="*">Show All</button>
                    @foreach ($statuses as $status)
                        @php
                            $slug = strtolower(str_replace(' ', '-', $status->name));
                        @endphp
                        <button class="btn btn-secondary my-0 position-relative" data-filter=".{{ $slug }}">
                            {!! !empty($orders_count[str_replace('-', '_', $slug)]) ? '<span class="badge badge-danger p-1 border position-absolute end-0 top-50 translate-middle-y">'.$orders_count[str_replace('-', '_', $slug)].'</span>' : '' !!}
                            {{ $status->name }}
                        </button>
                    @endforeach
                </div>
            </div>
            <div class="row order-list">
                @foreach ($orders as $order)
                    <div class="col-lg-4 col-md-6 order-item {{ strtolower(str_replace(' ', '-', $order->status)) }}">
                        <a href="{{ route('user.order.show', $order->id) }}" class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">
                                    {{ $order->product_name }} <span class="fw-bold fs-6">({{ $order->status }})</span>
                                </h4>
                                <hr>
                            </div>
                            <div class="card-body">
                                <div class="image rounded">
                                    <img src="{{ Storage::url($order->file_path) }}" alt="{{ $order->product_name }}" class="w-100" style="height: 200px; object-fit: cover; transition: all 0.5s ease;">
                                </div>
                                <p class="my-3" 
                                    style="display: -webkit-box;
                                           max-width: 100%;
                                           -webkit-line-clamp: 3;
                                           -webkit-box-orient: vertical;
                                           overflow: hidden;
                                        ">{{ $order->product_description }}</p>
                                <hr>
                                <div class="text-center mb-2">
                                    <small class="text-primary fw-bold">View Details</small>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('plugins/isotope-3.0.6/isotope.pkgd.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            var $list = $('.order-list').isotope({
                // options
                itemSelector: '.order-item',
                masonry: true
            });

            $('.btn-filter-group').on('click', '.btn', function () {
                var filterValue = $(this).attr('data-filter');
                $list.isotope({ filter: filterValue });

                $('.btn-filter-group').find('.btn').removeClass('btn-primary').addClass('btn-secondary');
                $(this).removeClass('btn-secondary').addClass('btn-primary');
            });
        });
    </script>
@endpush