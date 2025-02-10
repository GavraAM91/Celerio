<x-app-layout>
    <!-- Basic Layout -->
    <div class="mt-4">
        <h2>Edit Product</h2>
        <div class="row">
            <div class="col-xl">
                <div class="card mb-6">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Edit Product</h5>
                        <small class="text-body float-end">Default label</small>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('product.update', $data_product->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Product Name -->
                            <div class="mb-6">
                                <label class="form-label" for="product_name">Product Name</label>
                                <input type="text" class="form-control" id="product_name" name="product_name"
                                    value="{{ old('product_name', $data_product->product_name) }}"
                                    placeholder="Enter product name" required />
                            </div>

                            <!-- Category -->
                            <div class="mb-6">
                                <label class="form-label" for="category_id">Category</label>
                                <select class="form-control" id="category_id" name="category_id" required>
                                    <option value="" disabled>Select category</option>
                                    @foreach ($data_category as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $category->id == $data_product->category_id ? 'selected' : '' }}>
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Product Image -->
                            <div class="mb-6">
                                <label class="form-label" for="product_image">Product Image</label>
                                <input type="file" class="form-control" id="product_image" name="product_image"
                                    accept="image/*" />
                                @if ($data_product->product_image)
                                    <img src="{{ asset('storage/' . $data_product->product_image) }}" width="100"
                                        class="mt-2" />
                                @endif
                            </div>

                            <!-- Product Price -->
                            <div class="mb-6">
                                <label class="form-label" for="product_price">Product Price</label>
                                <input type="text" class="form-control" id="product_price" name="product_price"
                                    value="{{ old('product_price', $data_product->product_price) }}"
                                    placeholder="Enter product price" required />
                            </div>

                            <!-- Stock -->
                            <div class="mb-6">
                                <label class="form-label" for="stock">Stock</label>
                                <input type="number" class="form-control" id="stock" name="stock"
                                    value="{{ old('stock', $data_product->stock) }}" placeholder="Enter stock quantity"
                                    required />
                            </div>

                            <!-- Expired Date -->
                            <div class="mb-4">
                                <label for="expired_at" class="block text-sm font-medium text-gray-700">Expired
                                    Date</label>
                                <input type="date" id="expired_at" name="expired_at"
                                    value="{{ old('expired_at', $data_product->expired_at ? \Carbon\Carbon::parse($data_product->expired_at)->format('Y-m-d') : '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <!-- Product Status -->
                            <div class="mb-6">
                                <label class="form-label" for="product_status">Product Status</label>
                                <select class="form-control" id="product_status" name="product_status" required>
                                    <option value="" disabled>Select status</option>
                                    <option value="active"
                                        {{ $data_product->product_status == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive"
                                        {{ $data_product->product_status == 'inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>

                            <!-- Access Role -->
                            <div class="mb-6">
                                <label class="form-label" for="access_role">Access Role</label>
                                <input type="text" class="form-control" id="access_role" name="access_role"
                                    value="{{ old('access_role', $data_product->access_role) }}"
                                    placeholder="Enter access role" required />
                            </div>

                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
