<x-app-layout>
    <div class="card mb-6">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Basic Layout</h5>
            <small class="text-muted float-end">Default label</small>
        </div>
        <div class="card-body">
            <form>
                <div class="row mb-6">
                    <label class="col-sm-2 col-form-label" for="basic-default-name">Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="basic-default-name" placeholder="John Doe" />
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-sm-2 col-form-label" for="basic-default-company">Company</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="basic-default-company" placeholder="ACME Inc." />
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-sm-2 col-form-label" for="basic-default-email">Email</label>
                    <div class="col-sm-10">
                        <div class="input-group input-group-merge">
                            <input type="text" id="basic-default-email" class="form-control" placeholder="john.doe"
                                aria-label="john.doe" aria-describedby="basic-default-email2" />
                            <span class="input-group-text" id="basic-default-email2">@example.com</span>
                        </div>
                        <div class="form-text">You can use letters, numbers & periods</div>
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-sm-2 col-form-label" for="basic-default-phone">Phone No</label>
                    <div class="col-sm-10">
                        <input type="text" id="basic-default-phone" class="form-control phone-mask"
                            placeholder="658 799 8941" aria-label="658 799 8941"
                            aria-describedby="basic-default-phone" />
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-sm-2 col-form-label" for="basic-default-message">Message</label>
                    <div class="col-sm-10">
                        <textarea id="basic-default-message" class="form-control" placeholder="Hi, Do you have a moment to talk Joe?"
                            aria-label="Hi, Do you have a moment to talk Joe?" aria-describedby="basic-icon-default-message2"></textarea>
                    </div>
                </div>
                <div class="row justify-content-end">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">Send</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Basic Layout -->
    <div class="mt-4">
        <h2>Add Product</h2>
        <div class="row">
            <div class="col-xl">
                <div class="card mb-6">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Add Product</h5>
                        <small class="text-body float-end">Default label</small>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('product.store') }}" name="createProduct" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Product Name -->
                            <div class="mb-6">
                                <label class="form-label" for="product_name">Product Name</label>
                                <input type="text" class="form-control" id="product_name" name="product_name"
                                    placeholder="Enter product name" required />
                            </div>

                            <!-- Category -->
                            <div class="mb-6">
                                <label class="form-label" for="id_category">Category</label>
                                <select class="form-control" id="category_id" name="category_id" required>
                                    <option value="" disabled selected>Select category</option>
                                    @foreach ($data_category as $category)
                                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Product Image -->
                            <div class="mb-6">
                                <label class="form-label" for="product_image">Product Image</label>
                                <input type="file" class="form-control" id="product_image" name="product_image"
                                    accept="image/*" />
                            </div>

                            <!-- Product Price -->
                            <div class="mb-6">
                                <label class="form-label" for="product_price">Product Price</label>
                                <input type="text" class="form-control" id="product_price" name="product_price"
                                    placeholder="Enter product price" required />
                            </div>

                            <!-- Stock -->
                            <div class="mb-6">
                                <label class="form-label" for="stock">Stock</label>
                                <input type="number" class="form-control" id="stock" name="stock"
                                    placeholder="Enter stock quantity" required />
                            </div>

                            <div class="mb-4">
                                <label for="expired_at" class="block text-sm font-medium text-gray-700">Expired
                                    Date</label>
                                <input type="date" id="expired_at" name="expired_at"
                                    value="{{ old('expired_at', isset($data) ? \Carbon\Carbon::parse($data->expired_date)->format('Y-m-d') : '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            x
                            <!-- Product Status -->
                            <div class="mb-6">
                                <label class="form-label" for="product_status">Product Status</label>
                                <select class="form-control" id="product_status" name="product_status" required>
                                    <option value="" disabled selected>Select status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>

                            <!-- Access Role -->
                            <div class="mb-6">
                                <label class="form-label" for="access_role">Access Role</label>
                                <input type="text" class="form-control" id="access_role" name="access_role"
                                    placeholder="Enter access role" required />
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
