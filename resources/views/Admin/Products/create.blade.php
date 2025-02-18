<x-app-layout>
    <!-- Basic Layout -->
    <div class="mt-4">
        <h2>Add Product</h2>
        <div class="row">
            <div class="col-xl">
                <div class="card mb-6">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Add Product</h5>
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

                            <div class="mb-6">
                                <label for="expired_at">Expired Date & Time:</label>
                                <input type="datetime-local" class="form-control" id="expired_at" name="expired_at"
                                    required>
                            </div>


                            <!-- Stock -->
                            <div class="mb-6">
                                <label class="form-label" for="minimum_stock">Minimum Stock</label>
                                <input type="number" class="form-control" id="minimum_stock" name="minimum_stock"
                                    placeholder="Enter minimum stock " required />
                            </div>

                            <!-- Product Status -->
                            <div class="mb-6">
                                <label class="form-label" for="product_status">Product Status</label>
                                <select class="form-control" id="product_status" name="product_status" required>
                                    <option value="" disabled selected>Select status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>

                            <!-- satuan -->
                            <div class="mb-6">
                                <label class="form-label" for="unit_id">Jenis Satuan</label>
                                <select class="form-control" id="unit_id" name="unit_id" required>
                                    <option value="" disabled selected>Select category</option>
                                    @foreach ($data_UnitOfGoods as $unitOfGoods)
                                        <option value="{{ $unitOfGoods->id }}">{{ $unitOfGoods->unit }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Access Role -->
                            <div class="mb-6">
                                <label class="form-label" for="access_role">Access Role (can edit this)</label>
                                <input type="text" class="form-control" id="access_role" name="access_role"
                                    placeholder="Enter access role" required />
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-secondary"
                                onclick="window.history.back();">Kembali</button>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
    <!-- / Content -->
</x-app-layout>
