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

class UserController extends Controller
{
    public function indexAdmin(Request $request)
    {
        $query = User::where('role', 'admin');

        if ($request->has('sort')) {
            $query->orderBy('created_at', $request->sort === 'asc' ? 'asc' : 'desc');
        }

        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $data_admin = $query->get();
        return view('admin.admin.index', compact('data_admin'), ['title' => 'Kelola Admin']);
    }

    public function indexCasier(Request $request)
    {
        $query = User::where('role', 'casier');

        if ($request->has('sort')) {
            $query->orderBy('created_at', $request->sort === 'asc' ? 'asc' : 'desc');
        }

        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $data_casier = $query->get();
        return view('admin.casier.index', compact('data_casier'), ['title' => 'Kelola Casier']);
    }

    public function createAdmin()
    {
        return view('admin.admin.create', ['title' => 'Tambah Admin']);
    }

    public function createCasier()
    {
        return view('admin.casier.create', ['title' => 'Tambah Casier']);
    }
    public function showAdmin($id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);
        return view('admin.admin.detail', compact('admin'));
    }

    public function showCasier($id)
    {
        $casier = User::where('role', 'casier')->findOrFail($id);
        return view('admin.casier.detail', compact('casier'));
    }


    public function register(Request $request)
    {
        // Validasi data input menggunakan FacadesValidator
        $validator = FacadesValidator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string',
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

        // dd($user);  
        // Assign role ke user
        $user->assignRole($request->role);

        if ($user) {
            activity()
                ->causedBy(Auth::user() ?? $user)
                ->performedOn($user)
                ->event('created')
                ->withProperties([
                    'email' => $user->email,
                    'role' => $user->role ?? 'undefined'
                ])
                ->log("Admin menambahkan user {$user->name} ({$user->email}) dengan role " . ($user->role ?? 'undefined'));



            // dd($request->role);
            if ($request->role == 'admin') {
                return redirect()->route('user.indexAdmin')->with('success', 'Admin registered successfully!');
            } elseif ($request->role == 'casier') {
                return redirect()->route('user.indexCasier')->with('success', 'Casier registered successfully!');
            }
        }

        return redirect()->back()->with('error', 'Failed to register user.');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function editAdmin($id)
    {
        $user = User::where('role', 'admin')->findOrFail($id);
        return view('admin.admin.edit', compact('user'), ['title' => 'Edit Admin']);
    }

    public function editCasier($id)
    {
        $user = User::where('role', 'casier')->findOrFail($id);
        return view('admin.casier.edit', compact('user'), ['title' => 'Edit Casier']);
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

        // Validasi input
        $validator = FacadesValidator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => "sometimes|email|unique:users,email,$id",
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'sometimes|string|max:50',
        ], [
            'email.unique' => 'The email has already been taken.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update hanya jika ada perubahan
        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        if ($request->has('role')) {
            $user->role = $request->role;
        }

        $user->save();

        // Log aktivitas update user
        activity()
            ->causedBy(Auth::user() ?? $user)
            ->performedOn($user)
            ->event('updated') // Menandai event sebagai 'updated'
            ->withProperties([
                'email' => $user->email,
                'role' => $user->role,
            ])
            ->log("Admin dengan nama " . (Auth::user()->name ?? 'Unknown') .
                " mengupdate profile user {$user->name} ({$user->email}) dengan role {$user->role}.");


        if ($user->role == 'admin') {
            return redirect()->route('user.indexAdmin')->with('success', 'Admin updated successfully!');
        } elseif ($user->role == 'casier') {
            return redirect()->route('user.indexCasier')->with('success', 'Casier updated successfully!');
        }

        return redirect()->back()->with('success', 'User updated successfully!');
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'Data user tidak ditemukan');
        }

        // Update status sebelum soft delete
        $user->update(['status' => 'deleted']);

        if ($user->delete()) {
            if ($user->role == 'admin') {
                activity()
                    ->causedBy(Auth::user())
                    ->performedOn($user)
                    ->event('deleted')
                    ->withProperties([
                        'email' => $user->email,
                        'role' => $user->role,
                    ])
                    ->log("Admin dengan nama " . Auth::user()->name .
                        " menghapus admin {$user->name} ({$user->email}).");

                return redirect()->route('user.indexAdmin')->with('success', 'Admin berhasil dihapus!');
            } elseif ($user->role == 'casier') {
                activity()
                    ->causedBy(Auth::user())
                    ->performedOn($user)
                    ->event('deleted')
                    ->withProperties([
                        'email' => $user->email,
                        'role' => $user->role,
                    ])
                    ->log("Admin dengan nama " . Auth::user()->name .
                        " menghapus casier {$user->name} ({$user->email}).");

                return redirect()->route('user.indexCasier')->with('success', 'Casier berhasil dihapus!');
            }
        } else {
            if ($user->role == 'admin') {
                return redirect()->route('user.indexAdmin')->with('error', 'Gagal menghapus admin');
            } elseif ($user->role == 'casier') {
                return redirect()->route('user.indexCasier')->with('error', 'Gagal menghapus casier');
            }
        }
    }


    public function trashedUser(Request $request)
    {
        $query = User::onlyTrashed();

        // Filter berdasarkan role
        if ($request->has('role') && in_array($request->role, ['admin', 'casier'])) {
            $query->where('role', $request->role);
        }

        // Pencarian berdasarkan nama
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sorting berdasarkan deleted_at
        if ($request->has('sort') && in_array($request->sort, ['asc', 'desc'])) {
            $query->orderBy('deleted_at', $request->sort);
        }

        $trashedUsers = $query->get(); // Ambil user yang sudah dihapus sesuai filter

        return view('admin.trashed.user', compact('trashedUsers'));
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->find($id);
        if ($user) {
            $user->restore();
            $user->update(['status' => 'active']); // Kembalikan status ke 'active'

            // Catat aktivitas restore user
            activity()
                ->causedBy(Auth::user())
                ->performedOn($user)
                ->event('restored')
                ->withProperties([
                    'email' => $user->email,
                    'role' => $user->role,
                ])
                ->log(Auth::user()->name . " mengembalikan user {$user->name} ({$user->email}).");

            return redirect()->route('user.trashed')->with('success', 'User berhasil dikembalikan!');
        }
        return redirect()->back()->with('error', 'User tidak ditemukan');
    }

    // Hapus Permanen
    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->find($id);
        if ($user) {
            $user->forceDelete();

            // Catat aktivitas penghapusan permanen
            activity()
                ->causedBy(Auth::user())
                ->performedOn($user)
                ->event('force_deleted')
                ->withProperties([
                    'email' => $user->email,
                    'role' => $user->role,
                ])
                ->log(Auth::user()->name . " menghapus permanen user {$user->name} ({$user->email}).");

            return view('admin.trashed.user')->with('success', 'User berhasil dihapus permanen!');
        }
        return redirect()->back()->with('error', 'User tidak ditemukan');
    }
}
