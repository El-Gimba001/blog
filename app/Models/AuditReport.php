<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditReport extends Model
{
    use HasFactory;

    // In App\Models\AuditReport.php
protected $fillable = [
    'emporia_id',
    'auditor_id',
    'report_title',
    'findings',
    'amount_adjusted',
    'profit_adjusted',
    'affected_products',
    'manager_approval_status',
    'manager_approved_by',
    'manager_approved_at',
    'manager_comments',
    'report_sent_at',
    'status',
    'admin_approval_status',
    'admin_approved_by',
    'admin_approved_at',
    'admin_comments',
];

    protected $casts = [
    'affected_products'   => 'array',

    // ✅ DATE CASTS (THIS FIXES YOUR ERROR)
    'manager_approved_at' => 'datetime',
    'admin_approved_at'   => 'datetime',
    'report_sent_at'      => 'datetime',
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