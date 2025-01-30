<?php

namespace App\Http\Controllers\Api;

use App\Models\Sales;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class SalesControllerApi extends Controller
{
    //if product already buy do this
    public function PurchasedProduct(Request $request) {
        $validator
    }

    //product still in cart a.k.a temporaryOrder
    public function TemporaryOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'membership_id' => 'nullable', 
            'product_id' => 'required|integer',
            'product_quantity' => 'required|integer|min:1',
        ]);

        //if fail in validation
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data_order = [
            'membership_id' => $request->input('membership_id', null), // Default `null` jika tidak ada
            'product_id' => $request->input('product_id'),
            'product_quantity' => $request->input('product_quantity'),
        ];

        // Insert in sales with default status is "Unpaid"
        $temporary_order = Sales::create($data_order);
    }
}
