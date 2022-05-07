<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealtyUnitReservation extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the user that owns the RealtyUnitReservation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function unit()
    {
        return $this->belongsTo(RealtyUnit::class);
    }
}
