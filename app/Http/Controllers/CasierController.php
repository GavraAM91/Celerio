<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\CategoryProduct;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class CasierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

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
                $query->orderby('created_at', 'asc');
            } else if ($request->sort === 'desc') {
                $query->orderby('created_at', 'desc');
            }
        }

        //if search
        // Search by User Name
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $data_product = $query->get();

        return view('admin.Products.index', compact('data_product'), ['title => User']);
    }

    /**
     * Display a listing of the resource.
     */

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.casier.create',  ['title => User']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function register(Request $request)
    {
        // Validasi data input menggunakan FacadesValidator
        $validator = FacadesValidator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string|max:50',
        ], [
            'email.unique' => 'The email has already been taken.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 400);
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Simpan user ke database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($user) {
            // Log Aktivitas
            activity()
                ->causedBy(Auth::user() ?? $user) // Jika belum login, gunakan user yang baru dibuat
                ->withProperties(['email' => $user->email, 'role' => $user->role])
                ->log("Admin dengan nama " . (Auth::user()->name ?? 'Unknown') . " menambahkan user {$user->name} ({$user->email}) dengan role {$user->role}.");

            return redirect()->route('login')->with('success', 'User registered successfully! Please login.');
        } else {
            return redirect()->back()->with('error', 'Failed to register user.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data_product = User::findOrFail($id);

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

        $data_product = User::find($id);
        // var_dump($data_product);

        $data_category = CategoryProduct::orderBy('category_name', 'asc')->get();

        return view('admin.Products.edit', compact('data_product', 'data_category'), ['title' => 'User Edit']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Validasi input menggunakan FacadesValidator
        $validator = FacadesValidator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,$id",
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|string|max:50',
        ], [
            'email.unique' => 'The email has already been taken.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update data user
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->role = $request->role;
        $user->save();

        // Log aktivitas
        activity()
            ->causedBy(Auth::user() ?? $user)
            ->withProperties(['email' => $user->email, 'role' => $user->role])
            ->log("Admin dengan nama " . (Auth::user()->name ?? 'Unknown') . " mengupdate profile dengan email {$user->email}.");

        return redirect()->back()->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data_product = User::find($id);
        if ($data_product) {
            if ($data_product->delete()) {
                return redirect()->route('c.index')->with('success', 'Delete User  Successfully!');
            } else {
                return redirect()->back()->with('error', 'Failed to Delete User');
            }
        } else {
            return redirect()->back()->with('error', 'Data User Not Found');
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
        $data_stock_db = User::findOrFail($data_stock['product_id']);

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
