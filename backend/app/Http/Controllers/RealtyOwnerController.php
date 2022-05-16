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
        $realtyOwner = RealtyOwner::create(request(['name', 'address', 'email', 'telephone', 'national_id']));

        // $max = Account::where('parent_id', 11)->max('id');
        // if (!$max) $max = 112701;
        // else  $max++;
        // $realtyOwner->account()->create(['id' => $max, 'parent_id' => 11, 'name' => 'realtyOwner: ' . $realtyOwner->name, 'is_main' => 0, 'debit_or_credit' => 'credit']);
        // return $realtyOwner->load('account');
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
