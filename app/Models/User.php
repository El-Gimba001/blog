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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        // REMOVE the role enum cast completely
    ];

    // Simple string-based role checks
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
}