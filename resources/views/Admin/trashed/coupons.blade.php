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

    <div class="mt-4">
        <h2>Deleted Coupons</h2>
    </div>
    <div class="mt-4 min-h-screen">
        <div class="card">
            <div class="d-flex justify-content-between align-items-center my-4">
                <div class="d-flex align-items-center gap-3 mx-3">
                    <!-- Form Search -->
                    <form class="flex-grow-1" method="GET" action="{{ route('membership.trashed') }}">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Cari nama user"
                                value="{{ request('search') }}" />
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>

                    <!-- Dropdown Sort -->
                    <div class="flex-grow-1">
                        <div class="btn-group dropdown w-100" id="dropdown-sort">
                            <button type="button" class="btn btn-primary dropdown-toggle w-100"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-menu me-1"></i> Sort by Deleted At
                            </button>
                            <ul class="dropdown-menu w-100">
                                <li><a href="{{ route('membership.trashed', ['sort' => 'asc', 'search' => request('search'), 'role' => request('role')]) }}"
                                        class="dropdown-item">Ascending</a></li>
                                <li><a href="{{ route('membership.trashed', ['sort' => 'desc', 'search' => request('search'), 'role' => request('role')]) }}"
                                        class="dropdown-item">Descending</a></li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover mt-3">
                    <thead>
                        <tr>
                            <th>Coupon Name</th>
                            <th>Value Coupon</th>
                            <th>Percentage Coupon</th>
                            <th>Used Coupon</th>
                            <th>Deleted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($trashedCoupons as $coupon)
                            <tr>
                                <td>{{ $coupon->name_coupon }}</td>
                                <td>Rp{{ $coupon->value_coupon ?? 'kosong' }}</td>
                                <td>{{ $coupon->percentage_coupon }}%</td>
                                <td>{{ $coupon->used_coupon }}%</td>
                                <td>{{ $coupon->deleted_at->format('d M Y') }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <form action="{{ route('coupon.restore', $coupon->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="bx bx-refresh me-1"></i> Restore
                                                </button>
                                            </form>
                                            <form action="{{ route('coupon.forceDelete', $coupon->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger"
                                                    onclick="return confirm('Are you sure you want to permanently delete this coupon?')">
                                                    <i class="bx bx-trash me-1"></i> Delete Permanently
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <button type="button" class="btn btn-secondary my-2" onclick="window.history.back();">Kembali</button>
            </div>
        </div>
    </div>
</x-app-layout>
