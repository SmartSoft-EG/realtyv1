<?php

namespace App\Http\Controllers;

use App\Models\AccountOrigin;
use Illuminate\Http\Request;

class AccountOriginController extends Controller
{
    public function index()
    {
        return AccountOrigin::all();
    }


    public function store()
    {
        $id = AccountOrigin::where('parent_id', request()->parent_id)->max('id');
        if (!$id) $id = (int)request()->parent_id . 01;
        else  $id++;
        return AccountOrigin::create(request([
            'parent_id',
            'name',
            'type',
            'status',
            'is_main',
            'debit_or_credit',
            'balance_type',
        ] + ['id' => $id]));
    }

    public function show(AccountOrigin $account)
    {
        return $account->load('transactions');
    }

    public function update(AccountOrigin $account)
    {

        $account->update(request([
            'acc_num',
            'parent_id',
            'accountable',
            'name',
            'type',
            'level',
            'status',
            'active',
            'is_main',
            'debit',
            'credit'

        ]));

        return response()->json(['success' => 'AccountOrigin was updated'], 202);
    }

    public function destroy(AccountOrigin $account)
    {
        $account->delete();
        return response()->json(['success' => 'AccountOrigin was deleted'], 202);
    }
}
