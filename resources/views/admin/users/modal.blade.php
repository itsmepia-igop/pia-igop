<div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title mb-0" id="viewUserLabel">User Details</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <span class="fw-bold">Full Name</span>
                        <p id="full_name"></p>
                    </div>
                    <div class="col-6">
                        <span class="fw-bold">Email</span>
                        <p id="email"></p>
                    </div>
                    <div class="col-12">
                        <div class="row user-info d-none">
                            <div class="col-12">
                                <span class="fw-bold">Contact Number</span>
                                <p class="mb-3" id="contact_number"></p>
                            </div>
                            <div class="col-12">
                                <span class="fw-bold">Address</span>
                                <p class="mb-3" id="address"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <div class="disable-group d-none">
                    <button type="button" class="btn btn-warning btn-disable" data-target="#disableForm">Disable</button>
                    <form action="#" class="d-none" id="disableForm" method="POST">@csrf @method('PATCH')</form>
                </div>
                <div class="enable-group d-none">
                    <button type="button" class="btn btn-info btn-enable" data-target="#enableForm">Enable</button>
                    <form action="#" class="d-none" id="enableForm" method="POST">@csrf @method('PATCH')</form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title mb-0" id="addUserLabel">Add User</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.users.create.admin') }}" method="post" id="addUser">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email" required />
                    </div>
                    <div class="form-group mb-3">
                        <label for="user_type">User Type</label>
                        <select name="user_type" id="user_type" class="form-control form-select">
                            <option value="admin">Administator</option>
                            <option value="staff">Staff</option>
                        </select>
                    </div>
                    <label for="password">Password</label>
                    <div class="form-group mb-3 position-relative">
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" required />
                        <i class="fa fa-eye position-absolute top-50 translate-middle-y btn-password" style="right: 10px;"></i>
                    </div>
                    <label for="password_confirmation">Password Confirmation</label>
                    <div class="form-group mb-3 position-relative">
                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Password Confirmation" required />
                        <i class="fa fa-eye position-absolute top-50 translate-middle-y btn-password" style="right: 10px;"></i>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="addUser">Save</button>
            </div>
        </div>
    </div>
</div>