<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acc_trans_detail extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function transaction()
    {
        return $this->belongsTo(acc_transaction::class);
    }
}
