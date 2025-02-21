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
                        <h4 class="card-title">{{ $admin ? 'Admin ' : '' }}Users</h4>
                        @if ($admin)
                            <div>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">Add Admin</button>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <table class="table" id="users-table">
                            <thead class="text-primary">
                                <tr>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>User Type</th>
                                    <th>Active</th>
                                    <th>Created At</th>
                                    <th style="width: 190px">Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.users.modal')
@endsection

@push('scripts')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            var table = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: window.location.href,
                columns: [
                    {data: 'full_name', name: 'full_name', orderable: true, searchable: false},
                    {data: 'email', name: 'email', orderable: true, searchable: false},
                    {data: 'role_name', name: 'role_name', orderable: false, searchable: false},
                    {data: 'active', name: 'active', orderable: false, searchable: false},
                    {data: 'created_at', name: 'created_at', orderable: false, searchable: false},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false, width: 190}
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

            table.on('click', '.btn-view', function (e) {
                let data = table.row(e.target.closest('tr')).data();

                var modal = $('#viewUserModal');
                    modal.find('#full_name').text(data.full_name);
                    modal.find('#email').text(data.email);
                    if (!data.is_admin) {
                        modal.find('.user-info').removeClass('d-none');
                        modal.find('#contact_number').text(data.contact_number);
                        modal.find('#address').text(data.address);
                    }
                    if (data.active) {
                        modal.find('.disable-group').removeClass('d-none');
                        modal.find('#disableForm').attr('action', data.disable_route);
                    } else {
                        modal.find('.enable-group').removeClass('d-none');
                        modal.find('#enableForm').attr('action', data.enable_route);
                    }
                    modal.modal('show');
            });

            $('#viewUserModal').on('hidden.bs.modal', function () {
                $(this).find('.user-info').addClass('d-none');
                $(this).find('.disable-group').addClass('d-none');
                $(this).find('#disableForm').attr('action', '#');
                $(this).find('.enable-group').addClass('d-none');
                $(this).find('#enableForm').attr('action', '#');
            });

            $('body').on('click', '.btn-disable', function (e) {
                e.preventDefault();
                var $this = $(this);
                Swal.fire({
                    title: '',
                    text: 'Are you sure you want to disable this user?',
                    showCancelButton: true,
                    cancelButtonText: 'Cancel',
                    confirmButtonText: 'Confirm',
                }).then((result) => {
                    if (result.value) {
                        $($this.data('target')).submit();
                    }
                });
            });
            
            $('body').on('click', '.btn-enable', function (e) {
                e.preventDefault();
                var $this = $(this);
                Swal.fire({
                    title: '',
                    text: 'Are you sure you want to enable this user?',
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