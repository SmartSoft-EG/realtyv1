<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Account extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function accountable()
    {
        return $this->morphTo();
    }


    public function children()
    {
        return $this->hasMany('App\Models\Account', 'parent_id', 'id');
    }


    public function parent()
    {
        return $this->belongsTo('App\Models\Account', 'parent_id', 'id')->withDefault(['id' => -1, 'name' => 'غير محدد']);
    }


    public function transactions()
    {
        return $this->hasMany('App\Models\AccTransDetail');
    }


    public function getFullNameAttribute()
    {
        return $this->account_num . ' ' . $this->name;
    }


    public function updateBalance()
    {
        $dif = $this->debit - $this->credit;
        $balance_type = $dif > 0 ? 'debit' : 'credit';
        $this->update(['balance' => abs($dif), 'balance_type' => $balance_type]);
    }
}
