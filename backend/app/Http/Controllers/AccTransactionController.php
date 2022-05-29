<?php

namespace App\Http\Controllers;

use App\Models\AccTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        if (request()->_type && request()->_type == 'apply_due') {


            if ($AccTransaction->state == 'performed') return response()->json(['error' => 'لا يمكن اعتماد هذا الاجراء'], 404);
            //check date
            DB::beginTransaction();

            try {
                $AccTransaction->update(['state' => 'performed']);

                //get customer
                $customer = $AccTransaction->transactionable->customer;

                $customer->transactions()->create([
                    'acc_transaction_id' => $AccTransaction->id,
                    'account_id' => 1127,
                    'debit' => $AccTransaction->value
                ]);
                $AccTransaction->items()->create([
                    'account_id' => 126,
                    'credit' => $AccTransaction->value
                ]);
                //update reservation total dues
                $AccTransaction->transactionable()->increment('total_dues', $AccTransaction->value);
                DB::commit();
                return 'ok';
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json($th->getMessage(), 404);
            }
        }

        if (request()->_type && request()->_type == 'apply_payment') {
            if ($AccTransaction->state == 'performed') return response()->json(['error' => 'لا يمكن اعتماد هذا الاجراء'], 404);

            //check date
            DB::beginTransaction();

            try {
                $AccTransaction->update(['state' => 'performed']);

                //get customer
                $customer = $AccTransaction->transactionable->customer;

                $customer->transactions()->create([
                    'acc_transaction_id' => $AccTransaction->id,
                    'account_id' => 1127,
                    'credit' => $AccTransaction->value
                ]);

                $AccTransaction->items()->create([
                    'account_id' => 112501,
                    'debit' => $AccTransaction->value
                ]);

                //add sales transaction

                $trans = AccTransaction::create(['date' => date('Y-m-d'), 'user_id' => auth()->id(), 'value' => $AccTransaction->value, 'state' => 'performed', 'description' => 'مبيعات الحجز رقم' . $AccTransaction->transactionable->id, 'type' => 'sales']);

                $trans->items()->create([
                    'account_id' => 126,
                    'debit' => $AccTransaction->value
                ]);

                $trans->items()->create([
                    'account_id' => 22715,
                    'credit' => $AccTransaction->value
                ]);

                //update reservation total payments
                $AccTransaction->transactionable()->increment('total_payments', $AccTransaction->value);
                DB::commit();
                return 'ok';
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json($th->getMessage(), 404);
            }
        }

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
