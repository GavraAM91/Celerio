<x-app-layout>
    <div class="mt-4">
        <h2>Laporan Stok</h2>
    </div>
    <div class="mt-4 min-h-screen">
        <div class="card">
            <div class="d-flex justify-content-between align-items-center my-4">
                {{-- <!-- Header Title -->
                <h5 class="card-header mb-0">Product List</h5> --}}

                <div class="d-flex align-items-center gap-3 mx-3 ">
                    <form class="flex-grow-1" method="GET" action="{{ route('sales_report.index') }}">
                        <div class="input-group">
                            <span class="input-group-text"><i class="tf-icons bx bx-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="cari transaksi "
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
                            <ul class="dropdown-menu w-100 p-3">
                                <li>
                                    <a href="{{ route('sales_report.index') }}"
                                        class="dropdown-item d-flex align-items-center text-danger">
                                        <i class="bx bx-refresh"></i> All Data
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
                <!-- Export Data di Pojok Kanan -->
                <div class="d-flex justify-content-end mx-2">
                    <div class="btn-group dropdown">
                        <a class="btn btn-success" href="{{ route('sales_report.exportStockProduct') }}">
                            <i class="bx bx-dots-vertical-rounded"></i> Export
                        </a>
                        {{-- <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Export
                        </button>
                        <ul class="dropdown-menu p-3">
                            <li class="d-flex flex-row gap-3">
                                <a class="btn btn-success" href="{{ route('sales_report.exportLowStockProducts') }}">
                                    <i class="bx bx-dots-vertical-rounded"></i> Export
                                </a>

                                <hr class="dropdown-divider">
                                <a class="btn btn-success" href="{{ route('product.create') }}">
                                    <i class="bx bx-dots-vertical-rounded"></i> Export Filter Tanggal
                                </a>
                            </li>
                        </ul> --}}
                    </div>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Harga Produk</th>
                            <th>Stok Minimal</th>
                            <th>Stok</th>
                            <th>Total Barang Terjual</th>
                            <th>Tanggal Dibuat</th>
                            <th>Expired At</th>
                            {{-- <th>Actions</th> --}}
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($data_stock as $product)
                            <tr>
                                <td>{{ $product->product->product_code }}</td>
                                <td>{{ $product->product->product_name }}</td>
                                <td>Rp {{ number_format($product->product->product_price, 0, ',', '.') }}</td>
                                <td>{{ $product->product->minimum_stock }}</td>
                                <td>{{ $product->stock }}</td>
                                <td>{{ $product->sold_product }}</td>
                                <td>{{ $product->created_at }}</td>
                                <td>{{ $product->expired_at }}</td>
                                {{-- <td>
                                    <a href="{{ route('sales_report.show', $product->id) }}" type="button"
                                        class="btn btn-primary">Detail Transaksi</a>
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @endpush
</x-app-layout>
