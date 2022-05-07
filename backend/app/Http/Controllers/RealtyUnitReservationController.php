<?php

namespace App\Http\Controllers;

use App\Models\RealtyUnitReservation;
use Illuminate\Http\Request;

class RealtyUnitReservationController extends Controller
{
    public function index()
    {
        return RealtyUnitReservation::all();
    }

    public function store()
    {
        return RealtyUnitReservation::create(request(['user_id', 'realty_unit_id', 'customer_id', 'start_date', 'end_date', 'price', 'total_price', 'state']));
    }
}
