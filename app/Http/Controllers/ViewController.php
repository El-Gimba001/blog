<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function customerLedger()
    {
        return view('ledger.customer-ledger');
    }
}