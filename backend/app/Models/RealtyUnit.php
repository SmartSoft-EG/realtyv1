<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealtyUnit extends Model
{
    use HasFactory;
    protected $guarded = [];
    //protected $table = "realty_units";

    public function realty()
    {
        return $this->belongsTo(Realty::class);
    }

    public function docs()
    {
        return $this->morphMany(Doc::class, 'docable');
    }

    public function owners()
    {
        return $this->belongsToMany(Owner::class, 'realty_units_owners');
    }
}
