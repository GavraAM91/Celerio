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
        <h2>Products Table</h2>
    </div>
    <div class="mt-4 min-h-screen">
        <div class="card">
            <div class="d-flex justify-content-between align-items-center my-4">
                {{-- <!-- Header Title -->
                <h5 class="card-header mb-0">Product List</h5> --}}

                <div class="d-flex align-items-center gap-3 mx-3 ">
                    <form class="flex-grow-1" method="GET" action="{{ route('product.index') }}">
                        <div class="input-group">
                            <span class="input-group-text"><i class="tf-icons bx bx-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="cari nama produk"
                                value="{{ request('search') }}" />
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>

                    <!-- Dropdown dengan Icon -->
                    <div class="flex-grow-1">
                        <div class="btn-group dropdown w-100 position-relative" id="dropdown-icon-demo">
                            <button type="button" class="btn btn-primary dropdown-toggle w-100"
                                data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                <i class="bx bx-menu me-1"></i> Filter
                            </button>
                            <ul class="dropdown-menu w-100">
                                <li>
                                    <a href="{{ route('product.index') }}"
                                        class="dropdown-item d-flex align-items-center text-danger">
                                        <i class="bx bx-refresh"></i> All Data
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('product.index', ['filter' => 'sold']) }}"
                                        class="dropdown-item d-flex align-items-center">
                                        Sold Product
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('product.index', ['filter' => 'stock']) }}"
                                        class="dropdown-item d-flex align-items-center">
                                        Stock Product
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('product.index', ['filter' => 'expired']) }}"
                                        class="dropdown-item d-flex align-items-center">
                                        Expired Date
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider" />
                                </li>
                                <li>
                                    <a href="{{ route('product.index', ['sort' => 'asc']) }}"
                                        class="dropdown-item d-flex align-items-center">
                                        Ascending
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('product.index', ['sort' => 'desc']) }}"
                                        class="dropdown-item d-flex align-items-center">
                                        Descending
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

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
                            <th>Product Code</th>
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
                                <td>{{ $product->product_code }}</td>
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

                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#modalCenter">
                                                Launch modal
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalCenterTitle">Modal title
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col mb-6">
                                                                    <label for="nameWithTitle"
                                                                        class="form-label">Name</label>
                                                                    <input type="text" id="nameWithTitle"
                                                                        class="form-control"
                                                                        placeholder="Enter Name" />
                                                                </div>
                                                            </div>
                                                            <div class="row g-6">
                                                                <div class="col mb-0">
                                                                    <label for="emailWithTitle"
                                                                        class="form-label">Email</label>
                                                                    <input type="email" id="emailWithTitle"
                                                                        class="form-control"
                                                                        placeholder="xxxx@xxx.xx" />
                                                                </div>
                                                                <div class="col mb-0">
                                                                    <label for="dobWithTitle"
                                                                        class="form-label">DOB</label>
                                                                    <input type="date" id="dobWithTitle"
                                                                        class="form-control" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-secondary"
                                                                data-bs-dismiss="modal">
                                                                Close
                                                            </button>
                                                            <button type="button" class="btn btn-primary">Save
                                                                changes</button>
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
    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            var myModal = new bootstrap.Modal(document.getElementById('modalCenter'));
            myModal.show();

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
    @endpush
</x-app-layout>
