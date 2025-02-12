<x-app-layout>

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                {{-- <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                            <i class="bx bx-menu bx-md"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <!-- Search -->
                        <div class="navbar-nav align-items-center">
                            <div class="nav-item d-flex align-items-center">
                                <i class="bx bx-search bx-md"></i>
                                <input type="text" class="form-control border-0 shadow-none ps-1 ps-sm-2"
                                    placeholder="Search..." aria-label="Search..." />
                            </div>
                        </div>
                        <!-- /Search -->

                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- Place this tag where you want the button to render. -->
                            <li class="nav-item lh-1 me-4">
                                <a class="github-button"
                                    href="https://github.com/themeselection/sneat-html-admin-template-free"
                                    data-icon="octicon-star" data-size="large" data-show-count="true"
                                    aria-label="Star themeselection/sneat-html-admin-template-free on GitHub">Star</a>
                            </li>

                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="../assets/img/avatars/1.png" alt
                                            class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="../assets/img/avatars/1.png" alt
                                                            class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0">John Doe</h6>
                                                    <small class="text-muted">Admin</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider my-1"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="bx bx-user bx-md me-3"></i><span>My Profile</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#"> <i
                                                class="bx bx-cog bx-md me-3"></i><span>Settings</span> </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <span class="d-flex align-items-center align-middle">
                                                <i class="flex-shrink-0 bx bx-credit-card bx-md me-3"></i><span
                                                    class="flex-grow-1 align-middle">Billing Plan</span>
                                                <span class="flex-shrink-0 badge rounded-pill bg-danger">4</span>
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider my-1"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);">
                                            <i class="bx bx-power-off bx-md me-3"></i><span>Log Out</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </nav> --}}

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="row">
                            <!-- Order Statistics -->
                            <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-6">
                                <div class="card h-100">
                                    <div class="card-header d-flex justify-content-between">
                                        <div class="card-title mb-0">
                                            <h5 class="mb-1 me-2">Order Statistics</h5>
                                            <p class="card-subtitle">42.82k Total Sales</p>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn text-muted p-0" type="button" id="orederStatistics"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded bx-lg"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end"
                                                aria-labelledby="orederStatistics">
                                                <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                                                <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                                <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-6">
                                            <div class="d-flex flex-column align-items-center gap-1">
                                                <h3 class="mb-1">8,258</h3>
                                                <small>Total Orders</small>
                                            </div>
                                            <div id="orderStatisticsChart"></div>
                                        </div>
                                        <ul class="p-0 m-0">
                                            <li class="d-flex align-items-center mb-5">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <span class="avatar-initial rounded bg-label-primary"><i
                                                            class="bx bx-mobile-alt"></i></span>
                                                </div>
                                                <div
                                                    class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <h6 class="mb-0">Electronic</h6>
                                                        <small>Mobile, Earbuds, TV</small>
                                                    </div>
                                                    <div class="user-progress">
                                                        <h6 class="mb-0">82.5k</h6>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="d-flex align-items-center mb-5">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <span class="avatar-initial rounded bg-label-success"><i
                                                            class="bx bx-closet"></i></span>
                                                </div>
                                                <div
                                                    class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <h6 class="mb-0">Fashion</h6>
                                                        <small>T-shirt, Jeans, Shoes</small>
                                                    </div>
                                                    <div class="user-progress">
                                                        <h6 class="mb-0">23.8k</h6>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="d-flex align-items-center mb-5">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <span class="avatar-initial rounded bg-label-info"><i
                                                            class="bx bx-home-alt"></i></span>
                                                </div>
                                                <div
                                                    class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <h6 class="mb-0">Decor</h6>
                                                        <small>Fine Art, Dining</small>
                                                    </div>
                                                    <div class="user-progress">
                                                        <h6 class="mb-0">849k</h6>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="d-flex align-items-center">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <span class="avatar-initial rounded bg-label-secondary"><i
                                                            class="bx bx-football"></i></span>
                                                </div>
                                                <div
                                                    class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <h6 class="mb-0">Sports</h6>
                                                        <small>Football, Cricket Kit</small>
                                                    </div>
                                                    <div class="user-progress">
                                                        <h6 class="mb-0">99</h6>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!--/ Order Statistics -->

                            <!-- Expense Overview -->
                            <div class="col-md-6 col-lg-4 order-1 mb-6">
                                <div class="card h-100">
                                    <div class="card-header nav-align-top">
                                        <ul class="nav nav-pills" role="tablist">
                                            <li class="nav-item">
                                                <button type="button" class="nav-link active" role="tab"
                                                    data-bs-toggle="tab" data-bs-target="#navs-tabs-line-card-income"
                                                    aria-controls="navs-tabs-line-card-income" aria-selected="true">
                                                    Income
                                                </button>
                                            </li>
                                            <li class="nav-item">
                                                <button type="button" class="nav-link" role="tab">Expenses</button>
                                            </li>
                                            <li class="nav-item">
                                                <button type="button" class="nav-link" role="tab">Profit</button>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content p-0">
                                            <div class="tab-pane fade show active" id="navs-tabs-line-card-income"
                                                role="tabpanel">
                                                <div class="d-flex mb-6">
                                                    <div class="avatar flex-shrink-0 me-3">
                                                        <img src="../assets/img/icons/unicons/wallet.png"
                                                            alt="User" />
                                                    </div>
                                                    <div>
                                                        <p class="mb-0">Total Balance</p>
                                                        <div class="d-flex align-items-center">
                                                            <h6 class="mb-0 me-1">$459.10</h6>
                                                            <small class="text-success fw-medium">
                                                                <i class="bx bx-chevron-up bx-lg"></i>
                                                                42.9%
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="incomeChart"></div>
                                                <div
                                                    class="d-flex align-items-center justify-content-center mt-6 gap-3">
                                                    <div class="flex-shrink-0">
                                                        <div id="expensesOfWeek"></div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">Income this week</h6>
                                                        <small>$39k less than last week</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/ Expense Overview -->

                            <!-- Transactions -->
                            <div class="col-md-6 col-lg-4 order-2 mb-6">
                                <div class="card h-100">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <h5 class="card-title m-0 me-2">Transactions</h5>
                                        <div class="dropdown">
                                            <button class="btn text-muted p-0" type="button" id="transactionID"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded bx-lg"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end"
                                                aria-labelledby="transactionID">
                                                <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                                                <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                                                <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body pt-4">
                                        <ul class="p-0 m-0">
                                            <li class="d-flex align-items-center mb-6">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <img src="../assets/img/icons/unicons/paypal.png" alt="User"
                                                        class="rounded" />
                                                </div>
                                                <div
                                                    class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <small class="d-block">Paypal</small>
                                                        <h6 class="fw-normal mb-0">Send money</h6>
                                                    </div>
                                                    <div class="user-progress d-flex align-items-center gap-2">
                                                        <h6 class="fw-normal mb-0">+82.6</h6>
                                                        <span class="text-muted">USD</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="d-flex align-items-center mb-6">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <img src="../assets/img/icons/unicons/wallet.png" alt="User"
                                                        class="rounded" />
                                                </div>
                                                <div
                                                    class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <small class="d-block">Wallet</small>
                                                        <h6 class="fw-normal mb-0">Mac'D</h6>
                                                    </div>
                                                    <div class="user-progress d-flex align-items-center gap-2">
                                                        <h6 class="fw-normal mb-0">+270.69</h6>
                                                        <span class="text-muted">USD</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="d-flex align-items-center mb-6">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <img src="../assets/img/icons/unicons/chart.png" alt="User"
                                                        class="rounded" />
                                                </div>
                                                <div
                                                    class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <small class="d-block">Transfer</small>
                                                        <h6 class="fw-normal mb-0">Refund</h6>
                                                    </div>
                                                    <div class="user-progress d-flex align-items-center gap-2">
                                                        <h6 class="fw-normal mb-0">+637.91</h6>
                                                        <span class="text-muted">USD</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="d-flex align-items-center mb-6">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <img src="../assets/img/icons/unicons/cc-primary.png"
                                                        alt="User" class="rounded" />
                                                </div>
                                                <div
                                                    class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <small class="d-block">Credit Card</small>
                                                        <h6 class="fw-normal mb-0">Ordered Food</h6>
                                                    </div>
                                                    <div class="user-progress d-flex align-items-center gap-2">
                                                        <h6 class="fw-normal mb-0">-838.71</h6>
                                                        <span class="text-muted">USD</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="d-flex align-items-center mb-6">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <img src="../assets/img/icons/unicons/wallet.png" alt="User"
                                                        class="rounded" />
                                                </div>
                                                <div
                                                    class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <small class="d-block">Wallet</small>
                                                        <h6 class="fw-normal mb-0">Starbucks</h6>
                                                    </div>
                                                    <div class="user-progress d-flex align-items-center gap-2">
                                                        <h6 class="fw-normal mb-0">+203.33</h6>
                                                        <span class="text-muted">USD</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="d-flex align-items-center">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <img src="../assets/img/icons/unicons/cc-warning.png"
                                                        alt="User" class="rounded" />
                                                </div>
                                                <div
                                                    class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <small class="d-block">Mastercard</small>
                                                        <h6 class="fw-normal mb-0">Ordered Food</h6>
                                                    </div>
                                                    <div class="user-progress d-flex align-items-center gap-2">
                                                        <h6 class="fw-normal mb-0">-92.45</h6>
                                                        <span class="text-muted">USD</span>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!--/ Transactions -->
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

            <div class="buy-now">
                <a href="https://themeselection.com/item/sneat-dashboard-pro-bootstrap/" target="_blank"
                    class="btn btn-danger btn-buy-now">Upgrade to Pro</a>
            </div>
</x-app-layout>
