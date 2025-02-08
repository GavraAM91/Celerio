<?php

namespace App\Http\Controllers;

use App\Models\Sales_Detail;
use App\Models\SalesReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class SalesReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //all membership's data 
        $sales_report_data = SalesReport::all();

        if ($sales_report_data) {
            return response()->json([
                'success' => true,
                'message' => 'all data send',
                'data' => $sales_report_data
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

        $sales_report_data = SalesReport::create($data_request);

        if ($sales_report_data) {
            return response()->json([
                'success' => true,
                'message' => 'membership created!',
                'data' => $sales_report_data
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
        $sales_report_data = SalesReport::findOrFail($id);


        if ($sales_report_data) {
            return response()->json([
                'success' => true,
                'message' => 'show data',
                'data' => $sales_report_data
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'error sending data ',
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {

        $request = request()->all();

        $sales_report_data = SalesReport::findOrFail($request['id']);

        if ($sales_report_data) {
            if ($sales_report_data->delete()) {
                return response()->json([
                    'success' => 'true',
                    'message' => 'The SalesReport Succesfully deleted'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'The SalesReport failed to delete'
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'The SalesReport Not Found'
            ]);
        }
    }

    // get 
}

