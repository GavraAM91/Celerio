<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
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

        $data_product = $query->get();

        return view('admin.coupon.index', compact('data_coupon'), ['title => Coupon']);
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
        $request = request()->all();

        //validate
        $validator = FacadesValidator::make($request, [
            'name_coupon' => 'required',
            'coupon_description' => 'required',
            'value_coupon' => 'required',
            'percentage_coupon' => 'required',
            'minimum_usage_coupon' => 'required',
            'expired_at' => 'required',
            'total_coupon' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error in input',
                'errors' => $validator->errors()
            ], 400);
        }

        $data_request = [
            'name_coupon' => request()->name_coupon,
            'coupon_description' => request()->coupon_description,
            'value_coupon' => request()->value_coupon,
            'percentage_coupon' => request()->percentage_coupon,
            'minimum_usage_coupon' => request()->minimum_usage_coupon,
            'expired_at' => request()->expired_at,
            'total_coupon' => request()->total_coupon,
            'status' => request()->status,
        ];

        $dataCoupon = Coupon::create($data_request);


        if ($dataCoupon) {
            return redirect()->route('coupon.index')->with('success', 'New coupon Added Successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to Add coupon');
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

        $data_coupon = Coupon::findOrFail($id);

        return view('admin.coupons.edit', compact('data_coupon'), ['title => edit coupon']);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request = request()->all();

        $data_coupon = Coupon::findOrFail($request['id']);
        $validator = FacadesValidator::make($request, [
            'name_coupon' => 'required',
            'coupon_description' => 'required',
            'value_coupon' => 'required',
            'percentage_coupon' => 'required',
            'minimum_usage_coupon' => 'required',
            'expired_at' => 'required',
            'total_coupon' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error in input',
                'errors' => $validator->errors()
            ], 400);
        }


        $data_request = [
            'name_coupon' => request()->name_coupon,
            'coupon_description' => request()->coupon_description,
            'value_coupon' => request()->value_coupon,
            'percentage_coupon' => request()->percentage_coupon,
            'minimum_usage_coupon' => request()->minimum_usage_coupon,
            'expired_at' => request()->expired_at,
            'total_coupon' => request()->total_coupon,
            'status' => request()->status,
        ];

        $data_coupon->update($data_request);

        if ($data_coupon) {
            return response()->json([
                'success' => true,
                'message' => 'Data update succesfully!',
                'data' => $data_coupon
            ]);
        } else {
            return response()->json([
                'message' => 'Data update failed!',
                'data' => $data_coupon
            ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $data_coupon = Coupon::findOrFail($id);
        if ($data_coupon) {
            if ($data_coupon->delete()) {
                return redirect()->route('product.index')->with('success', 'Delete Coupon  Successfully!');
            } else {
                return redirect()->back()->with('error', 'Failed to Delete Coupon');
            }
        } else {
            return redirect()->back()->with('error', 'Data Coupon Not Found');
        }
    }
}
