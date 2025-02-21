<div class="modal fade" id="addMaterialModal" tabindex="-1" aria-labelledby="addMaterialLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title mb-0" id="addMaterialLabel">Add Raw Material</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.material.store') }}" method="post" id="addMaterial">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="name">Material Type</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Material Type" value="{{ old('name') }}" required />
                        @if ($errors->has('name'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                    {{-- <div class="form-group mb-3">
                        <label for="batch_number">Batch Number</label>
                        <input type="text" class="form-control" name="batch_number" id="batch_number" placeholder="Batch Number" required />
                    </div> --}}
                    {{-- <div class="form-group mb-3">
                        <label for="quantity">Material Quantity</label>
                        <input type="number" class="form-control" name="quantity" id="quantity" placeholder="Material Quantity" required />
                    </div> --}}
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="addMaterial">Save changes</button>
            </div>
        </div>
    </div>
</div>

{{-- <div class="modal fade" id="editMaterialModal" tabindex="-1" aria-labelledby="editMaterialLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title mb-0" id="editMaterialLabel">Edit Raw Material</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" method="post" id="editMaterial">
                    @csrf
                    @method('PATCH')

                    <div class="form-group mb-3">
                        <label for="name">Material Type</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Material Type" required />
                    </div>
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
                <button type="submit" class="btn btn-primary" form="editMaterial">Save changes</button>
            </div>
        </div>
    </div>
</div> --}}

<div class="modal fade" id="restockModal" tabindex="-1" aria-labelledby="restockLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title mb-0" id="restockLabel"><span class="label_type"></span> Raw Material Stock</h2>
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