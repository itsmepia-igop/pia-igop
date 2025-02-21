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
                <p class="mb-0">Something went wrong in adding the product. Click <a href="#" data-bs-toggle="modal" data-bs-target="#addProductModal">here</a> to view.</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Available Products</h4>

                        <div class="d-flex gap-2 align-items-center">
                            <div class="d-flex gap-2">
                                <select id="sales_type" class="form-select">
                                    <option value="">Select an Option</option>
                                    <option value="generic">Generic Product</option>
                                    <option value="customized">Customized</option>
                                </select>
                            </div>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">Add Product</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table" id="product-table">
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

    @include('admin.product.modal', ['materials' => $materials])
@endsection

@push('scripts')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    @include('components.repeater-js')

    <script>
        const materials = {{ Js::from($materials) }};

        $(document).ready(function () {
            var table = $('#product-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: window.location.href,
                    data: function (d) {
                        return $.extend({}, d, {
                            sales_type: $('#sales_type').val()
                        });
                    }
                },
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
                    $('.dataTables_filter input', $('#product-table')).unbind();
                    $('.dataTables_filter input', $('#product-table')).bind('keyup', function(e) {
                        if(e.keyCode == 13) {
                            table.search(this.value).draw();
                        }
                    });

                    const fragment = window.location.hash;
                    if (fragment) {
                        $('.btn-edit' + fragment).trigger('click');
                    }
                },
            });

            $('#sales_type').on('change', function () {
                table.draw();
            });

            table.on('click', '.btn-remove', function (e) {
                e.preventDefault();
                var $this = $(this);
                Swal.fire({
                    title: '',
                    icon: 'info',
                    html: 'Are you sure you want to <b>remove</b> this product?',
                    showCancelButton: true,
                    cancelButtonText: 'Cancel',
                    confirmButtonText: 'Confirm',
                }).then((result) => {
                    if (result.value) {
                        $($this.data('target')).submit();
                    }
                });
            });

            $('#is_customize').on('change', function () {
                if ($(this).prop('checked')) {
                    $(this).closest('form').find('.materials-group').removeClass('d-none');
                    $(this).closest('form').find('.materials-group').find('.form-control, .form-select').prop('required', true);
                    $(this).closest('form').find('.thickness-group').removeClass('d-none');
                    $(this).closest('form').find('.thickness-group').find('.form-control, .form-select').prop('required', true);
                    $(this).closest('form').find('.size-group').removeClass('d-none');
                    $(this).closest('form').find('.size-group').find('.form-control, .form-select').prop('required', true);
                    // $(this).closest('form').find('.product-quantity').addClass('d-none');
                    // $(this).closest('form').find('.product-quantity').find('.form-control').prop('required', false).prop('min', 0);
                } else {
                    $(this).closest('form').find('.materials-group').addClass('d-none');
                    $(this).closest('form').find('.materials-group').find('.form-control, .form-select').prop('required', false);
                    $(this).closest('form').find('.thickness-group').addClass('d-none');
                    $(this).closest('form').find('.thickness-group').find('.form-control, .form-select').prop('required', false);
                    $(this).closest('form').find('.size-group').removeClass('d-none');
                    $(this).closest('form').find('.size-group').find('.form-control, .form-select').prop('required', true);
                    // $(this).closest('form').find('.product-quantity').removeClass('d-none');
                    // $(this).closest('form').find('.product-quantity').find('.form-control').prop('required', true).prop('min', 1);
                }
            });

            $(document).on('change', '.material_type_select', function () {
                var $this = $(this);
                
                if ($this.val() !== '') {
                    var material = materials.find((item) => $this.val() == item.id);
                    if (material) {
                        $this.closest('.material-item').find('.material_quantity').text(material.quantity);
                        return false;
                    }
                }

                $this.closest('.material-item').find('.material_quantity').text(0);
            });


        });
    </script>
@endpush