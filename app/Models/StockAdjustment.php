<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'emporia_id', 'auditor_id', 'product_id', 'old_quantity', 
        'new_quantity', 'adjustment', 'reason', 'amount_impact', 'profit_impact'
    ];

    public function emporia()
    {
        return $this->belongsTo(Emporia::class);
    }

    public function auditor()
    {
        return $this->belongsTo(User::class, 'auditor_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}