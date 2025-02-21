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
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Monthly Report Sales - ₱ 5,321</h4>
                        <div>
                            <a href="#" class="btn btn-primary">Export</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table" id="report-table">
                            <thead class="text-primary">
                                <tr>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Total Price</th>
                                    <th>Thickness</th>
                                    <th>Size</th>
                                    <th>User</th>
                                    <th>Ordered At</th>
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
            var table = $('#report-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: window.location.href,
                columns: [
                    {data: 'product_name', name: 'product_name', orderable: true, searchable: false},
                    {data: 'quantity', name: 'quantity', orderable: true, searchable: false},
                    {data: 'price', name: 'price', orderable: true, searchable: false},
                    {data: 'thickness', name: 'thickness', orderable: false, searchable: false},
                    {data: 'size', name: 'size', orderable: false, searchable: false},
                    {data: 'user', name: 'user', orderable: false, searchable: false},
                    {data: 'created_at', name: 'created_at', orderable: false, searchable: false},
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
                        table.search(this.value).draw();
                    }
                });
                },
            });
        });
    </script>
@endpush