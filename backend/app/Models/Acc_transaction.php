<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acc_transaction extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function transactionable()
    {
        return $this->morphTo();
    }
}
