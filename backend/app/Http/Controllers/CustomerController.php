<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //
    public function index()
    {
        return Customer::orderBy('updated_at', 'desc')->get();
    }

    public function store()
    {
        return Customer::create(request(['name', 'address', 'email', 'telephone', 'job', 'national_id']));
    }

    public function show(Customer $customer)
    {
        return $customer;
    }

    public function update(Customer $customer)
    {

        $customer->update(request(['name', 'address', 'email', 'telephone', 'job', 'national_id']));

        return response()->json(['success' => 'realty was updated'], 202);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return response()->json(['success' => 'realty was deleted'], 202);
    }
}
