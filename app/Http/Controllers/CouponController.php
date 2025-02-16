<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Coupon::query();

        //if has filter
        if (request()->has('filter')) {
            if ($request->filter === 'sold') {
                $query->where('used_coupon', '>', 0);
            } else if ($request->filter === 'stock') {
                $query->where('minimum_usage_coupon', '>', 0);
            } else if ($request->filter === 'expired') {
                $query->where('expired_at', '<', now());
            }
        }


        //if has sorting type
        if (request()->has('sort')) {
            if ($request->sort === 'asc') {
                $query->orderby('name_coupon', 'asc');
            } else if ($request->sort === 'desc') {
                $query->orderby('name_coupon', 'desc');
            }
        }

        //if search
        // Search by Product Name
        if ($request->has('search') && !empty($request->search)) {
            $query->where('coupon_name', 'like', '%' . $request->search . '%');
        }

        $data_coupon = $query->get();

        return view('admin.coupons.index', compact('data_coupon'), ['title => Coupon']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.coupons.create', ['title => create coupon']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $requestData = $request->all();

        // Validasi input
        $validator = Validator::make($requestData, [
            'name_coupon' => 'required',
            'coupon_description' => 'required',
            'value_coupon' => 'nullable',
            'percentage_coupon' => 'nullable',
            'minimum_usage_coupon' => 'required',
            'expired_at' => 'required',
            'total_coupon' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Simpan data
        $dataCoupon = Coupon::create($requestData);

        if ($dataCoupon) {
            activity()
                ->causedBy(Auth::user())
                ->performedOn($dataCoupon)
                ->event('created')
                ->withProperties($requestData)
                ->log("Admin dengan nama " . Auth::user()->name . " membuat kupon {$dataCoupon->name_coupon}.");

            return redirect()->route('coupon.index')
                ->with('success', 'New Coupon Added Successfully!');
        } else {
            return redirect()->back()
                ->with('error', 'Failed to Add Coupon');
        }
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data_coupon = Coupon::findOrFail($id);

        if ($data_coupon) {
            return response()->json([
                'succes' => true,
                'message' => 'Coupon found!',
                'data' => $data_coupon,
            ], 200);
        } else {
            return response()->json([
                'succes' => false,
                'message' => 'failed to search by ID',
            ], 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        $coupon = Coupon::findOrFail($id);

        return view('admin.coupons.edit', compact('coupon'), ['title' => 'edit coupon']);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $requestData = $request->all();

        // Temukan data berdasarkan ID
        $data_coupon = Coupon::findOrFail($requestData['id']);

        // Validasi input
        $validator = Validator::make($requestData, [
            'name_coupon' => 'required',
            'coupon_description' => 'required',
            'value_coupon' => 'sometimes|nullable',
            'percentage_coupon' => 'sometimes|nullable',
            'minimum_usage_coupon' => 'required',
            'expired_at' => 'required',
            'total_coupon' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update data
        $data_coupon->update($requestData);

        if ($data_coupon) {
            activity()
                ->causedBy(Auth::user())
                ->performedOn($data_coupon)
                ->event('updated')
                ->withProperties($requestData)
                ->log("Admin dengan nama " . Auth::user()->name . " mengedit kupon {$data_coupon->name_coupon}.");

            return redirect()->route('coupon.index')
                ->with('success', 'Update Coupon Success!');
        } else {
            return redirect()->route('coupon.index')
                ->with('error', 'Failed to Update Coupon!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    // Soft Delete Coupon
    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);

        if ($coupon->delete()) {
            activity()
                ->causedBy(Auth::user())
                ->performedOn($coupon)
                ->event('deleted')
                ->withProperties(['name_coupon' => $coupon->name_coupon])
                ->log("Admin dengan nama " . Auth::user()->name . " menghapus kupon {$coupon->name_coupon}.");

            return redirect()->route('coupon.index')->with('success', 'Coupon deleted successfully!');
        }
        return redirect()->back()->with('error', 'Failed to delete coupon');
    }

    // View Trashed Coupons
    public function trashed()
    {
        $trashedCoupons = Coupon::onlyTrashed()->get();
        return view('admin.trashed.coupons', compact('trashedCoupons'));
    }

    // Restore Coupon
    public function restore($id)
    {
        $coupon = Coupon::onlyTrashed()->findOrFail($id);
        $coupon->restore();

        activity()
            ->causedBy(Auth::user())
            ->performedOn($coupon)
            ->event('restored')
            ->withProperties(['name_coupon' => $coupon->name_coupon])
            ->log("Admin dengan nama " . Auth::user()->name . " memulihkan kupon {$coupon->name_coupon}.");

        return redirect()->route('coupon.trashed')->with('success', 'Coupon restored successfully!');
    }

    // Force Delete (Permanent Delete)
    public function forceDelete($id)
    {
        $coupon = Coupon::onlyTrashed()->findOrFail($id);

        $coupon->forceDelete();

        activity()
            ->causedBy(Auth::user())
            ->performedOn($coupon)
            ->event('force_deleted')
            ->withProperties(['name_coupon' => $coupon->name_coupon])
            ->log("Admin dengan nama " . Auth::user()->name . " menghapus permanen kupon {$coupon->name_coupon}.");

        return redirect()->route('coupon.trashed')->with('success', 'Coupon permanently deleted!');
    }
}
