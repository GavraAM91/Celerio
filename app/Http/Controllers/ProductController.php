<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\UnitOfGoods;
use Illuminate\Support\Str;
use App\Models\StockProduct;
use Illuminate\Http\Request;
use App\Models\CategoryProduct;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $query = Product::query();

        // Eager load relasi stockProducts, unitOfGoods, dan categoryProduct
        $query->with(['stockProducts', 'unitOfGoods', 'categoryProduct']);

        // Filter jika ada
        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'sold':
                    // Filter produk yang memiliki sold_product > 0 pada relasi stockProducts
                    $query->whereHas('stockProducts', function ($q) {
                        $q->where('sold_product', '>', 0);
                    });
                    break;
                case 'stock':
                    // Filter produk yang memiliki stock > 0 pada relasi stockProducts
                    $query->whereHas('stockProducts', function ($q) {
                        $q->where('stock', '>', 0);
                    });
                    break;
                case 'expired':
                    // Filter produk yang memiliki expired_at kurang dari waktu sekarang pada relasi stockProducts
                    $query->whereHas('stockProducts', function ($q) {
                        $q->where('expired_at', '<', now());
                    });
                    break;
            }
        }

        // Sorting berdasarkan nama produk
        if ($request->has('sort')) {
            $order = $request->sort === 'asc' ? 'asc' : 'desc';
            $query->orderBy('product_name', $order);
        }

        // Search by Product Name
        if ($request->has('search') && !empty($request->search)) {
            $query->where('product_name', 'like', '%' . $request->search . '%');
        }

        // Ambil data produk beserta relasinya
        $data_product = $query->get();

        return view('admin.Products.index', compact('data_product'), ['title' => 'Product']);
    }

    public function create()
    {
        // Ambil hanya kolom yang diperlukan (optimasi query)
        $data_category = CategoryProduct::select('id', 'category_name')->get();

        $data_UnitOfGoods = UnitOfGoods::select('id', 'unit')->get();

        // Kirim data ke view
        return view('admin.Products.create', [
            'data_category' => $data_category,
            'data_UnitOfGoods' => $data_UnitOfGoods,
            'title' => 'Product'
        ]);
    }

    public function store(Request $request)
    {
        $request = request()->all();


        // Validasi input
        $validator = FacadesValidator::make($request, [
            'category_id' => 'required',
            'product_name' => 'required',
            'product_image' => 'nullable|mimes:png,jpg,jpeg|max:10240',
            'product_price' => 'required',
            'product_status' => 'required',
            'stock' => 'required',
            'expired_at' => 'required|date_format:Y-m-d\TH:i',
            'minimum_stock' => 'required',
            'access_role' => 'required',
            'unit_id' => 'required|exists:unit_of_goods,id', // Menambahkan validasi untuk unit_id
        ], [
            'product_image.max' => 'The product image must not exceed 10 MB.',
            'product_image.mimes' => 'The product image must be in PNG, JPG, or JPEG format.',
        ]);

        // dd($validator);

        //jika gagal 
        if ($validator->fails()) {
            // Ambil error pertama atau gabungan error
            $errorMessages = $validator->errors()->all();
            $errorString = implode("\n", $errorMessages);

            // Redirect ke route 'product.index' dengan flash session 'alert_error'
            return redirect()->route('product.index')->with('error', $errorString);
        }


        // Initialize file variables
        $imageName = null;

        // Handle proposal image upload
        if (request()->hasFile('product_image')) {
            $product_image = request()->file('product_image');
            $imageName = 'Product_Image_' . date('Y-m-d') . '_' . $product_image->getClientOriginalName();
            $imagePath = 'Product/product_image/' . $imageName;
            Storage::disk('public')->put($imagePath, file_get_contents($product_image));
        }

        //created_by
        $created_by = Auth::user()->username;

        $productCode = 'PRD' . strtoupper(substr(request()->product_name, 0, 4))
            . Str::upper(Str::random(8));

        //created_at
        $created_at = now();


        // Menyiapkan data untuk produk
        $data_request = [
            'category_id' => $request['category_id'],
            'product_code' => $productCode,
            'product_name' => $request['product_name'],
            'product_image' => $imageName,
            'product_price' => $request['product_price'],
            'product_status' => $request['product_status'],
            'expired_at' => $request['expired_at'],
            'minimum_stock' => $request['minimum_stock'],
            'access_role' => $request['access_role'],
            'unit_id' => $request['unit_id'],
            'created_at' => $created_at,
        ];

        // Menyimpan produk baru
        $data_product = Product::create($data_request);

        // Menyimpan data stok
        $data_stock = [
            'product_code' => $productCode,
            'expired_at' => Carbon::parse($request['expired_at'])->format('Y-m-d'),
            'stock' => $request['stock'],
        ];
        // Menambahkan stok ke StockProduct
        $data_stock = StockProduct::create($data_stock);

        // dd($data_stock);

        if ($data_product && $data_stock) {
            activity()
                ->causedBy(Auth::user())
                ->performedOn($data_product)
                ->event('created')
                ->withProperties($data_request)
                ->log("Admin dengan nama " . Auth::user()->name . " menambahkan produk {$data_product->product_name}.");

            return redirect()->route('product.index')->with('success', 'New Product Added Successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to Add Product');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data_product = Product::findOrFail($id);

        return response()->json([
            'success' => '200',
            'message' => 'detail data sended',
            'proposal' => $data_product,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($product_code)
    {
        $data_product = Product::where('product_code', $product_code)->firstOrFail();
        $data_category = CategoryProduct::orderBy('category_name', 'asc')->get();
        $data_unitOfGoods = UnitOfGoods::orderBy('unit', 'asc')->get();
        $data_stock = StockProduct::where('product_code', $product_code)->firstOrFail();

        $data = [
            'data_product' => $data_product,
            'data_category' => $data_category,
            'data_unitOfGoods' => $data_unitOfGoods,
            'data_stock' => $data_stock,
        ];

        return view('admin.Products.edit', compact('data'))->with(['title' => 'Product Edit']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        $request = request()->all();

        // Mencari produk berdasarkan ID
        $data_product = Product::findOrFail($id);

        // Validasi input
        $validator = FacadesValidator::make($request, [
            'category_id' => 'sometimes',
            'product_name' => 'sometimes',
            'product_image' => 'nullable|mimes:png,jpg,jpeg|max:10240',
            'product_price' => 'sometimes',
            'stock' => 'sometimes|integer|min:0',
            'expired_at' => 'sometimes|date_format:Y-m-d\TH:i',
            'minimum_stock' => 'sometimes',
            'access_role' => 'sometimes',
            'product_status' => 'nullable',
            'unit_id' => 'required|exists:unit_of_goods,id',
        ], [
            'product_image.max' => 'The product image must not exceed 10 MB.',
            'product_image.mimes' => 'The product image must be in PNG, JPG, or JPEG format.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('product.index')->with('error', 'Error in input: ' . $validator->errors()->first());
        }

        // Update timestamp
        $updated_at = now();
        $edited_by = Auth::user()->name;

        // Data request untuk produk
        $data_request = [
            'category_id' => request()->category_id,
            'product_name' => request()->product_name,
            'product_price' => request()->product_price,
            'access_role' => request()->access_role,
            'edited_by' => $edited_by,
            'product_status' => request()->product_status,
            'minimum_stock' => request()->minimum_stock,
            'updated_at' => $updated_at,
            'unit_id' => request()->unit_id,
        ];

        // Jika gambar produk diupdate
        if (request()->hasFile('product_image')) {
            $product_image = request()->file('product_image');
            $imageName = 'Product_Image_' . date('Y-m-d') . $product_image->getClientOriginalName();
            $imagePath = 'Product/product_image/' . $imageName;

            // Gambar lama
            $oldFilePath = 'Product/product_image/' . $data_product->product_image;
            $archiveImagePath = 'Product/product_image/product_image_archive/' . $data_product->product_image;

            Storage::disk('public')->put($imagePath, file_get_contents($product_image));

            // Jika gambar lama ada, pindahkan ke arsip
            if (Storage::disk('public')->exists($oldFilePath)) {
                Storage::disk('public')->move($oldFilePath, $archiveImagePath);
            }

            $data_request['product_image'] = $imageName;
        }

        // Cek stok dari database
        $data_stock = StockProduct::where('product_code', $data_product->product_code)->firstOrFail();

        // Jika stok diubah, buat entri baru
        if (isset($request['stock']) && $request['stock'] != $data_stock->stock) {
            $new_product_code = 'PRD' . strtoupper(substr(request()->product_name, 0, 4))
                . Str::upper(Str::random(8));

            $data_request['product_code'] = $new_product_code;
            $data_request['sold_product'] = 0;

            // Buat entri baru di tabel products
            $new_product = Product::create($data_request);

            // Buat entri baru di tabel stock_products
            StockProduct::create([
                'product_code' => $new_product_code,
                'stock' => $request['stock'],
                'expired_at' => $request['expired_at'],
            ]);
        } else {
            // Jika stok tidak diupdate, hanya update data produk yang ada
            $data_product->update($data_request);
        }

        // Log aktivitas
        activity()
            ->causedBy(Auth::user())
            ->performedOn($data_product)
            ->event('updated')
            ->withProperties($data_request)
            ->log("Admin dengan nama " . Auth::user()->name . " edit produk {$data_product->product_name}.");

        return redirect()->route('product.index')->with('success', 'Update Product Success!');
    }


    // Soft Delete Product
    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found');
        }

        if ($product->delete()) {
            activity()
                ->causedBy(Auth::user())
                ->performedOn($product)
                ->event('deleted')
                ->withProperties(['product_name' => $product->product_name])
                ->log("Admin dengan nama " . Auth::user()->name . " menghapus produk {$product->product_name}.");

            return redirect()->route('product.index')->with('success', 'Product deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to delete product');
        }
    }

    // View Trashed Products

    public function trashed()
    {
        $trashedCategories = CategoryProduct::onlyTrashed()->get();
        return view('admin.trashed.category', compact('trashedCategories'));
    }

    // Restore Product
    public function restore($id)
    {
        $product = Product::onlyTrashed()->find($id);
        if ($product) {
            $product->restore();
            activity()
                ->causedBy(Auth::user())
                ->performedOn($product)
                ->event('restored')
                ->withProperties(['product_name' => $product->product_name])
                ->log("Admin dengan nama " . Auth::user()->name . " memulihkan produk {$product->product_name}.");

            return redirect()->route('product.trashed')->with('success', 'Product restored successfully!');
        }
        return redirect()->back()->with('error', 'Product not found');
    }

    // Force Delete (Permanent Delete)
    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->find($id);

        if ($product) {
            $product->forceDelete();
            activity()
                ->causedBy(Auth::user())
                ->performedOn($product)
                ->event('force_deleted')
                ->withProperties(['product_name' => $product->product_name])
                ->log("Admin dengan nama " . Auth::user()->name . " menghapus permanen produk {$product->product_name}.");

            return redirect()->route('product.trashed')->with('success', 'Product permanently deleted!');
        }
        return redirect()->back()->with('error', 'Product not found');
    }

    public function addStockView($product_code)
    {
        $data_product = Product::where('product_code', $product_code)->firstOrFail();
        $data_category = CategoryProduct::orderBy('category_name', 'asc')->get();
        $data_unitOfGoods = UnitOfGoods::orderBy('unit', 'asc')->get();
        $data_stock = StockProduct::where('product_code', $product_code)->firstOrFail();

        $data = [
            'data_product' => $data_product,
            'data_category' => $data_category,
            'data_unitOfGoods' => $data_unitOfGoods,
            'data_stock' => $data_stock,
        ];

        return view('admin.Products.addStock', compact('data'))->with(['title' => 'Product Edit']);
    }


    public function addStock($id)
    {
        $request = request()->all();

        $data_product = Product::findOrFail($id);

        // Validasi input
        $validator = FacadesValidator::make($request, [
            'stock' => 'required|min:1',
            'expired_at' => 'required|date_format:Y-m-d\TH:i',
        ]);
        if ($validator->fails()) {
            return redirect()->route('product.index')->with('error', 'Error in input: ' . $validator->errors()->first());
        }

        $data_stock = StockProduct::findORFail($data_product->product_code);

        $new_product_code = 'PRD' . strtoupper(substr(request()->product_name, 0, 4))
            . Str::upper(Str::random(8));

        $new_product_data = $data_stock->replicate();
        $new_product_data->product_code = $new_product_code;
        $new_product_data->sold_product = 0;
        $new_product_data->save();

        $stock_data = [
            'product_id' => $new_product_data->id,
            'stock' => request()->stock,
            'expired_at' => $request()->expired_at,
        ];

        StockProduct::create($stock_data);

        // Log aktivitas admin yang menambahkan stok
        activity()
            ->causedBy(Auth::user())
            ->performedOn($new_product_data)
            ->event('created')
            ->withProperties(['product_name' => $new_product_data->product_name, 'added_stock' => request()->stock])
            ->log("Admin dengan nama " . Auth::user()->name . " menambahkan stok {request()->stock} untuk produk {$new_product_data->product_name}.");

        // Arahkan kembali ke halaman index dengan pesan sukses
        return redirect()->route('product.index')->with('success', 'Data stock added successfully');
    }

    public function checkAllProducts()
    {
        $lowStockProducts = StockProduct::whereColumn('stock', '<=', 'minimum_stock')->with('product')->get();
        $expiredProducts = StockProduct::whereDate('expired_at', '<', now())
            ->orWhereBetween('expired_at', [now(), now()->addDays(7)])
            ->with('product')
            ->get();

        return response()->json([
            'lowStockProducts' => $lowStockProducts,
            'expiredProducts' => $expiredProducts,
        ]);
    }

    public function updateAllStocks()
    {
        $updatedCount = StockProduct::whereHas('product', function ($query) {
            $query->whereColumn('stock_products.stock', '<=', 'products.minimum_stock');
        })
            ->get()
            ->each(function ($stockProduct) {
                $stockProduct->stock = $stockProduct->product->minimum_stock;
                $stockProduct->save();
            });

        return response()->json([
            'success' => true,
            'message' => count($updatedCount) . " produk diperbarui!"
        ]);
    }

    public function updateAllStatuses()
    {
        // Ambil produk yang sudah expired
        $expiredProducts = StockProduct::where('expired_at', '<', now())->get();
        // Variabel status baru
        $newStatus = 'Expired';

        // Inisialisasi counter untuk jumlah produk yang berhasil diupdate
        $updatedCount = 0;

        // Update setiap produk satu per satu
        foreach ($expiredProducts as $product) {
            // Hanya update jika status belum sesuai
            if ($product->status !== $newStatus) {
                $product->status = $newStatus;
                if ($product->save()) {
                    $updatedCount++;
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => "$updatedCount produk diubah menjadi $newStatus!"
        ]);
    }
}
