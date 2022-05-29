<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class People extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "peoples";

    public function updateBalance()
    {
        $dif = $this->debit - $this->credit;
        $balance_type = $dif > 0 ? 'debit' : 'credit';
        $this->update(['balance' => abs($dif), 'balance_type' => $balance_type]);
    }

    public function units()
    {
        return $this->belongsToMany(RealtyUnit::class, 'realty_units_peoples');
    }

    public function transactions()
    {
        return $this->morphMany(AccTransDetail::class, 'detailable');
    }
}
