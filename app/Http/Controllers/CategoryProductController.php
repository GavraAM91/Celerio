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
    public function index()
    {
        $category_data = CategoryProduct::all();

        if ($category_data) {
            return response()->json([
                'message' => 'all data sended',
                'category_data' => $category_data
            ], 200);
        } else {
            return response()->json([
                'message' => 'zero data in db',
            ], 422);
        }
    }

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
            return response()->json([
                'success' => true,
                'message' => 'New Category Added',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add Category',
            ], 422);
        }
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
    public function edit(CategoryProduct $categoryProduct)
    {
        //
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
            return response()->json([
                'response' => '200',
                'success' => true,
                'message' => 'Update Category Success',
            ], 200);
        } else {
            return response()->json([
                'response' => 422,
                'success' => false,
                'message' => 'Failed to Category Proposal',
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category_dataa = CategoryProduct::find($id);
        if ($category_dataa) {
            if ($category_dataa->delete()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Category deleted successfully.'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Category failed to delete.'
                ], 422);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data not found.'
            ], 400);
        }
    }
}
