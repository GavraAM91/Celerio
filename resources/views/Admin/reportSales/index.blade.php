
<x-app-layout>
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
                                <a class="btn btn-success" href="{{ route('sales_report.exportSales') }}">
                                    <i class="bx bx-dots-vertical-rounded"></i> Export All
                                </a>

                                <hr class="dropdown-divider">
                                <a class="btn btn-success" href="{{ route('product.create') }}">
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
                            <th>Pelanggan</th>
                            <th>Total Harga Produk</th>
                            <th>Total dengan Diskon</th>
                            <th>Pajak</th>
                            <th>Total Harga Akhir</th>
                            <th>Pembayaran</th>
                            <th>Kembalian</th>
                            <th>Dibuat</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($data_sales as $transaction)
                            <tr>
                                <td>{{ $transaction->invoice_sales }}</td>
                                <td>{{ $transaction->membership_name }}</td>
                                <td>Rp {{ number_format($transaction->total_product_price, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($transaction->total_price_discount, 0, ',', '.') }}</td>
                                <td>{{ $transaction->tax }}</td>
                                <td>Rp {{ number_format($transaction->final_price, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($transaction->cash_received, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($transaction->change, 0, ',', '.') }}</td>
                                <td>{{ $transaction->created_at }}</td>
                                <td>
                                    <a href="{{ route('sales_report.show', $transaction->id) }}" type="button"
                                        class="btn btn-primary">Detail Transaksi</a>
                                    {{-- <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>

                                    </div> --}}
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
    @endpush
</x-app-layout>
