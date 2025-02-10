<x-app-layout>
    {{-- @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif --}}

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
        <h2>Products Table</h2>
    </div>
    <div class="mt-4">
        <div class="card">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Header Title -->
                <h5 class="card-header mb-0">Product List</h5>

                <!-- Button -->
                <a class="btn btn-primary mx-4" href="{{ route('product.create') }}">
                    Add Product
                </a>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
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
                                    <img src="{{ asset('storage/Product/product_image/' . $product->product_image) }}"
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
                                            <a class="dropdown-item" href="{{ route('product.edit', $product->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            {{-- <a href="javascript:void(0);" class="btn btn-info btn-detail"
                                                data-id="{{ $product->id }}" data-bs-toggle="modal"
                                                data-bs-target="#detailModal">
                                                <i class="bx bx-edit-alt me-1"></i> Detail
                                            </a> --}}

                                            <button type="button" class="btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#modalCenter">
                                                Detail Data
                                            </button>

                                            <div class="modal fade" id="modalCentercc " tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Detail Data</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col mb-6">
                                                                    <label for="nameWithTitle"
                                                                        class="form-label">Name</label>
                                                                    <input type="text" id="nameWithTitle"
                                                                        class="form-control" readonly />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- 
                                            <x-modal-detail-layouts>
                                                <div class="row">
                                                    <div class="col mb-6">
                                                        <label for="nameWithTitle" class="form-label">Name</label>
                                                        <input type="text" id="nameWithTitle" class="form-control"
                                                            readonly />
                                                    </div>
                                                </div>
                                            </x-modal-detail-layouts> --}}


                                            <form action="{{ route('product.destroy', $product->id) }}"
                                                class="d-inline">
                                                @csrf
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.btn-info').click(function() {
                let id = $(this).data('id'); // Ambil ID dari tombol

                $.ajax({
                    url: `{{ url('product') }}/${id}`
                    success: function(response) {
                        if (response.success == "200") {
                            // Masukkan data ke dalam modal
                            $('#nameWithTitle').val(response.product.name);
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
</x-app-layout>
