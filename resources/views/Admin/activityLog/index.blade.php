<x-app-layout>
    <div class="container mt-4">
        <h2>Activity Log</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nama Pengguna</th>
                            <th>Aksi</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logs as $log)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $log->user ? $log->user->name : 'Guest' }}</td>
                                <td>{{ $log->description }}</td>
                                <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y H:i:s') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-secondary" onclick="window.history.back();">Kembali</button>

    </div>
</x-app-layout>
