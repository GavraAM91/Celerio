<x-app-layout>
    <div class="mt-4">
        <h2>Detail Casier</h2>
    </div>
    <div class="mt-4 min-h-screen">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Detail casier</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Kolom pertama -->
                    <div class="col-md-6">
                        <p><strong>ID Admin:</strong> {{ $casier->id }}</p>
                        <p><strong>Nama:</strong> {{ $casier->name }}</p>
                        <p><strong>Email:</strong> {{ $casier->email }}</p>
                        <p><strong>Tanggal Bergabung:</strong> {{ $casier->created_at->format('d M Y H:i') }}</p>
                    </div>

                    <!-- Kolom kedua -->
                    <div class="col-md-6">
                        <p><strong>Role:</strong> {{ ucfirst($casier->role) }}</p>
                        <p><strong>Status:</strong> {{ $casier->status }}</p>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-secondary my-2" onclick="window.history.back();">Kembali</button>
    </div>



    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @endpush
</x-app-layout>
