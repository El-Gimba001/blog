<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Emporia;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DailySalesReportController extends Controller
{
    /**
     * Display daily sales report
     */
    public function index()
    {
        try {
            // Use Main Store (ID: 1) for sale users
            $emporiaId = 1;
            $emporia = Emporia::find($emporiaId);
            
            if (!$emporia) {
                return redirect()->back()->with('error', 'Main store not found.');
            }
            
            // Get today's date
            $today = now()->format('Y-m-d');
            
            // Get today's transactions
            $transactions = Transaction::with(['transactionItems.product'])
                ->where('emporia_id', $emporiaId)
                ->whereDate('created_at', $today)
                ->where('transaction_type', 'sale')
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Calculate statistics
            $totalSales = $transactions->sum('total_amount');
            $totalProfit = $transactions->sum('total_profit');
            $totalItems = $transactions->sum(function($transaction) {
                return $transaction->transactionItems->sum('quantity');
            });
            
            return view('transactions.daily', [
                'transactions' => $transactions,
                'totalSales' => $totalSales,
                'totalProfit' => $totalProfit,
                'totalItems' => $totalItems,
                'today' => $today,
                'emporia' => $emporia
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error loading daily sales report: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load daily sales report.');
        }
    }
    
    /**
     * Show sales for a specific date
     */
    public function show($date)
    {
        try {
            // Validate date format
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                return redirect()->back()->with('error', 'Invalid date format.');
            }
            
            $emporiaId = 1;
            $emporia = Emporia::find($emporiaId);
            
            if (!$emporia) {
                return redirect()->back()->with('error', 'Main store not found.');
            }
            
            // Get transactions for specific date
            $transactions = Transaction::with(['transactionItems.product'])
                ->where('emporia_id', $emporiaId)
                ->whereDate('created_at', $date)
                ->where('transaction_type', 'sale')
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Calculate statistics
            $totalSales = $transactions->sum('total_amount');
            $totalProfit = $transactions->sum('total_profit');
            $totalItems = $transactions->sum(function($transaction) {
                return $transaction->transactionItems->sum('quantity');
            });
            
            return view('reports.daily-show', [
                'transactions' => $transactions,
                'totalSales' => $totalSales,
                'totalProfit' => $totalProfit,
                'totalItems' => $totalItems,
                'date' => $date,
                'emporia' => $emporia
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error loading daily sales for date ' . $date . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load daily sales for ' . $date);
        }
    }
    
    /**
     * Generate daily sales report (PDF/Excel)
     */
    public function generate(Request $request)
    {
        try {
            $date = $request->input('date', now()->format('Y-m-d'));
            $format = $request->input('format', 'pdf');
            
            $emporiaId = 1;
            $emporia = Emporia::find($emporiaId);
            
            if (!$emporia) {
                return redirect()->back()->with('error', 'Main store not found.');
            }
            
            // Get transactions for the date
            $transactions = Transaction::with(['transactionItems.product'])
                ->where('emporia_id', $emporiaId)
                ->whereDate('created_at', $date)
                ->where('transaction_type', 'sale')
                ->orderBy('created_at', 'desc')
                ->get();
            
            $totalSales = $transactions->sum('total_amount');
            $totalProfit = $transactions->sum('total_profit');
            
            if ($format === 'excel') {
                // For Excel export (you'll need to install a package like maatwebsite/excel)
                return $this->exportExcel($date, $transactions, $totalSales, $totalProfit);
            } else {
                // For PDF export (you'll need to install a package like barryvdh/laravel-dompdf)
                return $this->exportPDF($date, $transactions, $totalSales, $totalProfit, $emporia);
            }
            
        } catch (\Exception $e) {
            \Log::error('Error generating daily sales report: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to generate report.');
        }
    }
    
    /**
     * Export to PDF (placeholder - install dompdf package)
     */
    private function exportPDF($date, $transactions, $totalSales, $totalProfit, $emporia)
    {
        // For now, just show a view
        return view('reports.daily-pdf', [
            'transactions' => $transactions,
            'totalSales' => $totalSales,
            'totalProfit' => $totalProfit,
            'date' => $date,
            'emporia' => $emporia
        ]);
    }
    
    /**
     * Export to Excel (placeholder)
     */
    private function exportExcel($date, $transactions, $totalSales, $totalProfit)
    {
        // For now, redirect to the show page
        return redirect()->route('reports.daily.sales.show', ['date' => $date])
            ->with('info', 'Excel export feature requires maatwebsite/excel package.');
    }
    
    /**
     * Get daily sales data for charts/API
     */
    public function getDailyData(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        
        $emporiaId = 1;
        
        $transactions = Transaction::where('emporia_id', $emporiaId)
            ->whereDate('created_at', $date)
            ->where('transaction_type', 'sale')
            ->get();
        
        $hourlyData = [];
        for ($i = 0; $i < 24; $i++) {
            $hour = str_pad($i, 2, '0', STR_PAD_LEFT);
            $hourlyData[$hour] = $transactions->filter(function($transaction) use ($hour) {
                return $transaction->created_at->format('H') == $hour;
            })->sum('total_amount');
        }
        
        return response()->json([
            'date' => $date,
            'total_sales' => $transactions->sum('total_amount'),
            'total_transactions' => $transactions->count(),
            'hourly_data' => $hourlyData,
            'payment_methods' => $transactions->groupBy('payment_method')->map->count()
        ]);
    }
}