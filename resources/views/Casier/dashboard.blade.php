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
    
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Layout container -->
            <div class="layout-page">


                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="row">
                            <div class="col-xxl-8 mb-6 order-0">
                                <div class="card">
                                    <div class="d-flex align-items-start row">
                                        <div class="col-sm-7">
                                            <div class="card-body">
                                                @php
                                                    $hour = now()->hour;
                                                    if ($hour >= 5 && $hour < 12) {
                                                        $greeting = 'Selamat Pagi';
                                                    } elseif ($hour >= 12 && $hour < 18) {
                                                        $greeting = 'Selamat Siang';
                                                    } else {
                                                        $greeting = 'Selamat Malam';
                                                    }
                                                @endphp

                                                <h5 class="card-title text-primary mb-3">{{ $greeting }},
                                                    {{ Auth::user()->name }}! 🎉</h5>
                                                <p class="mb-6">
                                                    Semoga harimu menyenangkan dan penuh keberhasilan! 🚀
                                                </p>

                                                <a href="{{ route('sales.create') }}"
                                                    class="btn btn-sm btn-outline-primary">Mulai Transaksi!</a>
                                            </div>
                                        </div>
                                        <div class="col-sm-5 text-center text-sm-left">
                                            <div class="card-body pb-0 px-0 px-md-6">
                                                <img src="{{ asset('template/assets/img/illustrations/man-with-laptop.png') }}"
                                                    height="175" class="scaleX-n1-rtl" alt="View Badge User">
                                            </div>
                                        </div>
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
</x-app-layout>
