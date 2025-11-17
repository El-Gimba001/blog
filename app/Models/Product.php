<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'name',
        'category',
        'unit',
        'quantity',
        'cost_price',
        'selling_price',
        'profit', 
    ];
}