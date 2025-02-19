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
                        <form action="{{ route('product.update', $data['data_product']->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Product Name -->
                            <div class="mb-6">
                                <label class="form-label" for="product_name">Product Name</label>
                                <input type="text" class="form-control" id="product_name" name="product_name"
                                    value="{{ old('product_name', $data['data_product']->product_name) }}"
                                    placeholder="Enter product name" />
                            </div>

                            <!-- Category -->
                            <div class="mb-6">
                                <label class="form-label" for="category_id">Category</label>
                                <select class="form-control" id="category_id" name="category_id">
                                    <option value="" disabled>Select category</option>
                                    @foreach ($data['data_category'] as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $category->id == $data['data_product']->category_id ? 'selected' : '' }}>
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
                                @if ($data['data_product']->product_image)
                                    <img src="{{ asset('storage/' . $data['data_product']->product_image) }}"
                                        width="100" class="mt-2" />
                                @endif
                            </div>

                            <!-- Product Price -->
                            <div class="mb-6">
                                <label class="form-label" for="product_price">Product Price</label>
                                <input type="text" class="form-control" id="product_price" name="product_price"
                                    value="{{ old('product_price', $data['data_product']->product_price) }}"
                                    placeholder="Enter product price" />
                            </div>

                            <!-- Stock -->
                            <div class="mb-6">
                                <label class="form-label" for="stock">Stock</label>
                                <input type="text" class="form-control" id="stock" name="stock"
                                    value="{{ old('stock', $data['data_stock']->stock ?? '') }}"
                                    placeholder="Enter stock quantity" />
                            </div>

                            <!-- Expired Date (Initially Hidden) -->
                            <div class="mb-6" id="expired_at" style="display: none;">
                                <label class="form-label" for="expired_at">Expired Date and Time</label>
                                <input type="datetime-local" class="form-control" id="expired_at" name="expired_at"
                                    value="{{ old('expired_at', isset($data['data_stock']) ? \Carbon\Carbon::parse($data['data_stock']->expired_at)->format('Y-m-d\TH:i') : '') }}" />
                            </div>


                            <!-- Stock -->
                            <div class="mb-6">
                                <label class="form-label" for="minimum_stock">Minimum stock</label>
                                <input type="text" class="form-control" id="minimum_stock" name="minimum_stock"
                                    value="{{ old('minimum_stock', $data['data_product']->minimum_stock ?? '') }}"
                                    placeholder="Enter minimum stock " />
                            </div>

                            <!-- Product Status -->
                            <div class="mb-6">
                                <label class="form-label" for="product_status">Product Status</label>
                                <select class="form-control" id="product_status" name="product_status">
                                    <option value="" disabled>Select status</option>
                                    <option value="active"
                                        {{ $data['data_product']->product_status == 'active' ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="inactive"
                                        {{ $data['data_product']->product_status == 'inactive' ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                            </div>

                            <div class="mb-6">
                                <label class="form-label" for="unit_id">Jenis Satuan</label>
                                <select class="form-control" id="unit_id" name="unit_id">
                                    <option value="" disabled>Select unit</option>
                                    @foreach ($data['data_unitOfGoods'] as $unitOfGoods)
                                        <option value="{{ $unitOfGoods->id }}"
                                            {{ $unitOfGoods->id == $data['data_product']->unit_id ? 'selected' : '' }}>
                                            {{ $unitOfGoods->unit }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <!-- Access Role -->
                            <div class="mb-6">
                                <label class="form-label" for="access_role">Access Role</label>
                                <input type="text" class="form-control" id="access_role" name="access_role"
                                    value="{{ old('access_role', $data['data_product']->access_role) }}"
                                    placeholder="Enter access role" />
                            </div>

                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <!-- JavaScript -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let stockInput = document.getElementById("stock");
                let expired_at = document.getElementById("expired_at");

                stockInput.addEventListener("input", function() {
                    // Jika stock tidak kosong, tampilkan expired_date, jika kosong, sembunyikan
                    expired_at.style.display = stockInput.value.trim() !== "" ? "block" : "none";
                });
            });
        </script>
    @endpush
</x-app-layout>
