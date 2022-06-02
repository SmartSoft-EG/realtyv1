<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccAccount extends Model
{
    use HasFactory;
    protected $guarded = [];



    public function parent()
    {
        return $this->belongsTo(AccountOrigin::class);
    }


    public function object()
    {
        return $this->belongsTo(AccObject::class);
    }


    public function transactions()
    {
        return $this->hasMany(AccTransDetail::class);
    }


    public function getFullNameAttribute()
    {
        return $this->id . ' ' . $this->name;
    }


    public function updateBalance()
    {
        $dif = $this->debit - $this->credit;
        $balance_type = $dif > 0 ? 'debit' : 'credit';
        $this->update(['balance' => abs($dif), 'balance_type' => $balance_type]);
    }
}
