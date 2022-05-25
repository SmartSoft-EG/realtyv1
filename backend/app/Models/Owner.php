<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function account()
    {
        return $this->morphOne(Account::class, 'accountable');
    }


    public function units()
    {
        return $this->belongsToMany(RealtyUnit::class, 'realty_units_owners');
    }
}
