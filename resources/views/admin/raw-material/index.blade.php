@extends('layouts.backend')

@push('css')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive-bs5/css/responsive.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
@endpush

@section('content')
    <div class="content">
        @include('layouts.alert')
        @if (count($errors))
            <div class="alert alert-danger alert-dismissible mb-4" role="alert">
                <p class="mb-0">Something went wrong in adding the raw material. Click <a href="#" data-bs-toggle="modal" data-bs-target="#addMaterialModal">here</a> to view.</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Raw Materials</h4>
                        <div>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMaterialModal">Add Material</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table" id="raw-material-table">
                            <thead class="text-primary">
                                <tr>
                                    {{-- <th>Batch Number</th> --}}
                                    <th>Material Type</th>
                                    <th>Quantity</th>
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

    @include('admin.raw-material.modal')
@endsection

@push('scripts')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            var table = $('#raw-material-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: window.location.href,
                columns: [
                    // {data: 'batch_number', name: 'batch_number', orderable: false, searchable: false},
                    {data: 'name', name: 'name', orderable: true, searchable: false},
                    {data: 'quantity', name: 'quantity', orderable: true, searchable: false},
                    {data: 'updated_at', name: 'updated_at', orderable: false, searchable: false},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false, width: 190}
                ],
                order: [[ 2, "desc" ]],
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

            // table.on('click', '.btn-edit', function (e) {
            //     let data = table.row(e.target.closest('tr')).data();
                
            //     var modal = $('#editMaterialModal');
            //         modal.find('#editMaterial').attr('action', data.edit_route);
            //         modal.find('#name').val(data.name);
            //         modal.find('#batch_number').val(data.batch_number);
            //         modal.find('#quantity').val(data.quantity);
            //         modal.modal('show');
            // });

            $('body').on('click', '.btn-remove', function (e) {
                e.preventDefault();
                var $this = $(this);
                Swal.fire({
                    title: '',
                    text: 'Are you sure you want to remove this raw material?',
                    showCancelButton: true,
                    cancelButtonText: 'Cancel',
                    confirmButtonText: 'Confirm',
                }).then((result) => {
                    if (result.value) {
                        $($this.data('target')).submit();
                    }
                });
            });

            $('#editMaterialModal, #addMaterialModal').on('hidden.bs.modal', function () {
                $(this).find('.form-control').val('');
            });
        });
    </script>
@endpush