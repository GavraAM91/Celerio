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
        <h2>Coupon Table</h2>
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

                <div class="d-flex gap-3 mx-4">
                    <a class="btn btn-danger" href="{{ route('coupon.trashed') }}">Data Terhapus</a>
                    <a class="btn btn-primary" href="{{ route('coupon.create') }}">Tambah Kupon </a>
                </div>

            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Coupon Name</th>
                            <th>Description Name</th>
                            <th>Value Coupon</th>
                            <th>Percentage Coupon</th>
                            <th>Minimum Usage</th>
                            <th>Total Coupon</th>
                            <th>Used Coupon</th>
                            <th>Expired At</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($data_coupon as $coupon)
                            <tr>
                                <td>{{ $coupon->name_coupon }}</td>
                                <td>{{ $coupon->coupon_description }}</td>
                                <td>Rp
                                    {{ $coupon->value_coupon ? number_format($coupon->value_coupon, 0, ',', '.') : 'Kosong' }}
                                </td>
                                <td>{{ $coupon->percentage_coupon ?? 'Kosong' }}</td>
                                <td>{{ $coupon->minimum_usage_coupon }}</td>
                                <td>{{ $coupon->total_coupon }}</td>
                                <td>{{ $coupon->used_coupon }}</td>
                                <td>{{ $coupon->expired_at ?? 'Kosong' }}</td>
                                <td>
                                    <span
                                        class="badge 
                        @if ($coupon->status == 'available') bg-label-success 
                        @elseif ($coupon->status == 'out of stock') bg-label-danger 
                        @elseif ($coupon->status == 'expired') bg-label-warning 
                        @elseif ($coupon->status == 'deleted') bg-label-primary
                        @else bg-label-secondary @endif">
                                        {{ ucfirst($coupon->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('coupon.edit', $coupon->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            <form action="{{ route('coupon.destroy', $coupon->id) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item"
                                                    onclick="return confirm('Are you sure you want to delete this coupon?')">
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
