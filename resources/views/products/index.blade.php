@extends('layouts.auth', [
    'class' => 'overflow-auto mh-100 h-100',
    'navbarClass' => 'bg-secondary py-3',
    'sectionClass' => 'bg-light'
])


@push('css')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive-bs5/css/responsive.bootstrap5.min.css') }}">
@endpush

@section('content')
    <div class="content">
        <div class="container">
            @include('layouts.alert')
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Products</h4>
                        </div>
                        <div class="card-body">
                            <table class="table" id="product-table">
                                <thead class="text-primary">
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Description</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th style="width: 190px">Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('products.modal')
@endsection

@push('scripts')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            demo.checkFullPageBackgroundImage();
            var table = $('#product-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: window.location.href,
                columns: [
                    {data: 'name', name: 'name', orderable: true, searchable: false},
                    {data: 'description_table', name: 'description', orderable: true, searchable: false},
                    {data: 'quantity', name: 'quantity', orderable: false, searchable: false},
                    {data: 'price', name: 'price', orderable: false, searchable: false},
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

                var modal = $('#viewProductModal');
                    modal.find('#viewProductLabel').text(data.name);
                    modal.find('#quantity').text(data.quantity);
                    modal.find('#price').text(data.price);
                    modal.find('#description').text(data.description);

                    $.each(data.materials, function (key, value) {
                        modal.find('.materials').append(`
                            <li class="list-group-item mb-0">(${value.count}) ${value.material.name}</li>
                        `);
                    });
                    
                    modal.find('.btn-checkout').attr('href', data.checkout_route);
                    modal.modal('show');
            });

            $('#viewProductModal').on('hidden.bs.modal', function () {
                $(this).find('.materials').html('');
            });
        });
    </script>
@endpush