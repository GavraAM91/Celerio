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
    public function index()
    {
        //get data from database
        $data_coupon = Coupon::all();

        //send data
        if ($data_coupon) {
            return response()->json([
                'message' => 'coupon data',
                'data' => $data_coupon,
            ], 200);
        } else {
            return response()->json([
                'message' => 'error get coupon data',
            ], 400);
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
            return response()->json([
                'succes' => true,
                'message' => 'Coupon Created!',
                'data' => $dataCoupon,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create Coupon!',
            ], 400);
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
    public function edit(Request $request)
    {

        $request_id = request()->id;

        $data_coupon = Coupon::findOrFail($request_id);

        return response()->json([
            'message' => 'data for edit',
            'data' => $data_coupon
        ]);
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
    public function destroy(Request $request)
    {

        $request = request()->all();

        $coupon_data = Coupon::findOrFail($request['id']);

        if ($coupon_data) {
            if ($coupon_data->delete()) {
                return response()->json([
                    'success' => 'true',
                    'message' => 'The Coupon Succesfully deleted'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'The Coupon failed to delete'
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'The Coupon Not Found'
            ]);
        }
    }
}
