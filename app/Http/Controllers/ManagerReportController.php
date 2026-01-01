<?php

namespace App\Http\Controllers;

use App\Models\ManagerReport;
use App\Models\Emporia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ManagerReportController extends Controller
{
    public function sendReport(Request $request)
    {
        $request->validate([
            'total_sales' => 'required|numeric',
            'total_profit' => 'required|numeric',
            'transaction_count' => 'required|integer',
            'sales_data' => 'required|array',
            'notes' => 'nullable|string'
        ]);

        // Get the manager's assigned emporia
        $emporia = Emporia::where('manager_id', Auth::id())->first();
        
        if (!$emporia) {
            // Try to get any active emporia as fallback
            $emporia = Emporia::where('is_active', true)->first();
        }

        if (!$emporia) {
            return response()->json([
                'success' => false,
                'message' => 'No emporia available. Please contact administrator.'
            ], 400);
        }

        try {
            // Calculate date range (last 7 days)
            $endDate = Carbon::today();
            $startDate = Carbon::today()->subDays(6);

            $report = ManagerReport::create([
                'emporia_id' => $emporia->id,
                'manager_id' => Auth::id(),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_sales' => $request->total_sales,
                'total_profit' => $request->total_profit,
                'transaction_count' => $request->transaction_count,
                'sales_data' => $request->sales_data,
                'is_reconstructed' => false
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Report successfully sent to Administrator!',
                'report_id' => $report->id,
                'report_date' => $endDate->format('M j, Y')
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to create report: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send report. Please try again.'
            ], 500);
        }
    }

    public function getManagerReports()
    {
        try {
            if (Auth::user()->isManager()) {
                $reports = ManagerReport::with('emporia')
                    ->where('manager_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else if (Auth::user()->isAdministrator()) {
                $reports = ManagerReport::with(['emporia', 'manager'])
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                $reports = collect();
            }

            return response()->json($reports);

        } catch (\Exception $e) {
            \Log::error('Error getting reports: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }

    public function getTodayReportData()
    {
        try {
            // Sample data - replace with actual calculations
            $reportData = [
                'total_sales' => 452300,
                'total_profit' => 85250,
                'transaction_count' => 47,
                'sales_data' => [
                    'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Today'],
                    'profits' => [75200, 58300, 89400, 67100, 112300, 128500, 85250],
                    'sales' => [201500, 158000, 245000, 189000, 312000, 385000, 265000]
                ]
            ];

            return response()->json($reportData);

        } catch (\Exception $e) {
            \Log::error('Error getting today report data: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }
}