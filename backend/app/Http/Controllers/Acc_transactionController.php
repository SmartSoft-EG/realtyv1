<?php

namespace App\Http\Controllers;
use App\Models\Acc_transaction;
use Illuminate\Http\Request;

class Acc_transactionController extends Controller
{
/*
'user_id',
'date',
'description',
'status',
'value',
'transactionable'
*/
public function index()
    {
        return Acc_transaction::orderBy('updated_at', 'desc')->get();
    }

    public function store()
    {
        return Acc_transaction::create(request([
            'user_id',
            'date',
            'description',
            'status',
            'value',
            'transactionable'
        ]));
    }

    public function show(Acc_transaction $acc_transaction)
    {
        return $acc_transaction;
    }

    public function update(Acc_transaction $acc_transaction)
    {

        $acc_transaction->update(request([
            'user_id',
            'date',
            'description',
            'status',
            'value',
            'transactionable'

        ]));

        return response()->json(['success' => 'Acc_transaction was updated'], 202);
    }

    public function destroy(Acc_transaction $acc_transaction)
    {
        $acc_transaction->delete();
        return response()->json(['success' => 'Acc_transaction was deleted'], 202);
    }

}
