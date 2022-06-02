<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccObject extends Model
{
    use HasFactory;
    protected $guarded = [];



    public function units()
    {
        return $this->belongsToMany(RealtyUnit::class, 'realty_units_owners');
    }

    public function accounts()
    {
        return $this->hasMany(AccAccount::class);
    }

    public function accounts_origin()
    {
        return $this->belongsToMany(AccountOrigin::class, AccAccount::class);
    }



    public function updateBalance()
    {
        $dif = $this->debit - $this->credit;
        $balance_type = $dif > 0 ? 'debit' : 'credit';
        $this->update(['balance' => abs($dif), 'balance_type' => $balance_type]);
    }
}
