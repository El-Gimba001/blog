<?php

namespace App\Http\Controllers;

use App\Models\AuditReport;
use Illuminate\Http\Request;

class AuditReportController extends Controller
{
    // Auditor submits report
    public function store(Request $request) {
        $emporiaId = auth()->user()->emporia_id;
        $products = $request->affected_products;
        $amountAdjusted = collect($products)->sum('total_sales');
        $profitAdjusted = collect($products)->sum('profit');
        AuditReport::create([
            'auditor_id' => auth()->id(),
            'emporia_id' => $emporiaId,
            'report_title' => $request->report_title,
            'findings' => $request->findings,
            'amount_adjusted' => $amountAdjusted,
            'profit_adjusted' => $profitAdjusted,
            'affected_products' => json_encode($products),

            // 🔑 IMPORTANT
            'manager_approval_status' => 'pending',
            'admin_approval_status' => 'pending',
            'status' => 'submitted',
        ]);
    }

    // Manager views pending audits
    

    // Manager approves/rejects
    public function managerDecision(Request $request, AuditReport $report)
    {
        if ($report->manager_approval_status !== 'pending'){
            abort(403, 'This report has already been reviewed.');
        }
        $report->update([
            'manager_approval_status' => $request->decision, // approved | rejected
            'manager_approved_by' => auth()->id(),
            'manager_approved_at' => now(),
            'manager_comments' => $request->comments,
            'status' => $request->decision === 'approved'
                ? 'manager_approved'
                : 'manager_rejected',
        ]);
    }
    // Admin views manager-approved audits
    public function pendingForAdmin()
    {
        $reports = AuditReport::where('manager_approval_status', 'approved')
            ->where('admin_approval_status', 'pending')
            ->whereNotNull('report_sent_at') // 🔑 THIS IS THE KEY
            ->latest()
            ->get();

        return view('admin.audit-reports', compact('reports'));
    }

        // Admin final decision
        public function adminDecision(Request $request, AuditReport $report)
    {
        $report->update([
            'admin_approval_status' => $request->decision,
            'admin_approved_by' => auth()->id(),
            'admin_approved_at' => now(),
            'admin_comments' => $request->comments,
            'status' => $request->decision === 'approved'
                ? 'admin_approved'
                : 'admin_rejected',
        ]);
    }

        // Auditor views his reports + statuses
        public function myReports()
    {
        return AuditReport::where('auditor_id', auth()->id())
            ->latest()
            ->get();
    }


    /* =======================
       PENDING REPORTS PAGE
    ======================== */
    public function pending()
    {
        // Show ALL reports for this emporia
        $reports = AuditReport::where('emporia_id', auth()->user()->emporia_id)
            ->whereIn('manager_approval_status', ['pending', 'approved', 'rejected']) // Show all statuses
            ->latest()
            ->get();

        return view('manager.audit-reports', compact('reports'));
    }

    /* =======================
       HISTORY PAGE
    ======================== */
    public function history()
    {
        $reports = AuditReport::where('emporia_id', auth()->user()->emporia_id)
            ->where('manager_approval_status', '!=', 'pending')
            ->latest()
            ->get();

        return view('manager.history', compact('reports'));
    }

    /* =======================
       APPROVE
    ======================== */
    public function approve(AuditReport $report)
    {
        $report->update([
            'manager_approval_status' => 'approved',
            'manager_approved_by' => auth()->id(),
            'manager_approved_at' => now(),
        ]);

        return back()->with('success', 'Report approved');
    }

    /* =======================
       REJECT (WITH REASON)
    ======================== */
    public function reject(Request $request, AuditReport $report)
    {
        $request->validate([
            'manager_comments' => 'required|string|min:5'
        ]);

        $report->update([
            'manager_approval_status' => 'rejected',
            'manager_comments' => $request->manager_comments,
            'manager_approved_by' => auth()->id(),
            'manager_approved_at' => now(),
        ]);

        return back()->with('success', 'Report rejected');
    }

    /* =======================
       SEND TO ADMIN
       (ONLY AFTER APPROVAL)
    ======================== */
    public function sendToAdmin(AuditReport $report)
    {
        if ($report->manager_approval_status !== 'approved') {
            return back()->with('error', 'You must approve the report first.');
        }

        if (!is_null($report->report_sent_at)) {
            return back()->with('error', 'This report has already been sent.');
        }

        $report->update([
            'report_sent_at' => now(), // 👈 sets NOT NULL
        ]);

        return back()->with('success', 'Report sent to administrator.');
    }

}