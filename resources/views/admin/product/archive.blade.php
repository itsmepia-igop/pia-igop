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
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Archived Products</h4>
                    </div>
                    <div class="card-body">
                        <table class="table" id="archive-product-table">
                            <thead class="text-primary">
                                <tr>
                                    <th>Design</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Customize</th>
                                    <th>Price</th>
                                    <th>Last Update</th>
                                    <th style="width: 190px">Actions</th>
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
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    @include('components.repeater-js')

    <script>
        $(document).ready(function () {
            var table = $('#archive-product-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: window.location.href,
                columns: [
                    {data: 'design', name: 'design', orderable: false, searchable: false},
                    {data: 'name', name: 'name', orderable: true, searchable: false},
                    {data: 'quantity', name: 'quantity', orderable: true, searchable: false},
                    {data: 'customize', name: 'customize', orderable: false, searchable: false},
                    {data: 'price', name: 'price', orderable: true, searchable: false},
                    {data: 'updated_at', name: 'updated_at', orderable: true, searchable: false},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false, width: 300}
                ],
                order: [[ 5, "desc" ]],
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

            table.on('click', '.btn-restore', function (e) {
                e.preventDefault();
                var $this = $(this);
                Swal.fire({
                    title: '',
                    icon: 'info',
                    html: 'Are you sure you want to <b>restore</b> this product?',
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