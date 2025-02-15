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

    //export laporan 
    public function exportSales()
    {
        // $exportSales = WarehouseModel::orderBy('warehouse_name', 'ASC')->get();

        // $spreadsheet = new Spreadsheet();
        // $activeWorksheet = $spreadsheet->getActiveSheet();

        // $date = date('d-m-Y - H:i');
        // $downloder = 'Downloader : ' . Auth::user()->name;
        // $today = 'Date : ' . $date;
        // $website_name = 'SiRotan';

        // $styleArrayCenterBold = [
        //     'font' => [
        //         'bold' => true,
        //     ],
        //     'alignment' => [
        //         'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        //     ],
        // ];
        // $styleArrayCenterBold20px = [
        //     'font' => [
        //         'bold' => true,
        //         'size' => 14
        //     ],
        //     'alignment' => [
        //         'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        //     ],
        // ];
        // $styleArraycenter = [
        //     'alignment' => [
        //         'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        //         'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        //     ],
        // ];

        // $activeWorksheet->mergeCells('A1:E1');
        // $activeWorksheet->setCellValue('A1', 'DATA WAREHOUSE');
        // $activeWorksheet->getStyle('A1')->applyFromArray($styleArrayCenterBold20px);

        // $activeWorksheet->mergeCells('A2:E2');
        // $activeWorksheet->setCellValue('A2', $website_name);
        // $activeWorksheet->getStyle('A2')->applyFromArray($styleArraycenter);

        // $activeWorksheet->mergeCells('A3:E3');
        // $activeWorksheet->setCellValue('A3', $today);
        // $activeWorksheet->getStyle('A3')->applyFromArray($styleArraycenter);

        // $activeWorksheet->mergeCells('A4:E4');
        // $activeWorksheet->setCellValue('A4', $downloder);
        // $activeWorksheet->getStyle('A4')->applyFromArray($styleArraycenter);

        // $activeWorksheet->getStyle('A1:E1')->getFont()->setName('Consolas');
        // $activeWorksheet->getStyle('A2:E2')->getFont()->setName('Consolas')->setSize(10);
        // $activeWorksheet->getStyle('A3:E3')->getFont()->setName('Consolas')->setSize(10);
        // $activeWorksheet->getStyle('A4:E4')->getFont()->setName('Consolas')->setSize(10);

        // foreach (range('A', 'E') as $columnID) {
        //     $activeWorksheet->getColumnDimension($columnID)
        //         ->setAutoSize(true);
        // }

        // $styleArray = [
        //     'font' => [
        //         'bold' => true,
        //     ],
        //     'alignment' => [
        //         'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        //     ],
        //     'borders' => [
        //         'allBorders' => [
        //             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        //         ],
        //     ],
        //     'fill' => [
        //         'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        //         'startColor' => [
        //             'argb' => '5a8ed1',
        //         ],
        //         'endColor' => [
        //             'argb' => '5a8ed1',
        //         ],
        //     ],
        // ];

        // $activeWorksheet->getStyle('A6:E6')->applyFromArray($styleArray);
        // $activeWorksheet->setCellValue('A6', 'NO');
        // $activeWorksheet->setCellValue('B6', 'WAREHOUSE NAME');
        // $activeWorksheet->setCellValue('C6', 'WAREHOUSE OWNER');
        // $activeWorksheet->setCellValue('D6', 'WAREHOUSE ADDRESS');
        // $activeWorksheet->setCellValue('E6', 'WAREHOUSE DESCRIPTION');

        // $styleArray = [
        //     'borders' => [
        //         'allBorders' => [
        //             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        //         ],
        //     ],
        // ];

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

        // $dirPath = 'report/export/';
        // if (!is_dir($dirPath)) {
        //     mkdir($dirPath, 0777, true);
        // }

        // $filename = 'data-warehouse_' . date('d-m-y-H-i-s') . '.xlsx';
        // $filePath = $dirPath . $filename;

        // $writer = new Xlsx($spreadsheet);
        // $writer->save($filePath);

        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment;filename="' . $filename . '"');
        // header('Cache-Control: max-age=0');
        // $writer->save('php://output');
        // exit();
    }

    //export laporan detail
    public function exportProduct() {}
}
