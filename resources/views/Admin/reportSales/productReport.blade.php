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
        <h2>Laporan Transaksi</h2>
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
                                <!-- Form Filter Tanggal -->
                                <li>
                                    <form method="GET" action="{{ route('sales_report.index') }}" class="px-3 py-2">
                                        <div class="mb-2">
                                            <label for="start_date" class="form-label">Tanggal Mulai</label>
                                            <input type="date" class="form-control" id="start_date"
                                                name="start_date">
                                        </div>
                                        <div class="mb-2">
                                            <label for="end_date" class="form-label">Tanggal Selesai</label>
                                            <input type="date" class="form-control" id="end_date" name="end_date">
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100">Search</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>

                <!-- Export Data di Pojok Kanan -->
                <div class="d-flex justify-content-end mx-2">
                    <div class="btn-group dropdown">
                        <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Export
                        </button>
                        <ul class="dropdown-menu p-3">
                            <li class="d-flex flex-row gap-3">
                                <a class="btn btn-success" href="{{ route('product.create') }}">
                                    <i class="bx bx-dots-vertical-rounded"></i> Export All
                                </a>
                                <hr class="dropdown-divider">
                                <a class="btn btn-successz" href="{{ route('product.create') }}">
                                    <i class="bx bx-dots-vertical-rounded"></i> Export Filter Tanggal
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>


            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Invoice Sales</th>
                            <th>Nama Barang</th>
                            <th>Jumlah Barang Terjual</th>
                            <th>Total Harga </th>
                            <th>Dibuat</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($data_product as $transaction_product)
                            <tr>
                                <td>{{ $transaction_product->invoice_sales }}</td>
                                <td>{{ $transaction_product->product->product_name }}</td>
                                <td>{{ $transaction_product->quantity }}</td>
                                <td>Rp {{ number_format($transaction_product->selling_price, 0, ',', '.') }}</td>
                                <td>{{ $transaction_product->created_at }}</td>
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
