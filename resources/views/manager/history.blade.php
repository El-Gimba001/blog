@extends('layouts.app')
@section('title', 'Audit Reports')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <!-- Header with Back and History buttons -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Pending Audit Reports</h1>
            <p class="text-gray-600">Review and approve audit submissions from your team</p>
        </div>
        
        <div class="flex items-center gap-3">
            <!-- History Button -->
            <a href="{{ route('manager.audit.history') }}"
               class="px-4 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 flex items-center gap-2">
                <i data-lucide="history" class="w-4 h-4"></i>
                View History
            </a>
            
            <!-- Back to Dashboard Button -->
            <a href="{{ url()->previous() }}" 
               class="px-4 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 flex items-center gap-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="mb-6">
        <div class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-sm font-medium inline-flex items-center gap-2">
            <i data-lucide="filter" class="w-4 h-4"></i>
            {{ $reports->count() }} report{{ $reports->count() !== 1 ? 's' : '' }} pending
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4">
        <div class="flex items-center">
            <i data-lucide="check-circle" class="text-green-500 w-5 h-5 mr-3"></i>
            <p class="text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
        <div class="flex items-center">
            <i data-lucide="alert-circle" class="text-red-500 w-5 h-5 mr-3"></i>
            <p class="text-red-700 font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    @forelse($reports as $report)
        <!-- Report Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex justify-between items-start gap-4 mb-4">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 mb-2">
                        <h2 class="font-semibold text-lg text-gray-900 truncate">
                            {{ $report->report_title ?? 'Audit Report #' . $report->id }}
                        </h2>
                        
                        <!-- Status Badge -->
                        @if($report->manager_approval_status === 'pending')
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">
                            <i data-lucide="clock" class="w-3 h-3"></i>
                            Pending
                        </span>
                        @elseif($report->manager_approval_status === 'approved')
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                            <i data-lucide="check" class="w-3 h-3"></i>
                            Approved
                        </span>
                        @elseif($report->manager_approval_status === 'rejected')
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">
                            <i data-lucide="x" class="w-3 h-3"></i>
                            Rejected
                        </span>
                        @endif
                    </div>
                    
                    <!-- Financial Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500 font-medium mb-1">Amount Adjusted</p>
                            <p class="text-lg font-semibold text-gray-900">
                                ₦{{ number_format($report->amount_adjusted, 2) }}
                            </p>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500 font-medium mb-1">Profit Adjusted</p>
                            <p class="text-lg font-semibold text-gray-900">
                                ₦{{ number_format($report->profit_adjusted, 2) }}
                            </p>
                        </div>
                    </div>
                    
                    <!-- Meta Info -->
                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mt-3">
                        <span class="inline-flex items-center gap-1.5">
                            <i data-lucide="user" class="w-4 h-4"></i>
                            {{ $report->auditor->name ?? 'Unknown Auditor' }}
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                            {{ $report->created_at->format('M d, Y - h:i A') }}
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <i data-lucide="clock" class="w-4 h-4"></i>
                            {{ $report->created_at->diffForHumans() }}
                        </span>
                        @if($report->priority === 'high')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-100 text-red-700 rounded-lg text-sm font-medium">
                            <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                            High Priority
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Summary -->
            @if($report->summary)
            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                <p class="text-gray-700 text-sm">{{ $report->summary }}</p>
            </div>
            @endif

            <!-- Actions -->
            <div class="mt-6 pt-6 border-t border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="text-sm text-gray-500">
                        <i data-lucide="info" class="w-4 h-4 inline mr-1.5"></i>
                        @if($report->manager_approval_status === 'pending')
                            Take action on this report
                        @elseif($report->manager_approval_status === 'approved')
                            Report approved. Ready to send to administrator.
                        @elseif($report->manager_approval_status === 'rejected')
                            Report rejected.
                        @endif
                    </div>
                    
                    <div class="flex flex-wrap gap-3">
                        <!-- Reject Button (Only show when pending) -->
                        @if($report->manager_approval_status === 'pending')
                        <button onclick="openRejectModal({{ $report->id }}, '{{ addslashes($report->report_title ?? 'Audit Report') }}')"
                                class="px-4 py-2.5 border border-red-300 text-red-700 font-medium rounded-lg hover:bg-red-50 transition-colors duration-200 inline-flex items-center gap-2">
                            <i data-lucide="x" class="w-4 h-4"></i>
                            Reject
                        </button>
                        @endif
                        
                        <!-- Approve Button (Only show when pending) -->
                        @if($report->manager_approval_status === 'pending')
                        <form method="POST" action="{{ route('manager.audit.approve', $report->id) }}" class="inline" id="approveForm{{ $report->id }}">
                            @csrf
                            <button type="submit" 
                                    onclick="return confirm('Are you sure you want to approve this report?')"
                                    class="px-4 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors duration-200 inline-flex items-center gap-2">
                                <i data-lucide="check" class="w-4 h-4"></i>
                                Approve
                            </button>
                        </form>
                        @endif
                        
                        <!-- Send to Admin Button (Show when approved AND admin hasn't received it yet) -->
                        @if($report->manager_approval_status === 'approved' && $report->admin_approval_status === 'pending')
                        <form method="POST" action="{{ route('manager.audit.send-admin', $report->id) }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    onclick="return confirm('Send this report to administrator for final approval?')"
                                    class="px-4 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200 inline-flex items-center gap-2">
                                <i data-lucide="send" class="w-4 h-4"></i>
                                Send to Admin
                            </button>
                        </form>
                        @elseif($report->manager_approval_status === 'approved' && $report->admin_approval_status === 'sent')
                        <button class="px-4 py-2.5 bg-green-100 text-green-700 font-medium rounded-lg inline-flex items-center gap-2 cursor-default">
                            <i data-lucide="check" class="w-4 h-4"></i>
                            Sent to Admin
                        </button>
                        @elseif($report->manager_approval_status === 'pending')
                        <button class="px-4 py-2.5 bg-gray-300 text-gray-500 font-medium rounded-lg inline-flex items-center gap-2 cursor-not-allowed">
                            <i data-lucide="send" class="w-4 h-4"></i>
                            Approve First
                        </button>
                        @endif
                    </div>
                </div>
                
                <!-- Show rejection reason if rejected -->
                @if($report->manager_approval_status === 'rejected' && $report->manager_comments)
                <div class="mt-4 p-3 bg-red-50 rounded-lg">
                    <p class="text-sm font-medium text-red-700 mb-1 flex items-center gap-2">
                        <i data-lucide="message-square" class="w-4 h-4"></i>
                        Rejection Reason:
                    </p>
                    <p class="text-sm text-red-600">{{ $report->manager_comments }}</p>
                </div>
                @endif
            </div>
        </div>
    @empty
        <!-- Empty State -->
        <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <i data-lucide="check-circle" class="w-8 h-8 text-green-600"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">All Caught Up!</h3>
            <p class="text-gray-600 max-w-md mx-auto">
                There are no pending audit reports requiring your approval at this time.
            </p>
        </div>
    @endforelse
</div>

<!-- REJECTION MODAL -->
<div id="rejectModal" class="fixed inset-0 hidden bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4">
        <!-- Modal Header -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="x-circle" class="w-6 h-6 text-red-600"></i>
                </div>
                <div>
                    <h2 class="font-semibold text-lg text-gray-900" id="modalTitle">Reject Report</h2>
                    <p class="text-sm text-gray-500">Provide a reason for rejection</p>
                </div>
            </div>
        </div>

        <!-- Modal Form -->
        <form method="POST" id="rejectForm">
            @csrf
            <div class="p-6">
                <input type="hidden" name="report_id" id="report_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Reason for Rejection <span class="text-red-500">*</span>
                    </label>
                    <textarea name="manager_comments" 
                              id="rejectReason"
                              required
                              rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors duration-200 resize-none"
                              placeholder="Please provide a clear and specific reason for rejecting this audit report..."></textarea>
                    <p class="text-xs text-gray-500 mt-2">Minimum 5 characters required</p>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" 
                            onclick="closeRejectModal()" 
                            class="px-4 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2.5 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center gap-2">
                        <i data-lucide="x" class="w-4 h-4"></i>
                        Confirm Rejection
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script>
    // Initialize Lucide icons
    lucide.createIcons();
    
    // Reject Modal Functions
    function openRejectModal(reportId, reportTitle) {
        const form = document.getElementById('rejectForm');
        form.action = `/manager/audit-reports/${reportId}/reject`;
        
        // Set report ID in hidden field (for form submission)
        document.getElementById('report_id').value = reportId;
        
        // Update modal title
        document.getElementById('modalTitle').textContent = `Reject: ${reportTitle}`;
        
        // Show modal
        document.getElementById('rejectModal').classList.remove('hidden');
        
        // Focus on textarea
        setTimeout(() => {
            document.getElementById('rejectReason').focus();
        }, 100);
    }
    
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('rejectForm').reset();
    }
    
    // Close modal when clicking outside or pressing ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeRejectModal();
    });
    
    document.getElementById('rejectModal').addEventListener('click', (e) => {
        if (e.target.id === 'rejectModal') closeRejectModal();
    });
    
    // Validate rejection reason before submission
    document.getElementById('rejectForm').addEventListener('submit', function(e) {
        const reason = document.getElementById('rejectReason').value.trim();
        if (reason.length < 5) {
            e.preventDefault();
            alert('Please provide a rejection reason with at least 5 characters.');
            document.getElementById('rejectReason').focus();
        }
    });
    
    // Real-time UI update after approval (optional - can remove if using page reload)
    function approveReport(reportId) {
        if (confirm('Are you sure you want to approve this report?')) {
            const form = document.getElementById(`approveForm${reportId}`);
            form.submit();
        }
    }
</script>
@endpush
@endsection