@extends('layouts.app')
@section('title', 'Daily Transactions')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i data-lucide="receipt" class="w-8 h-8 text-emerald-600"></i>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Daily Transactions</h1>
                    <p class="text-gray-600">{{ now()->format('F j, Y') }}</p>
                </div>
            </div>
            <a href="{{ route('sales.entry') }}" 
               class="flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition">
                <i data-lucide="plus" class="w-4 h-4"></i>
                New Sale
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-xl shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Sales</p>
                    <p class="text-2xl font-bold text-gray-900">₦{{ number_format($dailyTotal, 2) }}</p>
                </div>
                <i data-lucide="dollar-sign" class="w-8 h-8 text-emerald-600"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Profit</p>
                    <p class="text-2xl font-bold text-gray-900">₦{{ number_format($totalProfit, 2) }}</p>
                </div>
                <i data-lucide="trending-up" class="w-8 h-8 text-green-600"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Transactions</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $transactions->count() }}</p>
                </div>
                <i data-lucide="shopping-cart" class="w-8 h-8 text-blue-600"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Items Sold</p>
                    <p class="text-2xl font-bold text-gray-900">  </p>

                </div>
                <i data-lucide="package" class="w-8 h-8 text-orange-600"></i>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="p-6">
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-mono text-sm text-gray-900">{{ $transaction->reference }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-gray-900">{{ $transaction->customer_name }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $transaction->payment_type === 'Cash' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $transaction->payment_type === 'POS' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $transaction->payment_type === 'Bank Transfer' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $transaction->payment_type === 'Credit' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                    {{ $transaction->payment_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-semibold text-gray-900">₦{{ number_format($transaction->total_amount, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-semibold text-green-600">₦{{ number_format($transaction->transactionItems->sum('profit'), 2) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $transaction->created_at->format('h:i A') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No transactions found for today.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize Lucide icons
document.addEventListener("DOMContentLoaded", function() {
    if (window.lucide) {
        lucide.createIcons();
    }
});
</script>
@endsection