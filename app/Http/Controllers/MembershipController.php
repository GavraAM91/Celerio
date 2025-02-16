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
    public function index(Request $request)
    {
        $query = Membership::query();

        //if has sorting type
        if (request()->has('sort')) {
            if ($request->sort === 'asc') {
                $query->orderby('created_at', 'asc');
            } else if ($request->sort === 'desc') {
                $query->orderby('created_at', 'desc');
            }
        }

        //if search
        // Search by Product Name
        if ($request->has('search') && !empty($request->search)) {
            $query->where('product_name', 'like', '%' . $request->search . '%');
        }

        $membership_data = $query->get();

        return view('admin.membership.index', compact('membership_data'), ['title => Product']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.membership.create', ['title => create membership']);
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

        return response()->json([
            'success' => '200',
            'message' => 'detail data sended',
            'proposal' => $membership_data,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        $membership_data = Membership::findOrFail($id);

        return view('admin.membership.edit', compact('membership_data'), ['title => $membership_data']);
    }

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
            return redirect()->route('membership.index')
                ->with('success', 'Update Membership Success!');
        } else {
            return redirect()->route('membership_data.index')
                ->with('error', 'Failed to Update Membership!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $membership_data = Membership::find($id);
        if ($membership_data) {
            if ($membership_data->delete()) {
                return redirect()->route('product.index')->with('success', 'Delete Member Successfully!');
            } else {
                return redirect()->back()->with('error', 'Failed to Delete Member');
            }
        } else {
            return redirect()->back()->with('error', 'Member Not Found');
        }
    }
}
