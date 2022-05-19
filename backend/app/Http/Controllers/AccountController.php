<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        return Account::all();
    }

    /*
    'id',
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
    */

    public function store()
    {
        $id = Account::where('parent_id', request()->parent_id)->max('id');
        if (!$id) $id = (int)request()->parent_id . 01;
        else  $id++;
        return Account::create(request([
            'parent_id',
            'name',
            'type',
            'status',
            'is_main',
            'debit_or_credit',
            'balance_type',
        ] + ['id' => $id]));
    }

    public function show(Account $account)
    {
        return $account->load('transactions');
    }

    public function update(Account $account)
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

        return response()->json(['success' => 'Account was updated'], 202);
    }

    public function destroy(Account $account)
    {
        $account->delete();
        return response()->json(['success' => 'Account was deleted'], 202);
    }
}
