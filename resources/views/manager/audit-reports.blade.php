@extends('layouts.app')
@section('title', 'Manager - Audit Reports')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Audit Reports</h1>
                <p class="text-gray-600">Review and take action on audit reports</p>
            </div>
            <a href="{{ route('manager.audit.history') }}"
               class="px-4 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center gap-2">
                <i data-lucide="history" class="w-4 h-4"></i>
                View History
            </a>
        </div>

        <!-- Stats -->
        <div class="inline-block px-3 py-1.5 bg-blue-100 text-blue-800 rounded-lg text-sm font-medium mb-6">
            {{ $reports->where('manager_approval_status', 'pending')->count() }} pending report(s)
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-300 rounded-lg p-4">
        <div class="flex items-center gap-3">
            <i data-lucide="check-circle" class="text-green-600 w-5 h-5"></i>
            <span class="text-green-800">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-100 border border-red-300 rounded-lg p-4">
        <div class="flex items-center gap-3">
            <i data-lucide="alert-circle" class="text-red-600 w-5 h-5"></i>
            <span class="text-red-800">{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <!-- Reports List -->
    @if($reports->count() > 0)
        @foreach($reports as $report)
        <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
            <!-- Report Header -->
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="font-semibold text-lg text-gray-900 mb-1">{{ $report->report_title }}</h3>
                    <div class="flex items-center gap-4 text-sm text-gray-600">
                        <span class="flex items-center gap-1">
                            <i data-lucide="user" class="w-4 h-4"></i>
                            {{ $report->auditor->name ?? 'Auditor' }}
                        </span>
                        <span class="flex items-center gap-1">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                            {{ $report->created_at->format('M d, Y') }}
                        </span>
                    </div>
                </div>
                
                <!-- Status -->
                <span class="px-3 py-1 rounded-full text-sm font-medium
                    @if($report->manager_approval_status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($report->manager_approval_status === 'approved') bg-green-100 text-green-800
                    @else bg-red-100 text-red-800 @endif">
                    {{ ucfirst($report->manager_approval_status) }}
                </span>
            </div>

            <!-- Financial Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="border border-gray-100 rounded-lg p-4">
                    <p class="text-sm text-gray-500 mb-1">Amount Adjusted</p>
                    <p class="text-xl font-bold text-gray-900">₦{{ number_format($report->amount_adjusted, 2) }}</p>
                </div>
                <div class="border border-gray-100 rounded-lg p-4">
                    <p class="text-sm text-gray-500 mb-1">Profit Adjusted</p>
                    <p class="text-xl font-bold text-gray-900">₦{{ number_format($report->profit_adjusted, 2) }}</p>
                </div>
            </div>

            <!-- Actions Section -->
            @if($report->manager_approval_status === 'pending')
            <!-- PENDING: Show Approve/Reject buttons -->
            <div class="border-t border-gray-100 pt-6">
                <div class="flex justify-between items-center">
                    <p class="text-gray-600">Take action:</p>
                    <div class="flex gap-3">
                        <!-- Reject Button -->
                        <button onclick="openRejectModal({{ $report->id }})"
                                class="px-4 py-2 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 flex items-center gap-2">
                            <i data-lucide="x" class="w-4 h-4"></i>
                            Reject
                        </button>
                        
                        <!-- Approve Button -->
                        <form method="POST" action="{{ route('manager.audit.approve', $report->id) }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    onclick="return confirm('Approve this report?')"
                                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2">
                                <i data-lucide="check" class="w-4 h-4"></i>
                                Approve
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @elseif($report->manager_approval_status === 'approved')
            <!-- APPROVED: Show Send to Admin button -->
            <div class="border-t border-gray-100 pt-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-600 mb-1">Report approved</p>
                        <p class="text-sm text-gray-500">
                        @if ($report->manager_approved_at)
                            Approved on: {{ $report->manager_approved_at->format('M d, Y - h:i A') }}
                        @endif
                        </p>
                    </div>
                    
                    <div>
                        @if(is_null($report->report_sent_at))
                            <!-- NOT YET SENT -->
                            <form method="POST" action="{{ route('manager.audit.send-admin', $report->id) }}">
                                @csrf
                                <button type="submit"
                                        onclick="return confirm('Send this report to administrator?')"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                                    <i data-lucide="send" class="w-4 h-4"></i>
                                    Send to Admin
                                </button>
                            </form>
                        @else
                            <!-- ALREADY SENT -->
                            <span class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg flex items-center gap-2">
                                <i data-lucide="check" class="w-4 h-4"></i>
                                Sent to Administrator
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            @elseif($report->manager_approval_status === 'rejected')
            <!-- REJECTED: Show rejection reason -->
            <div class="border-t border-gray-100 pt-6">
                <div>
                    <p class="text-gray-600 mb-2">Report rejected</p>
                    @if($report->manager_comments)
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <p class="text-sm font-medium text-red-700 mb-1">Rejection Reason:</p>
                        <p class="text-red-600">{{ $report->manager_comments }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
        @endforeach
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="check-circle" class="w-8 h-8 text-green-600"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Reports</h3>
            <p class="text-gray-600">There are no audit reports to review at this time.</p>
        </div>
    @endif
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg w-full max-w-md mx-4">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Reject Report</h3>
            <form method="POST" id="rejectForm">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason for rejection *</label>
                    <textarea name="manager_comments" 
                              required
                              rows="4"
                              class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Enter rejection reason..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" 
                            onclick="closeRejectModal()"
                            class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Reject Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script>
    lucide.createIcons();
    
    function openRejectModal(reportId) {
        document.getElementById('rejectForm').action = `/manager/audit-reports/${reportId}/reject`;
        document.getElementById('rejectModal').classList.remove('hidden');
    }
    
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }
    
    // Close modal on outside click
    document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target.id === 'rejectModal') closeRejectModal();
    });
</script>
@endpush
@endsection