<x-app-layout>
    @push('styles')
        <style>
            #salesChart {
                height: 280px !important;
                /* Sesuaikan dengan kebutuhan */
            }
        </style>
    @endpush

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Layout container -->
            <div class="layout-page">

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="column">
                            <div class="row">
                                <!-- Card Produk -->
                                <div class="col-md-3 col-sm-6 mb-4">
                                    <div class="card h-100 text-center">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-center mb-2">
                                                <span class="material-icons"
                                                    style="font-size: 40px; color: coral">inventory_2</span>
                                            </div>
                                            <p class="mb-0 ms-2 ">Total Produk</p>
                                            <h4 class="card-title mb-0">{{ $product }}</h4>

                                        </div>
                                    </div>
                                </div>

                                <!-- Card Membership -->
                                <div class="col-md-3 col-sm-6 mb-4">
                                    <div class="card h-100 text-center">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-center mb-2">
                                                <span class="material-icons"
                                                    style="font-size: 40px; color: coral">group</span>
                                            </div>
                                            <p class="mb-1">Total Membership</p>
                                            <h4 class="card-title mb-0">{{ $membership }}</h4>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Kategori -->
                                <div class="col-md-3 col-sm-6 mb-4">
                                    <div class="card h-100 text-center">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-center mb-2">
                                                <span class="material-icons"
                                                    style="font-size: 40px; color: coral">category</span>
                                            </div>
                                            <p class="mb-1">Total Kategori</p>
                                            <h4 class="card-title mb-0">{{ $category }}</h4>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6 mb-4">
                                    <div class="card h-100 text-center">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-center mb-2">
                                                <span class="material-icons"
                                                    style="font-size: 40px; color: coral">inventory_2</span>
                                            </div>
                                            <p class="mb-0 ms-2 ">Total Pendapatan</p>
                                            <h4 class="card-title mb-0">{{ $product }}</h4>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-lg-9 col-md-8 col-12 mb-4">
                                <div class="card h-100">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <h5 class="card-title m-0 me-2">Sales in Last 7 Days</h5>
                                    </div>
                                    <div class="card-body pt-4">
                                        <canvas id="salesChart"></canvas>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Expired (1/4) -->
                            <div class="col-lg-3 col-md-4 col-12 mb-4">
                                <div class="card h-100">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <h5 class="card-title m-0 me-2">Product Expired</h5>
                                    </div>
                                    <div class="card-body pt-4">
                                        @if ($expiredSoonProducts->isEmpty())
                                            <div class="text-center">
                                                <div class="d-flex align-items-center justify-content-center mb-2">
                                                    <span class="material-icons"
                                                        style="font-size: 25px; color: coral">pending_actions</span>
                                                    <p>Tidak ada Product Expired</p>
                                                </div>
                                            </div>
                                        @else
                                            <ul class="p-0 m-0">
                                                @foreach ($expiredSoonProducts as $product)
                                                    <li class="d-flex align-items-center mb-3">
                                                        <div class="avatar flex-shrink-0 me-3">
                                                            <img src="{{ asset('storage/Product/product_image/' . $product->product_image) }}"
                                                                alt="Product" class="rounded" />
                                                        </div>
                                                        <div
                                                            class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                            <div class="me-2">
                                                                <small
                                                                    class="d-block">{{ $product->product_name }}</small>
                                                                <h6 class="fw-normal mb-0">Expires:
                                                                    {{ \Carbon\Carbon::parse($product->expired)->format('d M Y') }}
                                                                </h6>
                                                            </div>
                                                            <div class="user-progress d-flex align-items-center gap-2">
                                                                <h6 class="fw-normal mb-0">{{ $product->stock }}
                                                                </h6>
                                                                <span class="text-muted">units</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- / Content -->


                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
            {{-- </div> --}}


            {{-- </div> --}}
            <!-- / Layout wrapper -->
            @push('scripts')
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    const ctx = document.getElementById('salesChart').getContext('2d');

                    const salesData = {
                        labels: {!! json_encode(array_keys($salesByDay)) !!}, // Tanggal dalam 7 hari terakhir
                        datasets: [{
                            label: 'Total Penjualan',
                            data: {!! json_encode(array_values($salesByDay)) !!}, // Jumlah total penjualan per hari
                            borderColor: 'rgba(103,119,239,255)',
                            backgroundColor: 'rgba(103,119,239,0.2)',
                            borderWidth: 2,
                            pointRadius: 5,
                            pointBackgroundColor: 'rgba(103,119,239,255)',
                            pointBorderColor: 'white',
                            pointBorderWidth: 2
                        }]
                    };

                    function formatRupiah(value) {
                        return 'Rp. ' + new Intl.NumberFormat('id-ID').format(value);
                    }

                    const salesChart = new Chart(ctx, {
                        type: 'line',
                        data: salesData,
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: true
                                },
                                tooltip: {
                                    enabled: true,
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            return formatRupiah(tooltipItem.raw); // Format ke Rupiah
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return formatRupiah(value); // Format angka sumbu Y ke Rupiah
                                        }
                                    }
                                }
                            }
                        }
                    });
                </script>

                <script>
                    $(document).ready(function() {
                        function checkExpiredProducts() {
                            $.ajax({
                                url: "{{ route('product.checkExpired') }}",
                                type: "POST",
                                data: {
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function(response) {
                                    console.log("Produk yang diperbarui:", response.updated);
                                },
                                error: function(xhr, status, error) {
                                    console.error("Terjadi kesalahan:", error);
                                }
                            });
                        }

                        // Panggil fungsi saat halaman dimuat
                        checkExpiredProducts();

                        // Set interval untuk menjalankan pengecekan setiap 5 menit
                        setInterval(checkExpiredProducts, 300000); // 300000 ms = 5 menit
                    });
                </script>
            @endpush

</x-app-layout>
