<x-app-layout>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="mt-4">
        <h2>Products Table</h2>
    </div>
    <div class="mt-4">
        <div class="card">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Header Title -->
                <h5 class="card-header mb-0">Product List</h5>

                <!-- Button -->
                <button class="btn btn-primary mx-4">
                    Add Product
                </button>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Sold Product</th>
                            <th>Expired Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($data_product as $product)
                            <tr>
                                <td>
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                        alt="{{ $product->product_name }}" class="rounded-circle" width="50">
                                </td>
                                <td>{{ $product->product_name }}</td>
                                <td>Rp {{ number_format($product->product_price, 0, ',', '.') }}</td>
                                <td>{{ $product->stock }}</td>
                                <td>{{ $product->sold_product }}</td>
                                <td>{{ $product->expired_at }}</td>
                                <td>
                                    <span
                                        class="badge 
                        @if ($product->product_status == 'available') bg-label-success 
                        @elseif ($product->product_status == 'out of stock') bg-label-danger 
                        @elseif ($product->product_status == 'expired') bg-label-warning 
                        @else bg-label-secondary @endif">
                                        {{ ucfirst($product->product_status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('products.show', $product->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item"
                                                    onclick="return confirm('Are you sure you want to delete this product?')">
                                                    <i class="bx bx-trash me-1"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
