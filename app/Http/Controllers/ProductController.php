<?php

namespace App\Http\Controllers;

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
    public function index() {}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
            'stock' => 'required',
            'access_role' => 'required',
            'created_by' => 'required'
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
        $created_by = Auth::user()->name;

        //created_at
        $created_at = now();

        $data_request = [
            'category_id' => request()->category_id,
            'product_name' => request()->product_name,
            'product_image' => $imageName,
            'product_price' => request()->product_price,
            'stock' => request()->stock,
            'access_role' => request()->access_role,
            'created_by' => $created_by,
            'created_at' => $created_at
        ];

        $data_product = Product::create($data_request);

        if ($data_product) {
            return response()->json([
                'response' => '200',
                'success' => true,
                'message' => 'New Product Added',
            ], 200);
        } else {
            return response()->json([
                'response' => 422,
                'success' => false,
                'message' => 'Failed to Add Product',
            ], 422);
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
    public function edit()
    {
        if (request()->ajax()) {
            $id = request()->id;

            $data_product = Product::find($id);
            $category = CategoryProduct::orderBy('category_name', 'asc')->get();

            return response()->json([
                'message' => 'data for edit',
                'data_product' => $data_product,
                'category' => $category,
            ], 200);
        }
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
            return response()->json([
                'response' => '200',
                'success' => true,
                'message' => 'Update Proposal Success',
            ], 200);
        } else {
            return response()->json([
                'response' => 422,
                'success' => false,
                'message' => 'Failed to Update Product',
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $proposal_data = Product::find($id);
        if ($proposal_data) {
            if ($proposal_data->delete()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data deleted successfully.'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data failed to delete.'
                ], 500);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data not found.'
            ], 400);
        }
    }

    public function addStock(Request $request)
    {
        $validator = FacadesValidator::make($request, [
            'user_id' => 'required',
            'product_id' => 'required',
            'stock' => 'required',
        ]);

        //if fail
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error in input',
                'errors' => $validator->errors()
            ], 400);
        }

        //all inn the data 
        $data_stock = $request->all();

        //check into database 
        $data_stock_db = Product::findOrFail($data_stock['product_id']);

        //update into database 
        $data_stock_db->update($data_stock['stock'], $data_stock['stock']);

        if ($data_stock_db) {
            return response()->json([
                'message' => 'data stock updated',
                'data' => $data_stock_db
            ], 200);
        } else {
            return response()->json([
                'message' => 'data stock error in update',
                'data' => $data_stock_db
            ], 400);
        }
    }
}
