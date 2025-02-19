<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class MembershipController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:read-membership', only: ['index']),
            new Middleware('permission:create-membership', only: ['create']),
            new Middleware('permission:edit-membership', only: ['edit']),
            new Middleware('permission:delete-membership', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Membership::query();

        //if has sorting type
        if (request()->has('sort')) {
            if ($request->sort === 'asc') {
                $query->orderby('created_at', 'asc');
            } else if ($request->sort === 'desc') {
                $query->orderby('created_at', 'desc');
            }
        }

        //if search
        // Search by Product Name
        if ($request->has('search') && !empty($request->search)) {
            $query->where('product_name', 'like', '%' . $request->search . '%');
        }

        $membership_data = $query->get();

        return view('admin.membership.index', compact('membership_data'), ['title => Product']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.membership.create', ['title => create membership']);
    }

    public function CasierCreateMembership()
    {
        return view('casier.membership.create', ['title' => 'create membership']);
    }


    // generate code for member
    public function codeGenerator($id, $username)
    {

        $date = date('dmY');

        $usernamePart = strtoupper(substr($username, 0, 3));

        return "MBR{$id}{$date}{$usernamePart}";
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'name'         => 'required|string',
            'username'     => 'required|string',
            'email'        => 'required|string',
            'type'         => 'required|string',
            'phone_number' => 'required',
            'address'      => 'required|string',
            'status'       => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error in input',
                'errors'  => $validator->errors()
            ], 400);
        }

        $point = 100;
        if ($request->type == 'type1') {
            $point = 1000;
        } else {
            $point = 100;
        }

        $membership_code = 'kosong';

        // Tambahkan data member baru
        $data_request = [
            'name'          => $request->name,
            'username'      => $request->username,
            'email'         => $request->email,
            'type'          => $request->type,
            'point'         => $point,
            'phone_numbber' => $request->phone_number, // pastikan nama kolom sesuai dengan database
            'address'       => $request->address,
            'status'        => $request->status
        ];

        $membership_data = Membership::create($data_request);
        $membership_code = 'MBR' . strtoupper(substr($request->name, 0, 4)) . strtoupper(Str::random(6));
        $membership_data->update(['membership_code' => $membership_code]);

        // Cari berdasarkan email, username, atau membership_code
        $duplicateCount = Membership::where('email', $request->email)
            ->orWhere('username', $request->username)
            ->orWhere('membership_code', $membership_code)
            ->count();

        if ($duplicateCount > 1) {
            // Hapus data yang baru saja dibuat untuk mencegah duplikasi
            $membership_data->delete();

            // Redirect kembali ke halaman create dengan pesan error dan membawa input sebelumnya
            return redirect()->back()->with('error', 'Data sudah ada di database')->withInput();
        }

        if ($membership_data) {
            // Catat aktivitas pembuatan member baru
            activity()
                ->causedBy(Auth::user())
                ->performedOn($membership_data)
                ->event('created')
                ->withProperties([
                    'email' => $membership_data->email,
                    'name'  => $membership_data->name
                ])
                ->log(Auth::user()->name . " menambahkan member baru: {$membership_data->name} ({$membership_data->email}).");

            if (Auth::user()->role === 'admin') {
                return redirect()->route('membership.index')
                    ->with('success', 'Tambah Membership Berhasil!');
            } else {
                return redirect()->route('dashboard.casier')
                    ->with('success', 'Tambah membership berhasil');
            }
        } else {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('membership.index')
                    ->with('error', 'Gagal Tambah Membership!');
            } else {
                return redirect()->route('dashboard.casier')
                    ->with('error', 'Gagal Tambah Membership!');
            }
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //sort data by ID
        $membership_data = Membership::findOrFail($id);

        return response()->json([
            'success' => '200',
            'message' => 'detail data sended',
            'proposal' => $membership_data,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $membership_data = Membership::findOrFail($id);

        return view('admin.membership.edit', compact('membership_data'), ['title' => '$membership_data']);
    }

    public function update(Request $request, $id)
    {
        $membership_data = Membership::findOrFail($id);

        $validator = FacadesValidator::make($request->all(), [
            'name' => 'sometimes|string',
            'username' => 'sometimes|string',
            'email' => 'sometimes|email',
            'type' => 'sometimes|string',
            'point' => 'sometimes',
            'phone_number' => 'sometimes',
            'address' => 'sometimes|string',
            'status' => 'sometimes|string|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error in input',
                'errors' => $validator->errors()
            ], 400);
        }

        $data_request = $request->only(['name', 'username', 'email', 'type', 'point', 'phone_number', 'address', 'status']);

        $update = $membership_data->update($data_request);

        if ($update) {
            activity()
                ->causedBy(Auth::user())
                ->performedOn($membership_data)
                ->event('updated')
                ->withProperties([
                    'old' => $membership_data->getOriginal(),
                    'new' => $data_request
                ])
                ->log(Auth::user()->name . " memperbarui data membership {$membership_data->name} ({$membership_data->email}).");

            return redirect()->route('membership.index')
                ->with('success', 'Update Membership Success!');
        } else {
            return redirect()->route('membership.index')
                ->with('error', 'Failed to Update Membership!');
        }
    }


    public function destroy($id)
    {
        $membership_data = Membership::find($id);

        if (!$membership_data) {
            return redirect()->back()->with('error', 'Member Not Found');
        }

        // Soft delete the membership
        if ($membership_data->delete()) {
            // Log the deletion activity
            activity()
                ->causedBy(Auth::user())
                ->performedOn($membership_data)
                ->event('deleted')
                ->withProperties([
                    'id' => $membership_data->id,
                    'name' => $membership_data->name,
                    'email' => $membership_data->email ?? 'N/A',
                ])
                ->log(Auth::user()->name . " deleted membership {$membership_data->name} (ID: {$membership_data->id}).");

            return redirect()->route('membership.index')->with('success', 'Delete Member Successfully!');
        }

        return redirect()->back()->with('error', 'Failed to Delete Member');
    }

    public function trashed(Request $request)
    {
        $query = Membership::onlyTrashed();


        // Pencarian berdasarkan nama
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sorting berdasarkan deleted_at
        if ($request->has('sort') && in_array($request->sort, ['asc', 'desc'])) {
            $query->orderBy('deleted_at', $request->sort);
        }

        $trashedMembership = $query->get(); // Ambil user yang sudah dihapus sesuai filter

        return view('admin.trashed.membership', compact('trashedMembership'));
    }

    /**
     * Restore a soft deleted membership.
     */
    public function restore($id)
    {
        $membership = Membership::onlyTrashed()->find($id);
        if ($membership) {
            $membership->restore();
            $membership->update(['status' => 'active']);

            // Activity log for restoration
            activity()
                ->causedBy(Auth::user())
                ->performedOn($membership)
                ->event('restored')
                ->withProperties([
                    'membership_code' => $membership->membership_code,
                    'name' => $membership->name,
                    'email' => $membership->email,
                ])
                ->log(Auth::user()->name . " mengembalikan membership {$membership->name} ({$membership->email}).");

            return redirect()->route('membership.trashed')->with('success', 'Member berhasil dikembalikan!');
        }
        return redirect()->back()->with('error', 'Member tidak ditemukan');
    }

    /**
     * Permanently delete a membership.
     */
    public function forceDelete($id)
    {
        $membership = Membership::onlyTrashed()->find($id);
        if ($membership) {
            $membership->forceDelete();

            // Activity log for force delete
            activity()
                ->causedBy(Auth::user())
                ->performedOn($membership)
                ->event('force_deleted')
                ->withProperties([
                    'membership_code' => $membership->membership_code,
                    'name' => $membership->name,
                    'email' => $membership->email,
                ])
                ->log(Auth::user()->name . " menghapus permanen membership {$membership->name} ({$membership->email}).");

            return redirect()->route('membership.trashed')->with('success', 'Member berhasil dihapus permanen!');
        }
        return redirect()->back()->with('error', 'Member tidak ditemukan');
    }
}
