<?php

namespace App\Http\Controllers;

use App\Models\AccAccount;
use Illuminate\Http\Request;

class AccAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->parent_id) {
            return AccAccount::where('account_origin_id', request()->parent_id)->with('object')->get();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccAccount  $accAccount
     * @return \Illuminate\Http\Response
     */
    public function show(AccAccount $accAccount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AccAccount  $accAccount
     * @return \Illuminate\Http\Response
     */
    public function edit(AccAccount $accAccount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AccAccount  $accAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AccAccount $accAccount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccAccount  $accAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccAccount $accAccount)
    {
        //
    }
}
