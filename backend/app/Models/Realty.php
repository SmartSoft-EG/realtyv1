<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Realty extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'realty';

    /**
     * Get the user that owns the Realty
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unites()
    {
        return $this->hasMany(RealtyUnit::class);
    }

    public function owner()
    {
        return $this->belongsTo(RealtyOwner::class);
    }

    public function docs()
    {
        return $this->morphMany(Doc::class, 'docable');
    }
}
