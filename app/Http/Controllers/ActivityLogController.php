<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //all membership's data 
        $activity_log_data = ActivityLog::all();

        if ($activity_log_data) {
            return response()->json([
                'success' => true,
                'message' => 'all data send',
                'data' => $activity_log_data
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
            'log' => 'required|string',
            'created_at' => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error in input',
                'errors' => $validator->errors()
            ], 400);
        }

        //add benefits
        $data_request = [
            'user_id' => Auth::user()->id,
            'log' => request()->log,
            'created_at' => now()
        ];

        $activity_log_data = ActivityLog::create($data_request);

        if ($activity_log_data) {
            return response()->json([
                'success' => true,
                'data' => $activity_log_data
            ], 200);
        } else {
            return response()->json([
                'success' => false,
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //sort data by ID
        $activity_log_data = ActivityLog::findOrFail($id);


        if ($activity_log_data) {
            return response()->json([
                'success' => true,
                'message' => 'show data',
                'data' => $activity_log_data
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
    // public function update(Request $request)
    // {

    //     // $request = request()->all();

    //     $activity_log_data = ActivityLog::findOrFail($request->id);

    //     $validator = FacadesValidator::make($request, [
    //         'type' => 'required|string',
    //         'percentage_discount' => 'required|numeric',
    //     ]);


    //     if ($validator->fails()) {
    //         return response()->json([
    //             'message' => 'Error in input',
    //             'errors' => $validator->errors()
    //         ], 400);
    //     }

    //     //add benefits
    //     $data_request = [
    //         'type' => request()->type,
    //         'log' => request()->percentage_discount,
    //     ];

    //     //update to db
    //     $activity_log_data->update($data_request);

    //     if ($activity_log_data) {
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'ActivityLog updated successfully',
    //             'membership' => $activity_log_data
    //         ], 200);
    //     } else {
    //         return response()->json([
    //             'message' => 'ActivityLog updated successfully',
    //             'membership' => $activity_log_data
    //         ], 200);
    //     }
    // }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(Request $request)
    // {

    //     $request = request()->all();

    //     $activity_log_data = ActivityLog::findOrFail($request['id']);

    //     if ($activity_log_data) {
    //         if ($activity_log_data->delete()) {
    //             return response()->json([
    //                 'success' => 'true',
    //                 'message' => 'The ActivityLog Succesfully deleted'
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'The ActivityLog failed to delete'
    //             ]);
    //         }
    //     } else {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'The Membership Not Found'
    //         ]);
    //     }
    // }
}
