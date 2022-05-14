<?php

namespace App\Http\Controllers;

use App\Models\AccTransaction;
use Illuminate\Http\Request;

class AccTransactionController extends Controller
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
        return AccTransaction::orderBy('updated_at', 'desc')->get();
    }

    public function store()
    {
        return AccTransaction::create(request([
            'user_id',
            'date',
            'description',
            'status',
            'value',
            'transactionable'
        ]));
    }

    public function show(AccTransaction $AccTransaction)
    {
        return $AccTransaction;
    }

    public function update(AccTransaction $AccTransaction)
    {

        $AccTransaction->update(request([
            'user_id',
            'date',
            'description',
            'status',
            'value',
            'transactionable'

        ]));

        return response()->json(['success' => 'AccTransaction was updated'], 202);
    }

    public function destroy(AccTransaction $AccTransaction)
    {
        $AccTransaction->delete();
        return response()->json(['success' => 'AccTransaction was deleted'], 202);
    }
}
