<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Struk Kasir</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
        }

        .container {
            width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px dashed #000;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .content table {
            width: 100%;
            border-collapse: collapse;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>SirKasir</h2>
            <p>Tanggal: {{ $created_at }}</p>
            <p>Kasir: {{ $casier_name }}</p>
        </div>
        <hr>
        <div class="content">
            <table>
                <tr>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                </tr>
                @foreach ($sales_detail as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->quantity }}</td>
                        <td style="text-align:right;">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        <hr>
        <div>
            <p><b>Total: Rp {{ number_format($data_sales->total, 0, ',', '.') }}</b></p>

            @if ($discount > 0)
                <p>Diskon: {{ $discount_display }} (-Rp {{ number_format($discount, 0, ',', '.') }})</p>
            @endif

            <p><b>Total Setelah Diskon: Rp {{ number_format($total_after_discount, 0, ',', '.') }}</b></p>
        </div>
    </div>
</body>

</html>
