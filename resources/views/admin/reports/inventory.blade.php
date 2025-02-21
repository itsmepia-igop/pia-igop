@extends('layouts.backend')

@push('css')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive-bs5/css/responsive.bootstrap5.min.css') }}">
@endpush

@section('content')
    <div class="content">
        @include('layouts.alert')
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('admin.reports.inventory.export') }}" target="_blank" class="text-end">
                    <input type="hidden" name="product_keyword" id="product_keyword">
                    <input type="hidden" name="material_keyword" id="material_keyword">
                    <button class="btn btn-primary">Export Report</button>
                </form>
            </div>
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Generic Product Inventory</h4>
                    </div>
                    <div class="card-body">
                        <table class="table" id="product-table" data-route="{{ route('admin.reports.product.inventory') }}">
                            <thead class="text-primary">
                                <tr>
                                    <th>Product Name</th>
                                    <th>Description</th>
                                    <th>Price per Item</th>
                                    <th>Stock Quantity</th>
                                    <th>Inventory Value</th>
                                    <th>Last Deducted Date</th>
                                    <th>Last Restock Date</th>
                                    <th>Product Discontinued?</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Raw Materials Inventory</h4>
                    </div>
                    <div class="card-body">
                        <table class="table" id="material-table" data-route="{{ route('admin.reports.material.inventory') }}">
                            <thead class="text-primary">
                                <tr>
                                    <th>Raw Material Name</th>
                                    <th>Stock Quantity</th>
                                    <th>Last Deducted Date</th>
                                    <th>Last Restock Date</th>
                                </tr>
                            </thead>
                        </table>
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

    <script>
        $(document).ready(function () {
            var table = $('#product-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: $('#product-table').data('route'),
                columns: [
                    {data: 'name', name: 'name', orderable: true, searchable: false},
                    {data: 'description', name: 'description', orderable: true, searchable: false},
                    {data: 'price', name: 'price', orderable: true, searchable: false},
                    {data: 'quantity', name: 'quantity', orderable: true, searchable: false},
                    {data: 'value', name: 'value', orderable: true, searchable: false},
                    {data: 'last_deducted', name: 'last_deducted', orderable: true, searchable: false},
                    {data: 'last_restock', name: 'last_restock', orderable: true, searchable: false},
                    {data: 'is_deleted', name: 'is_deleted', orderable: true, searchable: false},
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
                    $('.dataTables_filter input', $('#product-table')).unbind();
                    $('.dataTables_filter input', $('#product-table')).bind('keyup', function(e) {
                        if(e.keyCode == 13) {
                            $('#product_keyword').value(this.value);
                            table.search(this.value).draw();
                        }
                    });
                },
            });

            var table2 = $('#material-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: $('#material-table').data('route'),
                columns: [
                    {data: 'name', name: 'name', orderable: true, searchable: false},
                    {data: 'quantity', name: 'quantity', orderable: true, searchable: false},
                    {data: 'last_deducted', name: 'last_deducted', orderable: true, searchable: false},
                    {data: 'last_restock', name: 'last_restock', orderable: true, searchable: false},
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
                    $('.dataTables_filter input', $('#material-table')).unbind();
                    $('.dataTables_filter input', $('#material-table')).bind('keyup', function(e) {
                        if(e.keyCode == 13) {
                            $('#material_keyword').value(this.value);
                            table2.search(this.value).draw();
                        }
                    });
                },
            });
        });
    </script>
@endpush