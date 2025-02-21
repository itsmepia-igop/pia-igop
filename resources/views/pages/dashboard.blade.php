@extends('layouts.auth', [
    'class' => 'overflow-auto mh-100 h-100',
    'navbarClass' => 'bg-white py-1',
    'sectionClass' => 'bg-light'
])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12 d-flex align-items-center justify-content-center bg-primary" style="height: 200px">
                    <div class="text-center">
                        <h3 class="text-white mb-2">Innovaplas Packaging Corporation</h3>
                        <p class="text-white">Your Happiness is our success</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-3 d-flex align-items-center">
                    <input type="text" class="form-control" id="search-product" placeholder="Search Product">
                </div>
                <div class="col-md-9 d-flex flex-wrap gap-md-3 justify-content-md-end justify-content-center btn-filter-group">
                    <button class="btn btn-primary" data-filter="*">Show All</button>
                    <button class="btn btn-secondary" data-filter=".customisable">Customizable</button>
                    <button class="btn btn-secondary" data-filter=".generic-product">Generic Product</button>
                </div>
            </div>
            <div class="row product-list">
                @foreach ($products as $product)
                    <div class="col-lg-4 col-md-6 product-item {{ $product->is_customize ? "customisable" : "generic-product" }}">
                        <a href="{{ route('product.show', $product->id) }}" class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0 product-title">{{ $product->name }}</h4>
                                <span class="fw-bold">₱ {{ number_format($product->price, 2) }}</span>
                                <hr>
                            </div>
                            <div class="card-body">
                                <div class="image rounded">
                                    <img src="{{ Storage::url($product->file->path) }}" alt="{{ $product->file->file_name }}" class="w-100" style="height: 200px; object-fit: cover; transition: all 0.5s ease;">
                                </div>
                                <p class="mb-0 mt-3 product-description"
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
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('plugins/isotope-3.0.6/isotope.pkgd.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            demo.checkFullPageBackgroundImage();

            var filters = {};
            var $list = $('.product-list').isotope({
                // options
                itemSelector: '.product-item',
                masonry: true,
                filter: function () {
                    var isButtonMatch = filters.button ? $(this).is(filters.button) : true;
                    var isSearchMatch = filters.search ? filters.search($(this)) : true;
                    return isButtonMatch && isSearchMatch;
                }
            });

            $('.btn-filter-group').on('click', '.btn', function () {
                filters.button = $(this).attr('data-filter') !== '*' ? $(this).attr('data-filter') : null;

                $list.isotope(); // Trigger filtering

                // Update button active class
                $('.btn-filter-group').find('.btn').removeClass('btn-primary').addClass('btn-secondary');
                $(this).removeClass('btn-secondary').addClass('btn-primary');
            });

            // Search Filter
            $('#search-product').on('input', function () {
                var keyword = $(this).val().toLowerCase();

                filters.search = keyword
                    ? function (item) {
                        var title = item.find('.product-title').text().toLowerCase();
                        var description = item.find('.product-description').text().toLowerCase();
                        return title.includes(keyword) || description.includes(keyword);
                    }
                    : null;

                $list.isotope(); // Trigger filtering
            });
            // ctx = document.getElementById('chartOrders').getContext("2d");

            // myChart = new Chart(ctx, {
            //     type: 'pie',
            //     data: {
            //         labels: ['Pending', 'Accepted', 'Rejected', 'Completed'],
            //         datasets: [{
            //             label: "Orders",
            //             pointRadius: 0,
            //             pointHoverRadius: 0,
            //             backgroundColor: [
            //                 '#E3E3E3',
            //                 '#6bd098',
            //                 '#ef8157',
            //                 '#51cbce'
            //             ],
            //             borderWidth: 0,
            //             data: [342, 480, 530, 120]
            //         }]
            //     },

            //     options: {

            //         legend: {
            //             display: false
            //         },

            //         pieceLabel: {
            //             render: 'percentage',
            //             fontColor: ['white'],
            //             precision: 2
            //         },

            //         tooltips: {
            //             enabled: true
            //         },

            //         scales: {
            //             yAxes: [{

            //                 ticks: {
            //                     display: false
            //                 },
            //                 gridLines: {
            //                     drawBorder: false,
            //                     zeroLineColor: "transparent",
            //                     color: 'rgba(255,255,255,0.05)'
            //                 }

            //             }],

            //             xAxes: [{
            //                 barPercentage: 1.6,
            //                 gridLines: {
            //                     drawBorder: false,
            //                     color: 'rgba(255,255,255,0.1)',
            //                     zeroLineColor: "transparent"
            //                 },
            //                 ticks: {
            //                         display: false,
            //                 }
            //         }]
            //         },
            //     }
            // });
            // demo.initChartsPages();
        });
    </script>
@endpush