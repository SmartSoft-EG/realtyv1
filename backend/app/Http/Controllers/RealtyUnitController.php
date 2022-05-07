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
        $realty_unit = RealtyUnit::create(request(['name', 'realty_id', 'floor', 'code', 'type',  'description']));
        if (request()->imgs && is_array(request()->imgs)) {
            foreach (request()->imgs as $img) {
                $path = $img->store('docs/realty', ['disk' => 'public']);
                $realty_unit->docs()->create(['path' => $path, 'name' => 'default']);
            }
        }

        return $realty_unit;
    }

    public function show(RealtyUnit $realty_unit)
    {
        return $realty_unit->load('realty:id,name', 'docs');
    }

    public function update(RealtyUnit $realty_unit)
    {

        $realty_unit->update(request(['name', 'code', 'floor', 'description']));
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
