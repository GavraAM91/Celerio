<x-app-layout>
    @if (session('success'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050">
            <div class="toast align-items-center text-white bg-success border-0 show" role="alert" aria-live="assertive"
                aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050">
            <div class="toast align-items-center text-white bg-danger border-0 show" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif
    <div class="mt-4">
        <h2>Add Member</h2>
        <div class="row">
            <div class="col-xl">
                <div class="card mb-6">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Add Member</h5>
                        <small class="text-body float-end">Default label</small>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('membership.store') }}" name="createMember" method="POST">
                            @csrf

                            <!-- Name -->
                            <div class="mb-6">
                                <label class="form-label" for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Enter name" required />
                            </div>

                            <!-- Username -->
                            <div class="mb-6">
                                <label class="form-label" for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    placeholder="Enter username" required />
                            </div>

                            <!-- Email -->
                            <div class="mb-6">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Enter email" required />
                            </div>

                            <!-- Type -->
                            <div class="mb-6">
                                <label class="form-label" for="type">Type</label>
                                <select class="form-control" id="type" name="type" required>
                                    <option value="" disabled selected>Select type</option>
                                    <option value="type1">Type 1</option>
                                    <option value="type2">Type 2</option>
                                </select>
                            </div>

                            <!-- Phone Number -->
                            <div class="mb-6">
                                <label class="form-label" for="phone_number">Phone Number</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number"
                                    placeholder="Enter phone number" required />
                            </div>

                            <!-- Address -->
                            <div class="mb-6">
                                <label class="form-label" for="address">Address</label>
                                <input type="text" class="form-control" id="address" name="address"
                                    placeholder="Enter address" required />
                            </div>

                            <!-- Status -->
                            <div class="mb-6">
                                <label class="form-label" for="status">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="" disabled selected>Select status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
    <!-- / Content -->
</x-app-layout>
