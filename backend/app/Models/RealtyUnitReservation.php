<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealtyUnitReservation extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the user that owns the RealtyUnitReservation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function docs()
    {
        return $this->morphMany(Doc::class, 'docable');
    }



    public function transactions()
    {
        return $this->morphMany(AccTransaction::class, 'transactionable');
    }



    public function unit()
    {
        return $this->belongsTo(RealtyUnit::class, 'realty_unit_id');
    }

    public function customer()
    {
        return $this->belongsTo(People::class, 'customer_id');
    }

    public function getProgressAttribute()
    {

        $dif = (int)(strtotime(date('Y-m-d')) - strtotime($this->start_date)) / (60 * 60 * 24 * 30); // 3

        if ($dif < 0) return 0;


        $dif = (int)((strtotime($this->end_date) - strtotime(date('Y-m-d'))) / (60 * 60 * 24 * 30)); // 3
        if ($dif < 0) return 1;

        $mc = (int)(strtotime($this->end_date) - strtotime($this->start_date)) / (60 * 60 * 24 * 30); // 3

        return ($mc - $dif) / $mc;





        // $current_m = date('Y-m');

        // $start_m = date('Y-m', strtotime($this->start_date));
        // $end_m = date('Y-m', strtotime($this->end_date));

        // if ($current_m > $end_m) return 1;
        // if ($current_m < $start_m) return 0;

        // $performed_months = $end_m - $current_m;
        // return $performed_months / $this->months_count;
    }
}
