<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccTransaction extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function transactionable()
    {
        return $this->morphTo();
    }

    public function items()
    {
        return $this->hasMany('App\Models\AccTransDetail');
    }
}
