<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'emporia_id', 'auditor_id', 'report_title', 'findings',
        'amount_adjusted', 'profit_adjusted', 'affected_products', 'status'
    ];

    protected $casts = [
        'affected_products' => 'array'
    ];

    public function emporia()
    {
        return $this->belongsTo(Emporia::class);
    }

    public function auditor()
    {
        return $this->belongsTo(User::class, 'auditor_id');
    }
}