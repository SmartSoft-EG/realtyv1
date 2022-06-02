<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountOrigin extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $appends = ['full_name'];

    public function parent()
    {
        return $this->belongsTo('App\Models\Account', 'parent_id', 'id')->withDefault(['id' => -1, 'name' => 'غير محدد']);
    }

    public function children()
    {
        return $this->hasMany('App\Models\Account', 'parent_id', 'id');
    }

    public function accounts()
    {
        return $this->hasMany(AccAccount::class);
    }

    public function objects()
    {
        return $this->belongsToMany(AccObject::class, AccAccount::class);
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
