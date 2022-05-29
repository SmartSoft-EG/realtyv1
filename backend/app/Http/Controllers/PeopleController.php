<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\People;
use Illuminate\Http\Request;

class PeopleController extends Controller
{
    //
    public function index()
    {
        if (request()->all) return People::orderBy('updated_at', 'desc')->get();

        return People::where('type', request()->type)->orderBy('updated_at', 'desc')->get();
    }

    public function store()
    {
        $people = People::create(request(['name', 'type', 'address', 'email', 'telephone', 'job', 'national_id']));

        //add people account
        //get max record in peoples
        // $max = Account::where('parent_id', 1127)->max('id');
        // if (!$max) $max = 112701;
        // else  $max++;
        // $people->account()->create(['id' => $max, 'parent_id' => 1127, 'name' => 'people: ' . $people->name, 'is_main' => 0, 'debit_or_credit' => 'credit']);
        return $people;
    }

    public function show(People $people)
    {
        return $people->load('account');
    }

    public function update(People $person)
    {
        // return $person;
        $person->update(request(['name', 'type', 'address', 'email', 'telephone', 'job', 'national_id']));

        return response()->json(['success' => 'people was updated'], 202);
    }

    public function destroy(People $person)
    {
        $person->delete();
        return response()->json(['success' => 'people was deleted'], 202);
    }
}
