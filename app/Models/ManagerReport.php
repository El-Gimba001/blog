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

        // Get manager's emporia
        $emporia = Auth::user()->emporia()->first();
        
        if (!$emporia) {
            return response()->json([
                'success' => false,
                'message' => 'No emporia assigned to this manager.'
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
            return response()->json([
                'success' => false,
                'message' => 'Failed to send report: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getManagerReports()
    {
        // For manager: get their own reports
        if (Auth::user()->isManager()) {
            $reports = ManagerReport::with('emporia')
                ->where('manager_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();
        } 
        // For admin: get all reports from their emporia
        else if (Auth::user()->isAdministrator()) {
            $emporiaIds = Auth::user()->emporia()->pluck('id');
            $reports = ManagerReport::with(['emporia', 'manager'])
                ->whereIn('emporia_id', $emporiaIds)
                ->orderBy('created_at', 'desc')
                ->get();
        } 
        else {
            $reports = collect();
        }

        return response()->json($reports);
    }

    public function getTodayReportData()
    {
        // This would calculate real data from your database
        // For now, returning sample data - replace with actual calculations
        
        $today = Carbon::today();
        $emporia = Auth::user()->emporia()->first();

        if (!$emporia) {
            return response()->json([]);
        }

        // Sample data - REPLACE WITH ACTUAL CALCULATIONS FROM YOUR DATABASE
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
    }
}