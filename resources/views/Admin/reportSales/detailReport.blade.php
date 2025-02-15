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
        <h2>Laporan Transaksi Detail</h2>
    </div>
    <div class="mt-4 min-h-screen">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Detail Penjualan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Kolom pertama -->
                    <div class="col-md-6">
                        <p><strong>Invoice:</strong> {{ $data_sales->invoice_sales }}</p>
                        <p><strong>Tanggal Transaksi:</strong> {{ $data_sales->created_at->format('d M Y H:i') }}</p>
                        <p><strong>Pelanggan:</strong> {{ $data_sales->membership_name }}</p>
                        <p><strong>Petugas:</strong> {{ $data_sales->user->name }}</p>
                        <p><strong>Poin Member:</strong> {{ $data_sales->membership->point ?? 'N/A' }}</p>
                    </div>

                    <!-- Kolom kedua -->
                    <div class="col-md-6">
                        <p><strong>Nama Kupon:</strong> {{ $data_sales->coupon->name_coupon ?? 'N/A' }}</p>

                        <p><strong>Total Harga dengan Diskon:</strong> Rp
                            {{ number_format($data_sales->total_price_discount, 0, ',', '.') }}</p>
                        <p><strong>Harga Akhir:</strong> Rp {{ number_format($data_sales->final_price, 0, ',', '.') }}
                        </p>
                        <p><strong>Uang Dibayar:</strong> Rp
                            {{ number_format($data_sales->cash_received, 0, ',', '.') }}</p>
                        <p><strong>Kembalian:</strong> Rp {{ number_format($data_sales->change, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>


        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title">Detail Produk</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Nama Produk</th>
                            <th>Quantity</th>
                            <th>Selling Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detail_sales as $detail)
                            <tr>
                                <td>{{ $detail->product_id }}</td>
                                <td>{{ $detail->product->product_name }}</td>
                                <td>{{ $detail->quantity }}</td>
                                <td>Rp {{ number_format($detail->selling_price, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <button type="button" class="btn btn-secondary my-2" onclick="window.history.back();">Kembali</button>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @endpush
</x-app-layout>
