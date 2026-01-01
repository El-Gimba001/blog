<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'emporia_id', // KEEP AS IS
        'phone', // ADD THIS ONLY IF NOT EXISTS
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // KEEP EXISTING RELATIONSHIP (just add comments)
    /**
     * Relationship: User's primary emporium
     */
    public function emporia()
    {
        return $this->belongsTo(Emporia::class, 'emporia_id');
    }

    /**
     * Relationship: User can access multiple emporia (additional stores)
     * Uses pivot table: user_emporia
     */
    public function accessibleEmporia()
    {
        return $this->belongsToMany(Emporia::class, 'user_emporia', 'user_id', 'emporia_id');
    }

    /**
     * Relationship: Administrator manages multiple emporia
     */
    public function managedEmporia()
    {
        return $this->hasMany(Emporia::class, 'administrator_id');
        
    }

    // 🔥 ADD THESE CRITICAL METHODS (using existing column names)
    
    /**
     * Check if user has access to a specific emporium/store
     */
    public function hasEmporiaAccess($emporiaId)
    {
        if ($this->isAdministrator()) {
            return true; // Admins can access all stores
        }
        
        // Check primary emporium access
        if ($this->emporia_id == $emporiaId) {
            return true;
        }
        
        // Check accessible emporia via pivot table
        return $this->accessibleEmporia()->where('emporia.id', $emporiaId)->exists();
    }

    /**
     * Get all emporia user can access
     */
    public function getAllAccessibleEmporia()
    {
        if ($this->isAdministrator()) {
            return Emporia::all();
        }
        
        $emporia = collect();
        
        // Add primary emporium
        if ($this->emporia) {
            $emporia->push($this->emporia);
        }
        
        // Add accessible emporia
        if ($this->accessibleEmporia->isNotEmpty()) {
            $emporia = $emporia->merge($this->accessibleEmporia);
        }
        
        // Return unique emporia
        return $emporia->unique('id');
    }

    /**
     * Get primary emporium name for display
     */
    public function getEmporiaNameAttribute()
    {
        if (!$this->emporia) {
            return 'No Emporium Assigned';
        }
        return $this->emporia->name;
    }

    /**
     * Check if user has any emporium assigned
     */
    public function hasEmporia()
    {
        return !is_null($this->emporia_id);
    }

    // ✅ KEEP ALL YOUR EXISTING ROLE METHODS (they're perfect!)
    public function isAdministrator()
    {
        return $this->role === 'administrator';
    }

    public function isManager()
    {
        return $this->role === 'manager';
    }

    public function isAuditor()
    {
        return $this->role === 'auditor';
    }

    public function isSalesUser()
    {
        return $this->role === 'sales_user';
    }

    public function isStoreManager()
    {
        return $this->role === 'store_manager';
    }

    // 🔧 ADDITIONAL HELPER METHODS
    
    /**
     * Check if user can hold transactions (managers only)
     */
    public function canHoldTransactions()
    {
        return $this->isManager() || $this->isAdministrator();
    }

    /**
     * Check if user can approve audit reports
     */
    public function canApproveAudits()
    {
        return $this->isManager() || $this->isAdministrator();
    }

    /**
     * Check if user can manage customers
     */
    public function canManageCustomers()
    {
        return in_array($this->role, ['administrator', 'manager', 'store_manager', 'sales_user']);
    }

    /**
     * Check if user can view reports
     */
    public function canViewReports()
    {
        return in_array($this->role, ['administrator', 'manager', 'store_manager', 'auditor']);
    }

    public function assignedEmporia()
    {
        return $this->hasOne(Emporia::class, 'auditor_id');
    }
}