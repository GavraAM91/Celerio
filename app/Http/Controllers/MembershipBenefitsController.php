<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;
use App\Models\MembershipBenefits;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator as FacadesValidator;


class MembershipBenefitsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //all membership's data 
        $membership_benefits_data = MembershipBenefits::all();

        if ($membership_benefits_data) {
            return response()->json([
                'success' => true,
                'message' => 'all data send',
                'data' => $membership_benefits_data
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'error sending data ',
            ], 200);
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
        $validator = FacadesValidator::make($request, [
            'type' => 'required|string',
            'percentage_discount' => 'required|numeric',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error in input',
                'errors' => $validator->errors()
            ], 400);
        }

        //add benefits
        $data_request = [
            'type' => request()->type,
            'percentage_discount' => request()->percentage_discount,
        ];

        $membership_benefits_data = MembershipBenefits::create($data_request);

        if ($membership_benefits_data) {
            return response()->json([
                'success' => true,
                'message' => 'membership benefits created!',
                'data' => $membership_benefits_data
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'membership benefits failed to create!',
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //sort data by ID
        $membership_benefits_data = MembershipBenefits::findOrFail($id);


        if ($membership_benefits_data) {
            return response()->json([
                'success' => true,
                'message' => 'show data',
                'data' => $membership_benefits_data
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'error sending data ',
            ], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

        // $request = request()->all();

        $membership_benefits_data = MembershipBenefits::findOrFail($request->id);

        $validator = FacadesValidator::make($request, [
            'type' => 'required|string',
            'percentage_discount' => 'required|numeric',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error in input',
                'errors' => $validator->errors()
            ], 400);
        }

        //add benefits
        $data_request = [
            'type' => request()->type,
            'percentage_discount' => request()->percentage_discount,
        ];

        //update to db
        $membership_benefits_data->update($data_request);

        if ($membership_benefits_data) {
            return response()->json([
                'success' => true,
                'message' => 'MembershipBenefits updated successfully',
                'membership' => $membership_benefits_data
            ], 200);
        } else {
            return response()->json([
                'message' => 'MembershipBenefits updated successfully',
                'membership' => $membership_benefits_data
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {

        $request = request()->all();

        $membership_benefits_data = MembershipBenefits::findOrFail($request['id']);

        if ($membership_benefits_data) {
            if ($membership_benefits_data->delete()) {
                return response()->json([
                    'success' => 'true',
                    'message' => 'The MembershipBenefits Succesfully deleted'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'The MembershipBenefits failed to delete'
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'The Membership Not Found'
            ]);
        }
    }
}
