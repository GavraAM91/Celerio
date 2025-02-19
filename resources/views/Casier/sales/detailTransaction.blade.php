<x-app-layout>

    <div class="container">
        <h2>Transaksi Berhasil</h2>
        <p>Terima kasih! Berikut detail transaksi Anda:</p>

        <ul>
            <li><strong>Invoice:</strong> {{ $data_sales->invoice_sales ?? '-' }}</li>
        </ul>

        <a href="{{ route('sales.pdfReceipt', ['invoice_sales' => $data_sales->invoice_sales]) }}" class="btn btn-primary"
            target="_blank">Cetak Struk</a>

        <a href="{{ route('sales.create') }}" class="btn btn-secondary">Kembali</a>
    </div>
</x-app-layout>
