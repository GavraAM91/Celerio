<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //all membership's data 
        $membership_data = Membership::all();

        if ($membership_data) {
            return response()->json([
                'success' => true,
                'message' => 'all data send',
                'data' => $membership_data
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
        // $validator = FacadeValidator::valid
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //sort data by ID
        $membership_data = Membership::findOrFail($id);


        if ($membership_data) {
            return response()->json([
                'success' => true,
                'message' => 'show data',
                'data' => $membership_data
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
    public function edit(Membership $membership)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Membership $membership)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {

        $request = request()->all();

        $membership_data = Membership::findOrFail($request['id']);

        if ($membership_data) {
            if ($membership_data->delete()) {
                return response()->json([
                    'success' => 'true',
                    'message' => 'The Membership Succesfully deleted'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'The Membership failed to delete'
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
