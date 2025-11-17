<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emporia extends Model
{
    use HasFactory;

    // Explicitly set the table name since it's irregular
    protected $table = 'emporia';

    protected $fillable = [
        'name', 'location', 'code', 'administrator_id', 'manager_id', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function administrator()
    {
        return $this->belongsTo(User::class, 'administrator_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_emporia')
                    ->withPivot('role', 'is_default');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function auditReports()
    {
        return $this->hasMany(AuditReport::class);
    }

    public function stockAdjustments()
    {
        return $this->hasMany(StockAdjustment::class);
    }
}

class Emporia extends Model
{
    use HasFactory;
    
    // ADD THIS LINE - explicitly set table name
    protected $table = 'emporia';
    
    // ... rest of your code
}