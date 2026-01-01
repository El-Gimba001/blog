<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [

        'emporia_id', 'reference', 'customer_name', 'location',
        'payment_type', 'total_amount', 'status', 'user_id'
    ];

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
