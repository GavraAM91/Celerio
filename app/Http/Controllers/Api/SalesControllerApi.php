<?php

namespace App\Http\Controllers\Api;

use App\Models\Sales;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Membership;
use Illuminate\Http\Request;
use App\Models\MembershipBenefits;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class SalesControllerApi extends Controller
{
    //if product already buy do this
    public function PurchasedProduct(Request $request)
    {

        //validate all data
        $validator = Validator::make($request->all(), [
            'membership_id' => 'nullable',
            'payment_method' => 'required|string',
            'total_price' => 'required',
            'cart' => 'required|array',
            'cart.*.membership_id' => 'nullable',
            'cart.*.product_id' => 'required',
            'cart.*.coupon_name' => 'nullable',
            'cart.*.quantity' => 'required|integer|min:1',
            'cart.*.total_price' => 'required',
        ]);

        //if error 
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 400);
        }

        //prepare variable
        $membershipData = null;
        $membershipBenefit = null;
        $discountMembership = 0;
        $pointMembership = 0;

        //insert data to variable
        $totalPrice = $request->total_price;

        //if they use membership card
        if ($request->membership_id != null) {
            //insert membership_id from request 
            $membershipId = $request->membeship_id;

            //check into db membership 
            $membershipData = Membership::findOrFail($membershipId);

            //check into db membership Benefit
            $membershipBenefit = MembershipBenefits::where('type', $membershipData->type)->firstOrFail();

            //check the type
            if ($membershipData->type == 'type1') {
                $discountMembership = $membershipBenefit->percentageDiscount;
            } elseif ($membershipData->type == 'type2') {
                $discountMembership = $membershipBenefit->percentageDiscount;
            } elseif ($membershipData->type == 'type3') {
                $discountMembership = $membershipBenefit->percentageDiscount;
            }
        }

        //purchase system
        foreach($request->cart as $item) {
            $product = Product::findOrFail($item['product_id']);

                
        }

        //Tax Value



        //save in database sales
        // foreach ($request->cart as $item) {
        //     Sales::create([
        //         'membership_id' => $item['membership_id'],
        //         'product_id' => $item['product_id'],
        //         'coupon_name' => $item['coupon_name'],
        //         'quantity' => $item['quantity'],
        //         'total_price' => $item['total_price']
        //     ]);
        // }
    }

    //product still in cart a.k.a temporaryOrder
    // public function TemporaryOrder(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'membership_id' => 'nullable', 
    //         'product_id' => 'required|integer',
    //         'product_quantity' => 'required|integer|min:1',
    //     ]);

    //     //if fail in validation
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'message' => 'Validation failed',
    //             'errors' => $validator->errors(),
    //         ], 422);
    //     }

    //     $data_order = [
    //         'membership_id' => $request->input('membership_id', null), // Default `null` jika tidak ada
    //         'product_id' => $request->input('product_id'),
    //         'product_quantity' => $request->input('product_quantity'),
    //     ];

    //     // Insert in sales with default status is "Unpaid"
    //     $temporary_order = Sales::create($data_order);
    // }
}
