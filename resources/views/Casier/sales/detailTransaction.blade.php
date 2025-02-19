<x-app-layout>
    <div class="container min-h-screen my-2">
        <div class="card mb-6">
            <div class="card-body">
                <h2>Transaksi Berhasil</h2>
                <p>Terima kasih! Berikut detail transaksi Anda:</p>

                <ul>
                    <li><strong>Invoice : </strong> {{ $data_sales->invoice_sales ?? '-' }}</li>
                    <li>
                        <strong>Total Harga : </strong>
                        Rp {{ number_format($data_sales->final_price, 0, ',', '.') ?? '-' }}
                    </li>
                    <li><strong>Created_at : </strong> {{ $data_sales->created_at ?? '-' }}</li>
                </ul>

                {{-- <a href="{{ route('sales.pdfReceipt', ['invoice_sales' => $data_sales->invoice_sales]) }}"
                    class="btn btn-primary" target="_blank">
                    Cetak Struk
                </a> --}}

                <button type="button" class="btn btn-primary"
                    onclick="window.open('{{ route('sales.pdfReceipt', ['invoice_sales' => $data_sales->invoice_sales]) }}', '_blank')">
                    Cetak Struk
                </button>


                <a href="{{ route('sales.create') }}" class="btn btn-secondary">
                    Kembali
                </a>
            </div>
        </div>
    </div>

</x-app-layout>
