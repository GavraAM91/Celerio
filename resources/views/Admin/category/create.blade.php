<x-app-layout>
    <div class="mt-4">
        <h2>Add Category</h2>
        <div class="row">
            <div class="col-xl">
                <div class="card mb-6">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Add Category</h5>
                        <small class="text-body float-end">Category Form</small>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('category.store') }}" name="createCategory" method="POST">
                            @csrf

                            <!-- Category Name -->
                            <div class="mb-6">
                                <label class="form-label" for="category_name">Category Name</label>
                                <input type="text" class="form-control" id="category_name" name="category_name"
                                    placeholder="Enter category name" required />
                            </div>

                            <!-- Access Role -->
                            <div class="mb-6">
                                <label class="form-label" for="access_role">Access Role</label>
                                <select class="form-control" id="access_role" name="access_role" required>
                                    <option value="" disabled selected>Select access role</option>
                                    <option value="admin">Admin</option>
                                    <option value="user">User</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
