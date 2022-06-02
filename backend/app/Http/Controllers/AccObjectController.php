<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccObject;
use Illuminate\Http\Request;

class AccObjectController extends Controller
{
    //
    public function index()
    {
        if (request()->all) return AccObject::orderBy('updated_at', 'desc')->get();

        return AccObject::where('type', request()->type)->orderBy('updated_at', 'desc')->get();
    }

    public function store()
    {
        $object = AccObject::create(request(['name', 'type', 'address', 'email', 'telephone']));

        //add people account
        //get max record in peoples
        // $max = Account::where('parent_id', 1127)->max('id');
        // if (!$max) $max = 112701;
        // else  $max++;
        // $people->account()->create(['id' => $max, 'parent_id' => 1127, 'name' => 'people: ' . $people->name, 'is_main' => 0, 'debit_or_credit' => 'credit']);
        if (request()->type == 'customer') {
            $object->accounts_origin()->attach(1127, ['id' => 1127 . $object->id, 'name' => 'عميل : ' . $object->name]);
        }

        return $object;
    }

    public function show(AccObject $people)
    {
        return $people->load('account');
    }

    public function update(AccObject $person)
    {
        // return $person;
        $person->update(request(['name', 'type', 'address', 'email', 'telephone', 'job', 'national_id']));

        return response()->json(['success' => 'people was updated'], 202);
    }

    public function destroy(AccObject $person)
    {
        $person->delete();
        return response()->json(['success' => 'people was deleted'], 202);
    }
}
