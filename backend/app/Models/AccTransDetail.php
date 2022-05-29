<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccTransDetail extends Model
{
    use HasFactory;
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();
        static::created(function ($entry) {
            //update main entry balance
            $entry->account->increment('credit',  (float)$entry->credit);
            $entry->account->increment('debit',  (float)$entry->debit);
            $entry->account->updateBalance();
            $entry->update(['balance' => $entry->account->balance, 'balance_type' => $entry->account->balance_type]);

            //update related entry balance
            $related = $entry->detailable;
            if ($related) {
                $related->increment('credit',  (float)$entry->credit);
                $related->increment('debit',  (float)$entry->debit);
                $related->updateBalance();
                $entry->update(['related_balance' => $related->balance, 'balance_type' => $related->balance_type]);
            }

            $parent = $entry->account->parent;
            redo:
            if ($parent->id > 0) {
                $parent->increment('credit',  (float)$entry->credit);
                $parent->increment('debit',  (float)$entry->debit);
                $parent->updateBalance();
                $parent = $parent->parent;
                goto redo;
            }
        });

        static::deleting(function ($entry) {
            $entry->account->decrement('credit',  (float)$entry->credit);
            $entry->account->decrement('debit', (float)$entry->debit);
            $entry->account->updateBalance();
            $parent = $entry->account->parent;
            redo:
            if ($parent->id > 0) {
                $parent->decrement('credit',  (float)$entry->credit);
                $parent->decrement('debit',  (float)$entry->debit);
                $parent->updateBalance();
                $parent = $parent->parent;
                goto redo;
            }
        });
    }



    public function account()
    {
        return $this->belongsTo('App\Models\Account', 'account_id');
    }





    public function getAccountNameAttribute($value)
    {
        return $this->account->name;
    }

    public function getAccountBalanceAttribute($value)
    {
        return $this->account->balance;
    }

    public function detailable()
    {
        return $this->morphTo();
    }

    public function transaction()
    {
        return $this->belongsTo(AccTransaction::class, 'acc_transaction_id');
    }
}
