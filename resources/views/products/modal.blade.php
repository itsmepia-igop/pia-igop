<div class="modal fade" id="viewProductModal" tabindex="-1" aria-labelledby="viewProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title mb-0" id="viewProductLabel"></h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <span class="fw-bold">Quantity</span>
                        <p id="quantity"></p>
                    </div>
                    <div class="col-6">
                        <span class="fw-bold">Price</span>
                        <p id="price"></p>
                    </div>
                    <div class="col-12">
                        <span class="fw-bold">Description</span>
                        <p class="mb-3" id="description"></p>
                    </div>
                    <div class="col-12">
                        <span>Raw Materials</span>
                        <ul class="materials list-group"></ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" class="btn btn-primary btn-checkout">Checkout</a>
            </div>
        </div>
    </div>
</div>