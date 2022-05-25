<?php

namespace App\Http\Controllers;

use App\Models\Realty;
use Illuminate\Http\Request;

class RealtyController extends Controller
{
    //
    public function index()
    {
        return Realty::latest()->get();
    }



    public function store()
    {

        $realty = Realty::create(request(['name', 'code', 'address', 'description', 'realty_owner_id']));
        if (request()->imgs && is_array(request()->imgs)) {
            foreach (request()->imgs as $img) {
                $path = $img->store('docs/realty', ['disk' => 'public']);
                $realty->docs()->create(['path' => $path, 'name' => 'default']);
            }
        }

        return $realty;
    }

    public function show(Realty $realty)
    {
        return $realty->load('unites', 'docs');
    }

    public function update(Realty $realty)
    {

        $realty->update(request(['name', 'code', 'address', 'description']));
        if (request()->imgs && is_array(request()->imgs)) {
            foreach (request()->imgs as $img) {
                $path = $img->store('docs/realty', ['disk' => 'public']);
                $realty->docs()->create(['path' => $path, 'name' => 'default']);
            }
        }
        return response()->json(['success' => 'realty was updated'], 202);
    }

    public function destroy(Realty $realty)
    {
        $realty->delete();
        return response()->json(['success' => 'realty was deleted'], 202);
    }
}
