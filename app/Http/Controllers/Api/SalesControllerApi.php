<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Sales;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Membership;
use Illuminate\Http\Request;
use App\Models\selling_price;
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
            'user.id' => 'required',
            'membership_id' => 'nullable',
            'payment_method' => 'required|string',
            'total_price' => 'required',
            'cart' => 'required|array',
            'cart.*.membership_id' => 'nullable',
            'cart.*.product_id' => 'required',
            'cart.*.coupon_code' => 'nullable',
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

        try {
            //if they use membership card
            if ($request->membership_id) {
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
            foreach ($request->cart as $item) {
                $product = Product::findOrFail($item['product_id']);

                //insert product price
                $basePrice = $product->product_price;

                //check the membership type
                if ($membershipData) {
                    $selling_price = selling_price::where('type', $membershipData->type)->first();
                    $markup = $selling_price ? $selling_price->markup / 100 : 0.03;
                } else {
                    $markup = 0.03;
                }

                //counting + margin
                $price = $basePrice + ($basePrice * $markup);

                //check if any discount added
                $discountByCoupon = 0;

                if (!empty($item['coupon_code'])) {
                    $coupon = Coupon::where('coupon_code', $item['coupon_code'])->first();
                    if ($coupon->used_coupon <= $coupon->total_coupon) {
                        //if any coupon available, edit the table
                        $coupon->used_coupon += 1;
                        $coupon->save();

                        if (!empty($coupon->percentage_coupon)) {
                            $discountByCoupon = $coupon->percentage_coupon;
                        } else {
                            $discountByCoupon = $coupon->value_coupon;
                        }
                    } else {
                        $discountByCoupon = 0;
                    }
                }

                //perhitungann 
                $subtotal = ($price * $item['quantity']) - $discountByCoupon;

                //hitung pajak 
                $tax = $subtotal * 0.11;

                //point membership
                $points = ($membershipData && in_array($membershipData->type, ['type1', 'type2'])) ? ($subtotal * 0.02) : 0;

                //total price
                $totalPrice += $subtotal + $tax;
                $pointMembership += $points;

                //insert into membership point 
                Membership::where('id', $membershipData->id)->update([
                    'points' => $membershipData->poin + $pointMembership,
                ]);

                //insert into sales 
                $sale = Sales::create([
                    'membership_id' => $item['membership_id'],
                    'product_id' => $item['product_id'],
                    'coupon_id' => $item['coupon_id'],
                    'payment_method' => $item['payment_method'],
                    'total_price' => $item['total_price'],
                    'quantity' => $item['quantity']
                ]);

                if ($sale) {
                    Product::where('id', $item['product_id'])
                        ->increment('sold_product', $item['quantity']);

                    $product = Product::find($item['product_id']);

                    if ($product->sold_product >= $product->stock) {
                        $product->status = 'sold out';
                        $product->save();
                    }
                } else {
                    return response()->json([
                        'message' => 'Gagal menyimpan data penjualan untuk produk ' . $product->name
                    ], 500);
                }

                //get username
                $user = User::where('id', $item['user_id']);

                $items[] = [
                    'product_name' => $product->name,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                    'subtotal' => $subtotal
                ];
            }



            return response()->json([
                'message' => 'Transaksi berhasil',
                'total_price' => $totalPrice,
                'points_earned' => $pointMembership,
                'receipt' => [
                    'cashier' => $user->name,
                    'items' => $items,
                    'total' => $totalPrice
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat memproses transaksi.',
                'error' => $e->getMessage()
            ], 500);
        }
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
