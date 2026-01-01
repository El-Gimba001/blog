<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagerReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'emporia_id', 'manager_id', 'start_date', 'end_date',
        'total_sales', 'total_profit', 'transaction_count', 'sales_data'
    ];

    protected $casts = [
        'sales_data' => 'array',
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function emporia()
    {
        return $this->belongsTo(Emporia::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}