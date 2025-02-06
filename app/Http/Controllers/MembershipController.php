<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;

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
        $validator = FacadesValidator::make($request, [
            'name' => 'required',
            'username' => 'required',
            'email' => 'required',
            'type' => 'required'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error in input',
                'errors' => $validator->errors()
            ], 400);
        }

        //add member
        $data_request = [
            'name' => request()->name,
            'username' => request()->username,
            'email' => request()->email,
            'type' => request()->type
        ];

        $membership_data = Membership::create($data_request);

        //generate code 
        $membership_code = $this->codeGenerator($membership_data->id, $membership_data->username);

        //update the data 
        $membership_data->update(['membership_code' => $membership_code]);

        if ($membership_data) {
            return response()->json([
                'success' => true,
                'message' => 'membership created!',
                'data' => $membership_data
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'membership failed to create!',
            ], 400);
        }
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
    public function edit($id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

        // $request = request()->all();

        $membership_data = Membership::findOrFail($request->id);

        $validator = FacadesValidator::make($request->all(), [
            'name' => 'sometimes|string',
            'username' => 'sometimes|string',
            'email' => 'sometimes|string',
            'type' => 'sometimes|string'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error in input',
                'errors' => $validator->errors()
            ], 400);
        }

        //get validated data
        $validated_data = $validator->validated();

        //update to db
        $membership_data->update($validated_data);

        if ($membership_data) {
            return response()->json([
                'success' => true,
                'message' => 'Membership updated successfully',
                'membership' => $membership_data
            ], 200);
        } else {
            return response()->json([
                'message' => 'Membership updated successfully',
                'membership' => $membership_data
            ], 200);
        }
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
