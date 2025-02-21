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
            <div class="col-md-4">
                <form action="{{ route('admin.product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Product Details</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" value="1" id="is_customize" name="is_customize" {{ $product->is_customize ? 'checked' : '' }} />
                                        <span class="form-check-sign"></span>
                                        Customizable
                                    </label>
                                </div>
                            </div>
                            <div class="design-form mb-3">
                                <div class="">
                                    @if (!empty($product->file_id))
                                        <img src="{{ Storage::url($product->file->path) }}" alt="{{ $product->name }}" style="width: 250px; height: 200px; object-fit: cover;">
                                    @endif
                                </div>
                                <label for="design">Design</label>
                                <input type="file" class="form-control" id="design" name="design" placeholder="Design (optional)" accept="image/*" />
                                @if ($errors->has('design'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('design') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group mb-3">
                                <label for="name">Product Name</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Product Name" value="{{ old('name', $product->name ?? '') }}" required />
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            @if (!$product->is_customize)
                                <div class="form-group mb-3">
                                    <label for="quantity">Quantity</label>
                                    <input type="text" class="form-control" name="quantity" id="quantity" placeholder="Quantity" value="{{ $product->quantity ?? 0 }}" readonly />
                                </div>
                            @endif
                            <div class="form-group mb-3">
                                <label for="price">Product Price (₱)</label>
                                <input type="number" step="0.01" class="form-control" name="price" id="price" min="1" placeholder="Product Price (₱)" value="{{ old('price', $product->price ?? 0) }}" required />
                                @if ($errors->has('price'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('price') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group mb-5">
                                <label for="description">Product Desciption</label>
                                <textarea class="form-control" name="description" id="description" placeholder="Product Desciption" required>{{ old('description', $product->description ?? '') }}</textarea>
                                @if ($errors->has('description'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                            
                            <div class="size-group repeater">
                                <h6>Size Options</h6>
                                <div class="list">
                                    @if (!empty(old('size')))
                                        @foreach (old('sizes') as $size)
                                            <div class="row item mb-3">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="size[]">Size Option</label>
                                                        <input type="text" class="form-control" name="size[]" id="size[]" placeholder="Size Option" value="{{ $size ?? '' }}" required />
                                                        @if ($errors->has('sizes.'.$key))
                                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                                <strong>{{ $errors->first('sizes.'.$key) }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @elseif (!empty($product->sizes))
                                        @foreach (json_decode($product->sizes) as $size)
                                            <div class="row item mb-3">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="size[]">Size Option</label>
                                                        <input type="text" class="form-control" name="size[]" id="size[]" placeholder="Size Option" value="{{ $size ?? '' }}"  required />
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="row item mb-3">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="size[]">Size Option</label>
                                                    <input type="text" class="form-control" name="size[]" id="size[]" placeholder="Size Option" required />
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="text-end">
                                    <button type="button" class="btn btn-danger btn-remove {{ (!empty(old('sizes')) && count(old('sizes')) > 1) || (!empty($product->sizes) && count(json_decode($product->sizes)) > 1) ?: 'd-none' }}">Remove Option</button>
                                    <button type="button" class="btn btn-add">Add Option</button>
                                </div>
                            </div>
                            
                            <div class="thickness-group repeater {{ $product->is_customize ?: 'd-none' }}">
                                <h6>Thickness Options</h6>
                                <div class="list">
                                    @if (!empty(old('thickness')))
                                        @foreach (old('thickness') as $thickness)
                                            <div class="row item mb-3">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="thickness[]">Thickness Option</label>
                                                        <input type="text" class="form-control" name="thickness[]" id="thickness[]" placeholder="Thickness Option" value="{{ $thickness ?? '' }}" {{ old('is_customize') ? 'required' : '' }} />
                                                        @if ($errors->has('thickness.'.$key))
                                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                                <strong>{{ $errors->first('thickness.'.$key) }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @elseif (!empty($product->thickness))
                                        @foreach (json_decode($product->thickness) as $thickness)
                                            <div class="row item mb-3">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="thickness[]">Thickness Option</label>
                                                        <input type="text" class="form-control" name="thickness[]" id="thickness[]" placeholder="Thickness Option" value="{{ $thickness ?? '' }}" required />
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="row item mb-3">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="thickness[]">Thickness Option</label>
                                                    <input type="text" class="form-control" name="thickness[]" id="thickness[]" placeholder="Thickness Option" />
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="text-end">
                                    <button type="button" class="btn btn-danger btn-remove {{ (!empty(old('thickness')) && count(old('thickness')) > 1) || (!empty($product->thickness) && count(json_decode($product->thickness)) > 1) ?: 'd-none' }}">Remove Option</button>
                                    <button type="button" class="btn btn-add">Add Option</button>
                                </div>
                            </div>
        
                            <div class="materials-group repeater {{ $product->is_customize ?: 'd-none' }}">
                                <h6>Raw Materials</h6>
                                <div class="materials-list list">
                                    @if (!empty(old('materials_id')))
                                        @foreach (old('materials_id') as $key => $raw_material)
                                            <div class="row material-item item mb-3">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="">
                                                            <div class="float-end d-flex gap-2">
                                                                <h6 class="mb-0">Stock: </h6>
                                                                <span class="material_quantity">0</span>
                                                            </div>
                                                            <label for="materials_id[]">Material Type</label>
                                                        </div>
                                                        <select class="form-select material_type_select" name="materials_id[]" id="materials_id[]" placeholder="Material Type" {{ old('is_customize') ? 'required' : '' }}>
                                                            <option value="">Select an option</option>
                                                            @foreach ($materials as $material)
                                                                <option value="{{ $material->id }}" {{ $raw_material == $material->id ? 'selected' : '' }}>{{ $material->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('materials_id.'.$key))
                                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                                <strong>{{ $errors->first('materials_id.'.$key) }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="materials_count[]">Material per Product</label>
                                                        <input type="number" class="form-control" name="materials_count[]" id="materials_count[]" min="1" value="{{ old('materials_count')[$key] ?? 1 }}" placeholder="Material per Product" {{ old('is_customize') ? 'required' : '' }} />
                                                        @if ($errors->has('materials_count.'.$key))
                                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                                <strong>{{ $errors->first('materials_count.'.$key) }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @elseif (count($product->raw_materials))
                                        @foreach ($product->raw_materials as $raw_material)
                                            <div class="row material-item item mb-3">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="">
                                                            <div class="float-end d-flex gap-2">
                                                                <h6 class="mb-0">Stock: </h6>
                                                                <span class="material_quantity">{{ $materials->where('id', $raw_material->raw_material_id)->first()->quantity ?? 0 }}</span>
                                                            </div>
                                                            <label for="materials_id[]">Material Type</label>
                                                        </div>
                                                        <select class="form-select material_type_select" name="materials_id[]" id="materials_id[]" placeholder="Material Type" required>
                                                            <option value="">Select an option</option>
                                                            @foreach ($materials as $material)
                                                                <option value="{{ $material->id }}" {{ $raw_material->raw_material_id == $material->id ? 'selected' : '' }}>{{ $material->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="materials_count[]">Material per Product</label>
                                                        <input type="number" class="form-control" name="materials_count[]" id="materials_count[]" min="1" value="{{ $raw_material->count ?? 1 }}" placeholder="Material per Product" required />
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="row material-item item mb-3">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <div class="">
                                                        <div class="float-end d-flex gap-2">
                                                            <h6 class="mb-0">Stock: </h6>
                                                            <span class="material_quantity">0</span>
                                                        </div>
                                                        <label for="materials_id[]">Material Type</label>
                                                    </div>
                                                    <select class="form-select material_type_select" name="materials_id[]" id="materials_id[]" placeholder="Material Type">
                                                        <option value="">Select an option</option>
                                                        @foreach ($materials as $material)
                                                            <option value="{{ $material->id }}">{{ $material->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="materials_count[]">Material per Product</label>
                                                    <input type="number" class="form-control" name="materials_count[]" id="materials_count[]" min="1" placeholder="Material per Product" />
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="text-end">
                                    <button type="button" class="btn btn-danger btn-remove {{ (!empty(old('material_ids')) && count(old('material_ids')) > 1) || count($product->raw_materials) > 1 ?: 'd-none' }}">Remove Material</button>
                                    <button type="button" class="btn btn-add">Add Material</button>
                                </div>
                            </div>

                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary mx-auto">Save changes</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            @if (!$product->is_customize)
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Batches</h4>
                            <div>
                                <button type="button" class="btn btn-primary btn-restock"
                                    data-route="{{ route('admin.product.restock', $product->id) }}"
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
            @endif
        </div>
    </div>

    @include('admin.product.modal')
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

            table.on('click', '.btn-remove', function (e) {
                e.preventDefault();
                var $this = $(this);
                Swal.fire({
                    title: '',
                    text: 'Are you sure you want to remove this product batch?',
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