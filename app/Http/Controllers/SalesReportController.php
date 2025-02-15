<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\ReportSales;
use App\Models\SalesDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class SalesReportController extends Controller
{

    public function index(Request $request)
    {

        //passing all data from sales and detail sales 
        $query =  ReportSales::query();

        //filter by date
        $startDate = $request->input('start_date', now()->subWeek()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        //filter by time
        $startDate = date('Y-m-d 00:00:00', strtotime($startDate));
        $endDate = date('Y-m-d 23:59:59', strtotime($endDate));


        //get the data by created time
        $query->whereBetween('created_at', [$startDate, $endDate]);

        if ($request->has('search') && !empty($request->search)) {
            $query->where('invoice_sales', 'like', '%' . $request->search . '%');
        }

        $data_sales = $query->get();

        return view('admin.reportSales.index', compact('data_sales'));
    }

    //get detail
    public function show($id)
    {
        // Cari data penjualan berdasarkan ID dan load relasi yang diperlukan
        $data_sales = ReportSales::with(['user', 'membership', 'coupon', 'salesDetails.product'])
            ->findOrFail($id);

        // Jika Anda masih ingin memisahkan detail penjualan, Anda bisa mengaksesnya melalui relasi
        $detail_sales = $data_sales->salesDetails;

        return view('admin.reportSales.detailReport', compact('data_sales', 'detail_sales'));
    }

    //get transaction for product
    public function productReport(Request $request)
    {
        $query = SalesDetail::query();

        // Filter by date
        $startDate = $request->input('start_date', now()->subWeek()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $startDate = date('Y-m-d 00:00:00', strtotime($startDate));
        $endDate = date('Y-m-d 23:59:59', strtotime($endDate));

        $query->whereBetween('created_at', [$startDate, $endDate]);

        // Search product name
        if ($request->has('search') && !empty($request->search)) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('product_name', 'like', '%' . $request->search . '%');
            });
        }

        // Ambil data dan sertakan informasi produk
        $data_product = $query->with('product')->get();

        return view('admin.reportSales.productReport', compact('data_product'));
    }

    //export all sales
    public function exportSales(Request $request)
    {
        $data_sales = ReportSales::with(['user', 'membership', 'coupon', 'salesDetails.product'])
            ->orderBy('created_at', 'ASC')
            ->get();

        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();

        $date = now()->format('d-m-Y H:i:s');
        $downloder = 'Downloader : ' . Auth::user()->name;
        $today = 'Date : ' . $date;
        $website_name = 'SiRotan';

        $styleArrayCenterBold = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];
        $styleArrayCenterBold20px = [
            'font' => [
                'bold' => true,
                'size' => 14
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];
        $styleArraycenter = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];

        $activeWorksheet->mergeCells('D1:I1');
        $activeWorksheet->setCellValue('D1', 'DATA SALES');
        $activeWorksheet->getStyle('D1')->applyFromArray($styleArrayCenterBold20px);

        $activeWorksheet->mergeCells('D2:I2');
        $activeWorksheet->setCellValue('D2', $website_name);
        $activeWorksheet->getStyle('D2')->applyFromArray($styleArraycenter);

        $activeWorksheet->mergeCells('D3:I3');
        $activeWorksheet->setCellValue('D3', $today);
        $activeWorksheet->getStyle('D3')->applyFromArray($styleArraycenter);

        $activeWorksheet->mergeCells('D4:I4');
        $activeWorksheet->setCellValue('D4', $downloder);
        $activeWorksheet->getStyle('D4')->applyFromArray($styleArraycenter);

        $activeWorksheet->getStyle('D1:I1')->getFont()->setName('Consolas');
        $activeWorksheet->getStyle('D2:I2')->getFont()->setName('Consolas')->setSize(10);
        $activeWorksheet->getStyle('D3:I3')->getFont()->setName('Consolas')->setSize(10);
        $activeWorksheet->getStyle('D4:I4')->getFont()->setName('Consolas')->setSize(10);

        foreach (range('A', 'L') as $columnID) {
            $activeWorksheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => '5a8ed1',
                ],
                'endColor' => [
                    'argb' => '5a8ed1',
                ],
            ],
        ];

        $activeWorksheet->getStyle('A6:L6')->applyFromArray($styleArray);
        $activeWorksheet->setCellValue('A6', 'INVOICE SALES');
        $activeWorksheet->setCellValue('B6', 'MEMBERSHIP NAME');
        $activeWorksheet->setCellValue('C6', 'TOTAL HARGA PRODUK');
        $activeWorksheet->setCellValue('D6', 'TOTAL DENGAN DISKON');
        $activeWorksheet->setCellValue('E6', 'PAJAK');
        $activeWorksheet->setCellValue('F6', 'TOTAL HARGA AKHIR');
        $activeWorksheet->setCellValue('G6', 'PEMBAYARAN');
        $activeWorksheet->setCellValue('H6', 'KEMBALIAN');
        $activeWorksheet->setCellValue('I6', 'TANGGAL DIBUAT');
        $activeWorksheet->setCellValue('J6', 'PRODUCT NAME');
        $activeWorksheet->setCellValue('K6', 'QUANTITY');
        $activeWorksheet->setCellValue('L6', 'SELLING PRICE');

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_HAIR,
                ],
            ],
        ];

        // $column = 7;
        // $no = 1;
        // foreach ($warehouse as $key => $value) {
        //     $activeWorksheet->setCellValue('A' . $column, $no);
        //     $activeWorksheet->setCellValue('B' . $column, $value->warehouse_name);
        //     $activeWorksheet->setCellValue('C' . $column, $value->warehouse_owner);
        //     $activeWorksheet->setCellValue('D' . $column, $value->warehouse_address);
        //     $activeWorksheet->setCellValue('E' . $column, $value->warehouse_description);

        //     $column++;
        //     $no++;
        // }
        // Initialize counter untuk nomor urut
        $counter = 1;
        $column = 7;
        $startRow = $column;
        foreach ($data_sales as $sale) {
            foreach ($sale->salesDetails as $detail) {
                // Set nomor urut di kolom A
                $activeWorksheet->setCellValue('A' . $column, $counter);
                $activeWorksheet->setCellValue('B' . $column, $sale->membership->name ?? 'Non Member');
                $activeWorksheet->setCellValue('C' . $column, $sale->total_product_price);
                $activeWorksheet->setCellValue('D' . $column, $sale->total_price_discount ?? 'kosong');
                $activeWorksheet->setCellValue('E' . $column, $sale->tax);
                $activeWorksheet->setCellValue('F' . $column, $sale->final_price);
                $activeWorksheet->setCellValue('G' . $column, $sale->cash_received);
                $activeWorksheet->setCellValue('H' . $column, $sale->change);
                $activeWorksheet->setCellValue('I' . $column, $sale->created_at->format('d-m-Y H:i'));
                $activeWorksheet->setCellValue('J' . $column, $detail->product->product_name ?? 'kosong');
                $activeWorksheet->setCellValue('K' . $column, $detail->quantity);
                $activeWorksheet->setCellValue('L' . $column, $detail->selling_price);

                $column++; // Pindah ke baris berikutnya
                $counter++; // Increment nomor urut
            }
        }

        $activeWorksheet->getStyle("A{$startRow}:L" . ($column - 1))->applyFromArray($styleArray);

        $dirPath = 'report/export/';
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        }

        $filename = 'data-sales-' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        $filePath = $dirPath . $filename;

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    //export laporan detail
    public function exportProduct() {}
}
