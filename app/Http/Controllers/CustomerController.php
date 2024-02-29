<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerCollection;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return new CustomerCollection(Customer::all());
    }

    public function show(Customer $customer)
    {
        return new CustomerResource($customer);

    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            "name"=> "required|max:255|string",
            "email"=> "nullable|max:255|string",
            "phone"=> "nullable|max:255|string"
        ]);
        $customer = Customer::create($validate);
        return new CustomerResource($customer);
    }

    public function update(Request $request, Customer $customer)
    {
        $validate = $request->validate([
            "name"=> "required|max:255|string",
            "email"=> "sometimes|max:255|string",
            "phone"=> "sometimes|max:255|string"
        ]);
        $customer->update($validate);
        return new CustomerResource($customer);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return response()->noContent();
    }
}
