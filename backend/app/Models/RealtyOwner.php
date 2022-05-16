<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealtyOwner extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function account()
    {
        return $this->morphOne('App\Models\Account', 'accountable');
    }
}
