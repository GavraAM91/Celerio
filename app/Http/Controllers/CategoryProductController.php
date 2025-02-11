<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class CategoryProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CategoryProduct::query();


        //if has filter
        // if (request()->has('filter')) {
        //     if ($request->filter === 'sold') {
        //         $query->where('sold_product', '>', 0);
        //     } else if ($request->filter === 'stock') {
        //         $query->where('stock', '>', 0);
        //     } else if ($request->filter === 'expired') {
        //         $query->where('expired_at', '<', now());
        //     }
        // }


        //if has sorting type
        if (request()->has('sort')) {
            if ($request->sort === 'asc') {
                $query->orderby('category_name', 'asc');
            } else if ($request->sort === 'desc') {
                $query->orderby('category_name', 'desc');
            }
        }

        //if search
        // Search by Product Name
        if ($request->has('search') && !empty($request->search)) {
            $query->where('category_name', 'like', '%' . $request->search . '%');
        }

        $data_category = $query->get();

        return view('admin.category.index', compact('data_category'), ['title => Product']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.category.create', ['title => Category']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request = request()->all();

        $validator = FacadesValidator::make($request, [
            'category_name' => 'required',
            'access_role' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error in input',
                'errors' => $validator->errors()
            ], 400);
        }

        $data_request = [
            'category_name' => request()->category_name,
            'access_role' => request()->access_role,
        ];

        $data_category = CategoryProduct::create($data_request);

        if ($data_category) {
            return redirect()->route('category.index')->with('success', 'New Product Added Successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to Add Product');
        }
        // if ($data_category) {
        //     return response()->json([
        //         'success' => true,
        //         'message' => 'New Category Added',
        //     ], 200);
        // } else {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Failed to add Category',
        //     ], 422);
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data_category = CategoryProduct::findOrFail($id);

        return response()->json([
            'success' => '200',
            'message' => 'detail data sended',
            'proposal' => $data_category,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data_category = CategoryProduct::findOrFail();

        return view('admin.category.edit', compact('data_category'), ['title' => 'Category edit']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request = request()->all();

        $data_category = CategoryProduct::findOrFail($request['id']);

        $validator = FacadesValidator::make($request, [
            'category_name' => 'required',
            'access_role' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error in input',
                'errors' => $validator->errors()
            ], 400);
        }

        $data_request = [
            'category_name' => request()->category_name,
            'access_role' => request()->access_role
        ];

        //update the data
        $data_category->update($data_request);

        if ($data_category) {
            return redirect()->route('category.index')
                ->with('success', 'Update Product Success!');
        } else {
            return redirect()->route('category.index')
                ->with('error', 'Failed to Update Product!');
        }

        // if ($data_category) {
        //     return response()->json([
        //         'response' => '200',
        //         'success' => true,
        //         'message' => 'Update Category Success',
        //     ], 200);
        // } else {
        //     return response()->json([
        //         'response' => 422,
        //         'success' => false,
        //         'message' => 'Failed to Category Proposal',
        //     ], 422);
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category_data = CategoryProduct::find($id);
        if ($category_data) {
            if ($category_data->delete()) {
                return redirect()->route('category.index')->with('success', 'Delete Product  Successfully!');
            } else {
                return redirect()->back()->with('error', 'Failed to Delete Product');
            }
        } else {
            return redirect()->back()->with('error', 'Data Product Not Found');
        }
    }
}
