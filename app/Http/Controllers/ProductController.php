<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\CategoryProduct;
use Illuminate\Routing\Controller;
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

        //if has filter
        if (request()->has('filter')) {
            if ($request->filter === 'sold') {
                $query->where('sold_product', '>', 0);
            } else if ($request->filter === 'stock') {
                $query->where('stock', '>', 0);
            } else if ($request->filter === 'expired') {
                $query->where('expired_at', '<', now());
            }
        }


        //if has sorting type
        if (request()->has('sort')) {
            if ($request->sort === 'asc') {
                $query->orderby('product_name', 'asc');
            } else if ($request->sort === 'desc') {
                $query->orderby('product_name', 'desc');
            }
        }

        //if search
        // Search by Product Name
        if ($request->has('search') && !empty($request->search)) {
            $query->where('product_name', 'like', '%' . $request->search . '%');
        }

        $data_product = $query->get();

        return view('admin.Products.index', compact('data_product'), ['title => Product']);
    }

    /**
     * Display a listing of the resource.
     */

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil hanya kolom yang diperlukan (optimasi query)
        $data_category = CategoryProduct::select('id', 'category_name')->get();

        // Kirim data ke view
        return view('admin.Products.create', [
            'data_category' => $data_category,
            'title' => 'Product'
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request = request()->all();

        $validator = FacadesValidator::make($request, [
            'category_id' => 'required',
            'product_name' => 'required',
            'product_image' => 'nullable|mimes:png,jgp,jpeg|max:10240',
            'product_price' => 'required',
            'product_status' => 'required',
            'stock' => 'required',
            'expired_at' => 'required|date_format:Y-m-d',
            'access_role' => 'required',
        ], [
            'product_image.max' => 'The product image must not exceed 10 MB.',
            'product_image.mimes' => 'The product image must be in PNG, JPG, or JPEG format.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error in input',
                'errors' => $validator->errors()
            ], 400);
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


        //productCode
        $productCode = 'PRD' . strtoupper(substr(request()->product_name, 0, 4)) . now()->format('Ymd');

        //created_at
        $created_at = now();

        $data_request = [
            'category_id' => request()->category_id,
            'product_code' => $productCode,
            'product_name' => request()->product_name,
            'product_image' => $imageName,
            'product_price' => request()->product_price,
            'product_status' => request()->product_status,
            'expired_at' => request()->expired_at,
            'stock' => request()->stock,
            'access_role' => request()->access_role,
            'created_by' => $created_by,
            'created_at' => $created_at
        ];

        $data_product = Product::create($data_request);

        activity()->log(Auth::user()->name . 'has add product');

        if ($data_product) {
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
    public function edit($id)
    {

        $data_product = Product::find($id);

        $data_category = CategoryProduct::orderBy('category_name', 'asc')->get();

        return view('admin.Products.edit', compact('data_product', 'data_category'), ['title' => 'Product Edit']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        $request = request()->all();

        $data_product = Product::findOrFail($id);

        $validator = FacadesValidator::make($request, [
            'category_id' => 'required',
            'product_name' => 'required',
            'product_image' => 'nullable|mimes:png,jgp,jpeg|max:10240',
            'product_price' => 'required',
            'stock' => 'required',
            'access_role' => 'required',
            // 'expired_at' => 'required|date_format:Y-m-d',
            'product_status' => 'nullable '
        ], [
            'product_image.max' => 'The product image must not exceed 10 MB.',
            'product_image.mimes' => 'The product image must be in PNG, JPG, or JPEG format.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error in input',
                'errors' => $validator->errors()
            ], 400);
        }

        //update the update at
        $updated_at = now();

        //who's update the data 
        $edited_by = Auth::user()->name;

        $data_request = [
            'category_id' => request()->category_id,
            'product_name' => request()->product_name,
            'product_price' => request()->product_price,
            'stock' => request()->stock,
            'access_role' => request()->access_role,
            'edited_by' => $edited_by,
            'product_status' => request()->product_status,
            'expired_date' => request()->expired_date,
            'updated_at' => $updated_at
        ];

        //jika data mengandung files atau gambar    
        if (request()->hasFile('product_image')) {

            //new image
            $product_image = request()->file('product_image');
            $imageName = 'Product_Image_' . date('Y-m-d') . $product_image->getClientOriginalName();
            $imagePath = 'Product/product_image/' . $imageName;

            //old image 
            $oldImageName = 'Archive_' . $data_product->product_image;
            $oldFilePath = 'Product/product_image/' . $oldImageName;
            $archiveImagePath = 'Product/product_image/product_image_archive/' . $data_product->product_image;

            //upload new image
            Storage::disk('public')->put($imagePath, file_get_contents($product_image));

            // if old file exist move to archive
            if (Storage::disk('public')->exists($oldFilePath)) {
                Storage::disk('public')->move($oldFilePath, $archiveImagePath);
            }
        }

        //update the data
        $data_product->update($data_request);

        if ($data_product) {
            activity()
                ->causedBy(Auth::user())
                ->performedOn($data_product)
                ->event('updated')
                ->withProperties($data_request)
                ->log("Admin dengan nama " . Auth::user()->name . " edit produk {$data_product->product_name}.");

            return redirect()->route('product.index')
                ->with('success', 'Update Product Success!');
        } else {
            return redirect()->route('product.index')
                ->with('error', 'Failed to Update Product!');
        }
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
        $trashedProducts = Product::onlyTrashed()->get();
        return view('admin.trashed.product', compact('trashedProducts'));
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

    public function addStock(Request $request)
    {
        $validator = FacadesValidator::make($request, [
            'user_id' => 'required',
            'product_id' => 'required',
            'stock' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error in input',
                'errors' => $validator->errors()
            ], 400);
        }

        $data_stock = $request->all();
        $data_stock_db = Product::findOrFail($data_stock['product_id']);

        $data_stock_db->increment('stock', $data_stock['stock']);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($data_stock_db)
            ->event('updated')
            ->withProperties(['product_name' => $data_stock_db->product_name, 'added_stock' => $data_stock['stock']])
            ->log("Admin dengan nama " . Auth::user()->name . " menambahkan stok {$data_stock['stock']} untuk produk {$data_stock_db->product_name}.");

        return response()->json([
            'message' => 'data stock updated',
            'data' => $data_stock_db
        ], 200);
    }

    public function checkExpiredProducts(Request $request)
    {
        $now = Carbon::now();

        // Update status produk yang sudah expired
        $expiredProducts = Product::where('expired_at', '<', $now)
            ->where('product_status', '!=', 'expired')
            ->update(['product_status' => 'expired']);

        return response()->json(['success' => true, 'updated' => $expiredProducts]);
    }
}
