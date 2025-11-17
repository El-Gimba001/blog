<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index()
    {
        // Fetch products to populate dropdown
        $products = Product::all();
        return view('Transaction.sales-entry', compact('products'));
    }
}