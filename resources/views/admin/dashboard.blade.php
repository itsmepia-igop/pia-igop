@extends('layouts.backend')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12 order-2 order-lg-1">
                <div class="row">
                    <div class="col-xl-3 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-5 col-md-4">
                                        <div class="icon-big text-center icon-warning">
                                            <i class="nc-icon nc-app text-success"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-md-8">
                                        <div class="numbers">
                                            <p class="card-category">Order per Day</p>
                                            <p class="card-title" style = "font-size:24px">{{ $cards['orders'] }}<p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer ">
                                <hr>
                                <div class="stats">
                                    {{-- <i class="fa fa-calendar-o"></i> Last day --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-5 col-md-4">
                                        <div class="icon-big text-center icon-warning">
                                            <i class="nc-icon nc-money-coins text-success"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-md-8">
                                        <div class="numbers">
                                            <p class="card-category">Sales Today</p>
                                            <p class="card-title" style = "font-size:24px">₱ {{ number_format($cards['sales'], 2) }}<p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer ">
                                <hr>
                                <div class="stats">
                                    {{-- <i class="fa fa-calendar-o"></i> Last day --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-5 col-md-4">
                                        <div class="icon-big text-center icon-warning">
                                            <i class="nc-icon nc-calendar-60 text-success"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-md-8">
                                        <div class="numbers">
                                            <p class="card-category">Date</p>
                                            <p class="card-title" style = "font-size:22px">{{ now()->format('M d, Y') }}<p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer ">
                                <hr>
                                <div class="stats">
                                    {{-- <i class="fa fa-calendar-o"></i> Last day --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-5 col-md-4">
                                        <div class="icon-big text-center icon-warning">
                                            <i class="nc-icon nc-watch-time text-success"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-md-8">
                                        <div class="numbers">
                                            <p class="card-category">Time</p>
                                            <p class="card-title" style = "font-size:22px">{{ now()->format('h:i A') }}<p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer ">
                                <hr>
                                <div class="stats">
                                    {{-- <i class="fa fa-calendar-o"></i> Last day --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (!auth()->user()->is_staff)
                <div class="col-md-12 col-lg-8 order-3 order-lg-2">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-chart">
                                <div class="card-header">
                                    <div class="float-end sale-filter">
                                        <button class="btn btn-primary btn-weekly">Weekly</button>
                                        <button class="btn btn-secondary btn-monthly">Monthly</button>
                                        <button class="btn btn-secondary btn-yearly">Yearly</button>
                                    </div>
                                    <h5 class="card-title">Weekly Sales</h5>
                                    <p class="card-category">Sales this week</p>
                                </div>
                                <div class="card-body">
                                    <canvas id="sales" width="400" height="100"></canvas>
                                </div>
                                <div class="card-footer">
                                    <div class="chart-legend">
                                        <span><i class="fa fa-circle text-info"></i> This Week</span>
                                        <span><i class="fa fa-circle text-gray"></i> Last Week</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-4 order-1 order-lg-3">
                    <div class="row">
                        <div class="col-md-6 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Low Stock Products</h4>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        @forelse ($products as $product)
                                            <li class="list-group-item mb-0 d-flex align-items-center border-danger-subtle">
                                                {{ $product->name }} ({{ $product->quantity }})
                                            </li>
                                        @empty
                                            <li class="list-group-item mb-0 text-center">
                                                No low stock products
                                            </li>
                                        @endforelse
                                    </ul>
                                </div>
                                <div class="card-footer">
                                    <hr>
                                    <div class="text-center">
                                        <a href="{{ route('admin.products.list') }}" class="btn btn-primary">View all</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Low Stock Raw Materials</h4>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        @forelse ($materials as $material)
                                            <li class="list-group-item mb-0 d-flex align-items-center border-danger">
                                                {{ $material->name }} ({{ $material->quantity }})
                                            </li>
                                        @empty
                                            <li class="list-group-item mb-0 text-center">
                                                No low stock raw materials
                                            </li>
                                        @endforelse
                                    </ul>
                                </div>
                                <div class="card-footer">
                                    <hr>
                                    <div class="text-center">
                                        <a href="{{ route('admin.materials.list') }}" class="btn btn-primary">View all</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Pending Orders</h4>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        @forelse ($orders as $order)
                                            <li class="list-group-item mb-0 d-flex flex-wrap justify-content-between align-items-center border-warning">
                                                {{ $order->product->name }} - ({{ $order->status->name }}) 
                                                <span>
                                                    <small>{{ $order->updated_at->format('M d, Y') }}</small>
                                                    <a href="{{ route('admin.order.show', $order->id) }}" class="ms-2">View</a>
                                                </span>
                                            </li>
                                        @empty
                                            <li class="list-group-item mb-0 text-center">
                                                No pending orders
                                            </li>
                                        @endforelse
                                    </ul>
                                </div>
                                <div class="card-footer">
                                    <hr>
                                    <div class="text-center">
                                        <a href="{{ route('admin.orders.list', ['status' => 'pending']) }}" class="btn btn-primary">View all</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-md-12 order-1 order-lg-2">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Low Stock Products</h4>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        @forelse ($products as $product)
                                            <li class="list-group-item mb-0 d-flex align-items-center border-danger-subtle">
                                                {{ $product->name }} ({{ $product->quantity }})
                                            </li>
                                        @empty
                                            <li class="list-group-item mb-0 text-center">
                                                No low stock products
                                            </li>
                                        @endforelse
                                    </ul>
                                </div>
                                <div class="card-footer">
                                    <hr>
                                    <div class="text-center">
                                        <a href="{{ route('admin.products.list') }}" class="btn btn-primary">View all</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Low Stock Raw Materials</h4>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        @forelse ($materials as $material)
                                            <li class="list-group-item mb-0 d-flex align-items-center border-danger">
                                                {{ $material->name }} ({{ $material->quantity }})
                                            </li>
                                        @empty
                                            <li class="list-group-item mb-0 text-center">
                                                No low stock raw materials
                                            </li>
                                        @endforelse
                                    </ul>
                                </div>
                                <div class="card-footer">
                                    <hr>
                                    <div class="text-center">
                                        <a href="{{ route('admin.products.list') }}" class="btn btn-primary">View all</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Pending Orders</h4>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        @forelse ($orders as $order)
                                            <li class="list-group-item mb-0 d-flex flex-wrap justify-content-between align-items-center border-warning">
                                                {{ $order->product->name }} - ({{ $order->status->name }}) 
                                                <span>
                                                    <small>{{ $order->updated_at->format('M d, Y') }}</small>
                                                    <a href="{{ route('admin.order.show', $order->id) }}" class="ms-2">View</a>
                                                </span>
                                            </li>
                                        @empty
                                            <li class="list-group-item mb-0 text-center">
                                                No pending orders
                                            </li>
                                        @endforelse
                                    </ul>
                                </div>
                                <div class="card-footer">
                                    <hr>
                                    <div class="text-center">
                                        <a href="{{ route('admin.orders.list') }}" class="btn btn-primary">View all</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const chart = $('#sales');
            const sales = {{ Js::from($sales) }};
            
            var weekFirst = {
                data: Object.values(sales.this_week),
                fill: false,
                borderColor: '#51bcda',
                backgroundColor: 'transparent',
                pointBorderColor: '#51bcda',
                pointRadius: 4,
                pointHoverRadius: 4,
                pointBorderWidth: 8,
            };

            var weekSecond = {
                data: Object.values(sales.last_week),
                fill: false,
                borderColor: '#E3E3E3',
                backgroundColor: 'transparent',
                pointBorderColor: '#E3E3E3',
                pointRadius: 4,
                pointHoverRadius: 4,
                pointBorderWidth: 8
            };
            
            var dataWeek = {
                labels: Object.keys(sales.this_week),
                datasets: [weekFirst, weekSecond]
            };

            var monthFirst = {
                data: Object.values(sales.this_month),
                fill: false,
                borderColor: '#fbc658',
                backgroundColor: 'transparent',
                pointBorderColor: '#fbc658',
                pointRadius: 4,
                pointHoverRadius: 4,
                pointBorderWidth: 8,
            };

            var monthSecond = {
                data: Object.values(sales.last_month),
                fill: false,
                borderColor: '#E3E3E3',
                backgroundColor: 'transparent',
                pointBorderColor: '#E3E3E3',
                pointRadius: 4,
                pointHoverRadius: 4,
                pointBorderWidth: 8
            };

            var dataMonth = {
                labels: Object.keys(sales.this_month),
                datasets: [monthFirst, monthSecond]
            };

            var yearFirst = {
                data: Object.values(sales.this_year),
                fill: false,
                borderColor: '#51cbce',
                backgroundColor: 'transparent',
                pointBorderColor: '#51cbce',
                pointRadius: 4,
                pointHoverRadius: 4,
                pointBorderWidth: 8,
            };

            var yearSecond = {
                data: Object.values(sales.last_year),
                fill: false,
                borderColor: '#E3E3E3',
                backgroundColor: 'transparent',
                pointBorderColor: '#E3E3E3',
                pointRadius: 4,
                pointHoverRadius: 4,
                pointBorderWidth: 8
            };

            var dataYear = {
                labels: Object.keys(sales.this_year),
                datasets: [yearFirst, yearSecond]
            };

            var lineChart = new Chart(chart, {
                type: 'line',
                hover: false,
                data: dataWeek,
                options: {
                    legend: {
                        display: false,
                        position: 'top'
                    }
                }
            });

            $('.sale-filter').on('click', '.btn', function () {
                $('.sale-filter').find('.btn').removeClass('btn-primary').addClass('btn-secondary');
                $(this).addClass('btn-primary').removeClass('btn-secondary');
                var card = $(this).closest('.card');

                if ($(this).hasClass('btn-weekly')) {
                    card.find('.card-title').text('Weekly Sales');
                    card.find('.card-category').text('Sales this week');
                    card.find('.chart-legend span:first-child').remove();
                    card.find('.chart-legend').prepend('<span><i class="fa fa-circle text-info"></i> This Week</span>');
                    card.find('.chart-legend span:last-child').html('<i class="fa fa-circle text-gray"></i> Last Week');

                    lineChart.data = dataWeek;
                    lineChart.update();
                } else if ($(this).hasClass('btn-monthly')) {
                    card.find('.card-title').text('Monthly Sales');
                    card.find('.card-category').text('Sales this month');
                    card.find('.chart-legend span:first-child').remove();
                    card.find('.chart-legend').prepend('<span><i class="fa fa-circle text-warning"></i> This Month</span>');
                    card.find('.chart-legend span:last-child').html('<i class="fa fa-circle text-gray"></i> Last Month');

                    lineChart.data = dataMonth;
                    lineChart.update();
                } else {
                    card.find('.card-title').text('Yearly Sales');
                    card.find('.card-category').text('Sales this year');
                    card.find('.chart-legend span:first-child').remove();
                    card.find('.chart-legend').prepend('<span><i class="fa fa-circle text-primary"></i> This Year</span>');
                    card.find('.chart-legend span:last-child').html('<i class="fa fa-circle text-gray"></i> Last Year');

                    lineChart.data = dataYear;
                    lineChart.update();
                }
            });
        });
    </script>
@endpush