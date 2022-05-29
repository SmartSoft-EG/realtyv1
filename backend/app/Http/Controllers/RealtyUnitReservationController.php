<?php

namespace App\Http\Controllers;

use App\Models\RealtyUnitReservation;
use Illuminate\Http\Request;

class RealtyUnitReservationController extends Controller
{
    public function index()
    {
        return RealtyUnitReservation::latest()->with(['customer:id,name', 'unit:id,code'])->get();
    }

    public function store()
    {
        $reservation =  RealtyUnitReservation::create(request()->only(
            'realty_unit_id',
            'customer_id',
            'start_date',
            'end_date',
            'months_count',
            'rent_value_per_month',
            'due_date',
            'state'
        ) +
            ['user_id' => auth()->id()]);


        $due_date = date('Y-m-d', strtotime(date('Y-m', strtotime($reservation->start_date)) . '-' . $reservation->due_date));


        for ($i = 0; $i < $reservation->months_count; $i++) {
            $reservation->transactions()->create([
                'user_id' => auth()->id(),
                'date' => $due_date,
                'description' => 'استحقاق القيمة الايجارية لشهر  ' . date('Y-m', strtotime($due_date)),
                'value' => $reservation->rent_value_per_month,
                'state' => 'waiting',
                'type' => 'due'
            ]);

            $reservation->transactions()->create([
                'user_id' => auth()->id(),
                'date' => $due_date,
                'description' => 'تحصيل القيمة الايجارية لشهر  ' . date('Y-m', strtotime($due_date)),
                'value' => $reservation->rent_value_per_month,
                'state' => 'waiting',
                'type' => 'payment'
            ]);

            $due_date = date('Y-m-d', strtotime($due_date . '+1 month'));
        }

        return $reservation->load('customer:id,name,balance', 'unit:id,code', 'transactions');
    }

    public function show(RealtyUnitReservation $realty_unit_reservation)
    {
        return   $realty_unit_reservation->load('customer', 'unit', 'docs', 'transactions')->append('progress');
    }

    public function update(RealtyUnitReservation $realty_unit_reservation)
    {
        if (request()->add_transaction) {

            $trs = $realty_unit_reservation->transactions()->create([
                'user_id' => auth()->id, 'description' => request()->description, 'value' => request()->value, 'date' => date('Y-m-d')
            ]);
        }
    }
}
