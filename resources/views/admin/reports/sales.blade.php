@extends('layouts.backend')

@push('css')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive-bs5/css/responsive.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/jquery-ui/jquery-ui.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush

@section('content')
    <div class="content">
        @include('layouts.alert')
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Sales</h4>
                        
                        <div class="d-flex gap-2 align-items-center">
                            <div class="d-flex gap-2">
                                <input type="text" id="date" name="date" class="form-control" placeholder="Date" value="" autocomplete="off" form="exportSales">
                                <select id="sales_type" name="sales_type" class="form-select" form="exportSales">
                                    <option value="">Select an Option</option>
                                    <option value="generic">Generic Product</option>
                                    <option value="customized">Customized</option>
                                </select>
                                {{-- <input type="text" id="start" class="form-control" placeholder="Start Date">
                                <input type="text" id="end" class="form-control" placeholder="End Date"> --}}
                            </div>
                            <form action="{{ route('admin.reports.sales.export') }}" target="_blank" id="exportSales">
                                <input type="hidden" name="keyword" id="keyword">
                                <button type="submit" class="btn btn-primary">Export</button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table" id="report-table">
                            <thead class="text-primary">
                                <tr>
                                    <th>Product Name</th>
                                    <th>Thickness</th>
                                    <th>Size</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Completed At</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card card-chart">
                    <div class="card-header">
                        <h5 class="card-title">Sales Graph</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="sales" width="400" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
    {{-- <script src="{{ asset('plugins/jquery-ui/jquery-ui.js') }}"></script> --}}

    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/npm/daterangepickr.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            var table = $('#report-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: window.location.href,
                    data: function (d) {
                        return $.extend({}, d, {
                            date: $('#date').val(),
                            sales_type: $('#sales_type').val()
                        });
                    }
                },
                columns: [
                    {data: 'name', name: 'name', orderable: true, searchable: false},
                    {data: 'thickness', name: 'thickness', orderable: false, searchable: false},
                    {data: 'size', name: 'size', orderable: false, searchable: false},
                    {data: 'price', name: 'price', orderable: true, searchable: false},
                    {data: 'quantity', name: 'quantity', orderable: true, searchable: false},
                    {data: 'total', name: 'total', orderable: false, searchable: false},
                    {data: 'date_complete', name: 'date_complete', orderable: false, searchable: false},
                ],
                order: [[ 0, "asc" ]],
                orderCellsTop: true,
                scrollY: 700,
                scrollX: true,
                scrollCollapse: true,
                autoWidth: false,
                language: {
                infoFiltered: ""
                },
                initComplete: function() {
                    $('.dataTables_filter input').unbind();
                    $('.dataTables_filter input').bind('keyup', function(e) {
                        if(e.keyCode == 13) {
                            $('#keyword').val(this.value);
                            table.search(this.value).draw();
                        }
                    });
                },
            });

            $('#sales_type').on('change', function () {
                table.draw();
            });

            $('#date').daterangepicker({
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                linkedCalendars: false,
                opens: "center",
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            $('#date').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                table.draw();
            });

            $('#date').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                table.draw();
            });

            const chart = $('#sales');
            var lineChart = new Chart(chart, {
                type: 'line',
                hover: false,
                data: {
                    labels: [],
                    datasets: []
                },
                options: {
                    legend: {
                        display: false,
                        position: 'top'
                    }
                }
            });

            table.on('draw', function () {
                var data = table.rows().data().toArray();
                var graph = [];
                $.each(data, function (key, value) {
                    if (graph[value.date_complete]) {
                        graph[value.date_complete] += parseFloat(value.total.replace('₱ ', '').replace(',', '').trim());
                    } else {
                        graph[value.date_complete] = parseFloat(value.total.replace('₱ ', '').replace(',', '').trim());
                    }
                });

                lineChart.data = {
                        labels: Object.keys(graph),
                        datasets: [{
                            data: Object.values(graph),
                            fill: false,
                            borderColor: '#51bcda',
                            backgroundColor: 'transparent',
                            pointBorderColor: '#51bcda',
                            pointRadius: 4,
                            pointHoverRadius: 4,
                            pointBorderWidth: 8,
                        }]
                    };
                    console.log(lineChart.data);
                lineChart.update();
            });
        });
    </script>
@endpush