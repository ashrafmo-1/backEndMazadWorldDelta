<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        $customers = Customer::all();
        return response()->json([
            'customers' => $customers
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'phone' => 'required|string|',
        ]);

        $user = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'location' => $request->location,
            'note' => $request->note
        ]);

        return response()->json([
            'message' => 'created successfully',
        ]);
    }

    public function show($id)
    {
        $customer = Customer::find($id);
        return response()->json([
            'customer' => $customer
        ]);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers,email,'.$id,
            'phone' => 'required|string|',
        ]);

        $customer = Customer::find($id);
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->location = $request->location;
        $customer->note = $request->note;
        $customer->save();

        return response()->json([
            'message' => 'updated successfully',
        ]);
    }

    public function delete($id)
    {
        $customer = Customer::find($id);
        $customer->delete();

        return response()->json([
            'message' => 'deleted successfully',
        ]);
    }

}
