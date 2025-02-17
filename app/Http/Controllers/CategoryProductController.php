<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoryProduct;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
        $validator = Validator::make($request->all(), [
            'category_name' => 'required',
            'access_role' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error in input',
                'errors' => $validator->errors()
            ], 400);
        }

        $data_category = CategoryProduct::create([
            'category_name' => $request->category_name,
            'access_role' => $request->access_role,
        ]);

        if ($data_category) {
            // Logging activity using Spatie
            activity()
                ->causedBy(Auth::user())
                ->performedOn($data_category)
                ->event('created')
                ->withProperties(['category_name' => $data_category->category_name])
                ->log('Admin dengan nama [' . Auth::user()->name . '] menambahkan kategori [' . $data_category->category_name . '].');

            return redirect()->route('category.index')->with('success', 'New Category Added Successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to Add Category');
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
    public function edit($id)
    {
        $data_category = CategoryProduct::findOrFail($id);

        return view('admin.category.edit', compact('data_category'), ['title' => 'Category edit']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = FacadesValidator::make($request->all(), [
            'category_name' => 'required',
            'access_role' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $category = CategoryProduct::findOrFail($id);

        $oldData = $category->getOriginal(); // Data sebelum diubah

        $category->update([
            'category_name' => $request->category_name,
            'access_role' => $request->access_role,
        ]);

        // Tambahkan activity log
        activity()
            ->performedOn($category)
            ->causedBy(Auth::user())
            ->withProperties([
                'old' => $oldData,
                'new' => $category->toArray()
            ])
            ->event('created')
            ->log('Updated Category: ' . $category->category_name);

        return redirect()->route('category.index')->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category_data = CategoryProduct::find($id);

        if (!$category_data) {
            return redirect()->back()->with('error', 'Data Product Not Found');
        }

        // Soft delete kategori produk
        if ($category_data->delete()) {
            // Mencatat aktivitas penghapusan kategori
            activity()
                ->causedBy(Auth::user())
                ->performedOn($category_data)
                ->event('deleted')
                ->withProperties(['category_name' => $category_data->category_name])
                ->log("Admin dengan nama " . Auth::user()->name . " menghapus kategori produk {$category_data->category_name}.");

            // Redirect ke halaman daftar kategori dengan pesan sukses
            return redirect()->route('category.index')->with('success', 'Delete Category Successfully!');
        } else {
            // Jika gagal menghapus, kembali dengan pesan error
            return redirect()->back()->with('error', 'Failed to Delete Category');
        }
    }

    public function trashed()
    {
        $trashedCategories = CategoryProduct::onlyTrashed()->get();
        return view('admin.trashed.category', compact('trashedCategories'));
    }

    public function restore($id)
    {
        $category_data = CategoryProduct::onlyTrashed()->find($id);

        if ($category_data) {
            $category_data->restore();

            // Mencatat aktivitas pemulihan kategori
            activity()
                ->causedBy(Auth::user())
                ->performedOn($category_data)
                ->event('restored')
                ->withProperties(['category_name' => $category_data->category_name])
                ->log("Admin dengan nama " . Auth::user()->name . " memulihkan kategori produk {$category_data->category_name}.");

            return redirect()->route('category.trashed')->with('success', 'Category restored successfully!');
        }

        return redirect()->back()->with('error', 'Category not found');
    }

    public function forceDelete($id)
    {
        $category_data = CategoryProduct::onlyTrashed()->find($id);

        if ($category_data) {
            $category_data->forceDelete();

            // Mencatat aktivitas penghapusan permanen kategori
            activity()
                ->causedBy(Auth::user())
                ->performedOn($category_data)
                ->event('force_deleted')
                ->withProperties(['category_name' => $category_data->category_name])
                ->log("Admin dengan nama " . Auth::user()->name . " menghapus permanen kategori produk {$category_data->category_name}.");

            return redirect()->route('category.trashed')->with('success', 'Category permanently deleted!');
        }

        return redirect()->back()->with('error', 'Category not found');
    }
}
