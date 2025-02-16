<x-app-layout>
    {{-- @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif --}}

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
        <h2>Membership Table</h2>
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
                    <a class="btn btn-danger" href="{{ route('membership.trashed') }}">Data Terhapus</a>
                    <a class="btn btn-primary" href="{{ route('membership.createAdmin') }}">TambahMembership</a>
                </div>

            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Membership Code</th>
                            <th>Name</th>
                            <th>username</th>
                            <th>email</th>
                            <th>point</th>
                            <th>type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($membership_data as $membership)
                            <tr>
                                <td>{{ $membership->membership_code }}</td>
                                <td>{{ $membership->name }}</td>
                                <td>{{ $membership->username }}</td>
                                <td>{{ $membership->email }}</td>
                                <td>{{ $membership->point }}</td>
                                <td>{{ $membership->type }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('membership.edit', $membership->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>


                                            <form action="{{ route('membership.destroy', $membership->id) }}"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item"
                                                    onclick="return confirm('Are you sure you want to delete this membership ?')">
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.btn-info').click(function() {
                let id = $(this).data('id'); // Ambil ID dari tombol

                $.ajax({
                    url: `{{ url('membership') }}/${id}`
                    success: function(response) {
                        if (response.success == "200") {
                            // Masukkan data ke dalam modal
                            $('#nameWithTitle').val(response.membership.name);
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
</x-app-layout>
