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
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Raw Material Details</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.material.update', ['id' => $raw_material->id]) }}" method="POST" id="addMaterial">
                            @csrf
                            @method('PATCH')
                            <div class="form-group mb-3">
                                <label for="name">Material Type</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Material Type" value="{{ old('name', $raw_material->name ?? '') }}" required />
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group mb-3">
                                <label for="quantity">Quantity</label>
                                <input type="text" class="form-control" name="quantity" id="quantity" placeholder="Quantity" value="{{ $raw_material->quantity ?? 0 }}" readonly />
                            </div>

                            <div class="d-flex">
                               <button type="submit" class="btn btn-primary mx-auto" form="addMaterial">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Batches</h4>
                        <div>
                            <button type="button" class="btn btn-primary btn-restock"
                                data-route="{{ route('admin.material.restock', $raw_material->id) }}"
                                data-label-type="Add">Restock</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table" id="batches-table">
                            <thead class="text-primary">
                                <tr>
                                    <th>Batch Number</th>
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
            var table = $('#batches-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: window.location.href,
                columns: [
                    {data: 'batch_number', name: 'batch_number', orderable: false, searchable: false},
                    // {data: 'name', name: 'name', orderable: true, searchable: false},
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

            table.on('click', '.btn-edit', function (e) {
                let data = table.row(e.target.closest('tr')).data();
                
                var modal = $('#restockModal');
                    modal.find('form#restock').attr('action', $(this).data('route'));
                    modal.find('.label_type').text($(this).data('label-type'));
                    modal.find('#batch_number').val(data.batch_number);
                    modal.find('#quantity').val(data.quantity);
                    modal.modal('show');
            });

            $('body').on('click', '.btn-remove', function (e) {
                e.preventDefault();
                var $this = $(this);
                Swal.fire({
                    title: '',
                    text: 'Are you sure you want to remove this raw material batch?',
                    showCancelButton: true,
                    cancelButtonText: 'Cancel',
                    confirmButtonText: 'Confirm',
                }).then((result) => {
                    if (result.value) {
                        $($this.data('target')).submit();
                    }
                });
            });

            $('.btn-restock').on('click', function () {
                var modal = $('#restockModal');
                    modal.find('form#restock').attr('action', $(this).data('route'));
                    modal.find('.label_type').text($(this).data('label-type'));
                    modal.find('#batch_number').val('');
                    modal.find('#quantity').val('');
                    modal.modal('show');
            });
        });
    </script>
@endpush