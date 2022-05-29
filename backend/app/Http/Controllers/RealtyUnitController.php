<?php

namespace App\Http\Controllers;

use App\Models\RealtyUnit;
use Illuminate\Http\Request;

class RealtyUnitController extends Controller
{
    public function index()
    {
        return RealtyUnit::latest()->get();
    }

    public function store()
    {
        //     return request();
        $realty_unit = RealtyUnit::create(request(['realty_id', 'floor', 'size', 'code', 'type',  'description']));
        //add owners

        foreach (request()->owners as $owner) {
            $realty_unit->owners()->attach($owner['id'], ['percent' => $owner['percent']]);
        }

        if (request()->imgs && is_array(request()->imgs)) {
            foreach (request()->imgs as $img) {
                $path = $img->store('docs/realty', ['disk' => 'public']);
                $realty_unit->docs()->create(['path' => $path, 'name' => 'default']);
            }
        }

        return $realty_unit->load('owners');
    }

    public function show(RealtyUnit $realty_unit)
    {
        return $realty_unit->load('realty:id,name', 'reservations', 'reservations.customer:id,name,balance', 'docs');
    }

    public function update(RealtyUnit $realty_unit)
    {

        $realty_unit->update(request(['code', 'floor', 'size', 'type', 'description']));
        if (request()->imgs && is_array(request()->imgs)) {
            foreach (request()->imgs as $img) {
                $path = $img->store('docs/realty', ['disk' => 'public']);
                $realty_unit->docs()->create(['path' => $path, 'name' => 'default']);
            }
        }
        return  $realty_unit->load('docs');
        // return response()->json(['success' => 'realty was updated'], 202);
    }

    public function destroy(RealtyUnit $realty_unit)
    {
        $realty_unit->delete();
        return response()->json(['success' => 'realty was deleted'], 202);
    }
}
