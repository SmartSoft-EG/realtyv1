<?php

namespace App\Http\Controllers;

use App\Models\RealtyOwner;
use App\Models\Account;
// use Illuminate\Http\Request;

class RealtyOwnerController extends Controller
{

    public function index()
    {
        return RealtyOwner::latest()->get();
    }


    public function store()
    {
        $realtyOwner = RealtyOwner::create(request(['name', 'address',  'telephone', 'job']));

        $account_id = Account::where('parent_id', 1126)->max('id'); //banks parent account
        if (!$account_id) $account_id = 112701;
        else  $account_id++;
        $realtyOwner->account()->create(['id' => $account_id, 'parent_id' => 1126, 'name' => 'realtyOwner: ' . $realtyOwner->name, 'is_main' => 0, 'debit_or_credit' => 'credit']);
        return $realtyOwner->load('account');
    }


    public function show(RealtyOwner $realtyOwner)
    {
    }

    public function update(RealtyOwner $realtyOwner)
    {
        $realtyOwner->update(request(['name', 'address', 'email', 'telephone', 'national_id']));

        return response()->json(['success' => 'realtyOwner was updated'], 202);
    }

    public function destroy(RealtyOwner $realtyOwner)
    {
        $realtyOwner->delete();
        return response()->json(['success' => 'Realty Owner was deleted'], 202);
    }
}
