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
            margin-bottom: 10px;
        }

        .content table {
            width: 100%;
            border-collapse: collapse;
        }

        .content th,
        .content td {
            padding: 5px;
            border-bottom: 1px solid #ddd;
        }

        .summary {
            margin-top: 10px;
        }

        .line {
            border-top: 1px solid black;
            margin: 10px 0;
        }

        .text-right {
            text-align: right;
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

        <div class="content">
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th class="text-right">Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sales_detail as $product)
                        <tr>
                            <td>{{ $product->product_name ?? 'Produk Tidak Diketahui' }}</td>
                            <td class="text-right">{{ $product->quantity ?? 0 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="line"></div>

        <div class="summary">
            <p><b>Total Harga Produk: </b>Rp {{ number_format($data_sales->total_product_price, 0, ',', '.') }}</p>
            <p><b>Pajak (12%): </b>
                {{ $data_sales->tax * 100 }}%</p>
        </div>

        <div class="line"></div>

        <div class="summary">
            <p><b>Membership: </b>{{ $membership_name }}</p>
            <p><b>Points: </b>{{ $membership_points }}</p>
        </div>

        <div class="line"></div>

        <div class="summary">
            <p><b>Kupon / Diskon: </b>{{ $discount_display }}</p>
            <p><b>Harga Setelah Kupon: </b>Rp {{ number_format($data_sales->total_price_discount, 0, ',', '.') }}</p>
            <p><b>Total Final: </b>Rp {{ number_format($data_sales->final_price, 0, ',', '.') }}</p>
        </div>

        <div class="line"></div>

        <div class="summary">
            <p><b>Uang yang Dimasukkan: </b>Rp {{ number_format($data_sales->cash_received, 0, ',', '.') }}</p>
            <p><b>Kembalian: </b>Rp {{ number_format($data_sales->change, 0, ',', '.') }}</p>
        </div>
    </div>
</body>

</html>
