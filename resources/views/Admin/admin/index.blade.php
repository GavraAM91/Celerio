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
        <h2>Admin Management</h2>
    </div>
    <div class="mt-4 min-h-screen">
        <div class="card">
            <div class="d-flex justify-content-between align-items-center my-4">
                <div class="d-flex align-items-center gap-3 mx-3">
                    <form class="flex-grow-1" method="GET" action="{{ route('user.indexAdmin') }}">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Cari nama admin"
                                value="{{ request('search') }}" />
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>

                    <div class="flex-grow-1">
                        <div class="btn-group dropdown w-100" id="dropdown-sort">
                            <button type="button" class="btn btn-primary dropdown-toggle w-100"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-menu me-1"></i> Sort
                            </button>
                            <ul class="dropdown-menu w-100">
                                <li><a href="{{ route('user.indexAdmin', ['sort' => 'asc']) }}"
                                        class="dropdown-item">Ascending</a></li>
                                <li><a href="{{ route('user.indexAdmin', ['sort' => 'desc']) }}"
                                        class="dropdown-item">Descending</a></li>
                            </ul>
                        </div>
                    </div>
                </div>


                <div class="d-flex gap-3 mx-4">
                    <a class="btn btn-danger" href="{{ route('user.trashed') }}">Data Terhapus</a>
                    <a class="btn btn-primary" href="{{ route('user.createAdmin') }}">Add Admin</a>
                </div>

            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data_admin as $admin)
                            <tr>
                                <td>{{ $admin->name }}</td>
                                <td>{{ $admin->email }}</td>
                                <td><span class="badge bg-label-primary">Admin</span></td>
                                <td>{{ $admin->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('user.edit', $admin->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            <a class="dropdown-item" href="{{ route('user.showAdmin', $admin->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Detail
                                            </a>
                                            <form action="{{ route('user.destroy', $admin->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item"
                                                    onclick="return confirm('Are you sure you want to delete this admin?')">
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
