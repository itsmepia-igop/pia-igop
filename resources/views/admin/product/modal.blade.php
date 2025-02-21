<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title mb-0" id="addProductLabel">Add Product</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.product.store') }}" method="post" id="addProduct" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group mb-3">
                        <div class="form-check">
                             <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" value="1" id="is_customize" name="is_customize" {{ !empty(old('is_customize')) ? 'checked' : '' }} />
                                <span class="form-check-sign"></span>
                                Customizable
                            </label>
                        </div>
                    </div>
                    <div class="design-form mb-3">
                        <label for="design">Design</label>
                        <input type="file" class="form-control" id="design" name="design" placeholder="Design" accept="image/*" required />
                    </div>
                    <div class="form-group mb-3">
                        <label for="name">Product Name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Product Name" value="{{ old('name') }}" required />
                        @if ($errors->has('name'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                    {{-- <div class="form-group mb-3 product-quantity">
                        <label for="quantity">Product Quantity</label>
                        <input type="number" class="form-control" name="quantity" id="quantity" min="1" placeholder="Product Quantity" required />
                    </div> --}}
                    <div class="form-group mb-3">
                        <label for="price">Product Price (₱)</label>
                        <input type="number" step="0.01" class="form-control" name="price" id="price" min="1" placeholder="Product Price (₱)" value="{{ old('price') }}" required />
                        @if ($errors->has('price'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('price') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group mb-5">
                        <label for="description">Product Desciption</label>
                        <textarea class="form-control" name="description" id="description" placeholder="Product Desciption" required>{{ old('description') }}</textarea>
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
                                @foreach (old('size') as $key => $size)
                                    <div class="row item mb-3">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="size[{{ $key }}]">Size Option</label>
                                                <input type="text" class="form-control" name="size[{{ $key }}]" id="size[{{ $key }}]" placeholder="Size Option" value="{{ $size ?? '' }}" required />
                                                @if ($errors->has('size.'.$key))
                                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                                        <strong>{{ $errors->first('size.'.$key) }}</strong>
                                                    </span>
                                                @endif
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
                            <button type="button" class="btn btn-danger btn-remove {{ !empty(old('size')) && count(old('size')) > 1 ? '' : 'd-none' }}">Remove Option</button>
                            <button type="button" class="btn btn-add">Add Option</button>
                        </div>
                    </div>

                    <div class="thickness-group repeater {{ !empty(old('is_customize')) ? '' : 'd-none' }}">
                        <h6>Thickness Options</h6>
                        <div class="list">
                            @if (!empty(old('thickness')))
                                @foreach (old('thickness') as $key => $thickness)
                                    <div class="row item mb-3">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="thickness[{{ $key }}]">Thickness Option</label>
                                                <input type="text" class="form-control" name="thickness[{{ $key }}]" id="thickness[{{ $key }}]" placeholder="Thickness Option" value="{{ $thickness ?? '' }}" {{ old('is_customize') ? 'required' : '' }} />
                                                @if ($errors->has('thickness.'.$key))
                                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                                        <strong>{{ $errors->first('size.'.$key) }}</strong>
                                                    </span>
                                                @endif
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
                            <button type="button" class="btn btn-danger btn-remove {{ !empty(old('thickness')) && count(old('thickness')) > 1 ? '' : 'd-none' }}">Remove Option</button>
                            <button type="button" class="btn btn-add">Add Option</button>
                        </div>
                    </div>

                    <div class="materials-group repeater {{ !empty(old('is_customize')) ? '' : 'd-none' }}">
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
                            <button type="button" class="btn btn-danger btn-remove {{ (!empty(old('material_ids')) && count(old('material_ids')) > 1) ?: 'd-none' }}">Remove Material</button>
                            <button type="button" class="btn btn-add">Add Material</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="addProduct">Save changes</button>
            </div>
        </div>
    </div>
</div>

{{-- <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title mb-0" id="editProductLabel">Edit Product</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" method="post" id="editProduct" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="form-group mb-3">
                        <div class="form-check">
                             <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" value="1" id="is_customize-edit" name="is_customize" />
                                <span class="form-check-sign"></span>
                                Customizable
                            </label>
                        </div>
                    </div>
                    <div class="design-form mb-3">
                        <label for="design">Design (optional)</label>
                        <input type="file" class="form-control" id="design" name="design" placeholder="Design (optional)" accept="image/*" />
                    </div>
                    <div class="form-group mb-3">
                        <label for="name">Product Name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Product Name" required />
                    </div>
                    <div class="form-group mb-3">
                        <label for="price">Product Price (₱)</label>
                        <input type="number" step="0.01" class="form-control" name="price" id="price" min="1" placeholder="Product Price (₱)" required />
                    </div>
                    <div class="form-group mb-3">
                        <label for="description">Product Desciption</label>
                        <textarea class="form-control" name="description" id="description" placeholder="Product Desciption" required></textarea>
                    </div>

                    <div class="materials-group d-none">
                        <h6>Raw Materials</h6>
                        <div class="materials-list">
                            <div class="row material-item">
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <div class="">
                                            <div class="float-end d-flex gap-2">
                                                <h6 class="mb-0">Stock: </h6>
                                                <span class="material_quantity">0</span>
                                            </div>
                                            <label for="materials_id[]">Material Type</label>
                                        </div>
                                        <select class="form-select" name="materials_id[]" id="materials_id[]" placeholder="Material Type">
                                            <option value="">Select an option</option>
                                            @foreach ($materials as $material)
                                                <option value="{{ $material->id }}">{{ $material->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label for="materials_count[]">Material per Product</label>
                                        <input type="number" class="form-control" name="materials_count[]" id="materials_count[]" placeholder="Material Type" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-danger btn-remove-material d-none">Remove Material</button>
                            <button type="button" class="btn btn-add-material">Add Material</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="editProduct">Save changes</button>
            </div>
        </div>
    </div>
</div> --}}

<div class="modal fade" id="restockModal" tabindex="-1" aria-labelledby="restockLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title mb-0" id="restockLabel"><span class="label_type"></span> Product Stock</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" method="post" id="restock">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="batch_number">Batch Number</label>
                        <input type="text" class="form-control" name="batch_number" id="batch_number" placeholder="Batch Number" required />
                    </div>
                    <div class="form-group mb-3">
                        <label for="quantity">Material Quantity</label>
                        <input type="number" class="form-control" name="quantity" id="quantity" placeholder="Material Quantity" required />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="restock"><span class="label_type"></span> Stocks</button>
            </div>
        </div>
    </div>
</div>