<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //
    public function index()
    {
        return Customer::orderBy('updated_at', 'desc')->with('account:id,debit,credit,accountable_type,accountable_id')->get();
    }

    public function store()
    {
        $customer = Customer::create(request(['name', 'address', 'email', 'telephone', 'job', 'national_id']));

        //add customer account
        //get max record in customers
        $max = Account::where('parent_id', 1127)->max('id');
        if (!$max) $max = 112701;
        else  $max++;
        $customer->account()->create(['id' => $max, 'parent_id' => 1127, 'name' => 'customer: ' . $customer->name, 'is_main' => 0, 'debit_or_credit' => 'credit']);
        return $customer->load('account');
    }

    public function show(Customer $customer)
    {
        return $customer->load('account');
    }

    public function update(Customer $customer)
    {

        $customer->update(request(['name', 'address', 'email', 'telephone', 'job', 'national_id']));

        return response()->json(['success' => 'customer was updated'], 202);
    }

    public function destroy(Customer $customer)
    {
        $customer->account()->delete();
        $customer->delete();
        return response()->json(['success' => 'customer was deleted'], 202);
    }
}
