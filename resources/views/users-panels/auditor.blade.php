@extends('layouts.app')
@section('title', 'Auditor Panel')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i data-lucide="clipboard-check" class="w-8 h-8 text-purple-600"></i>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Auditor Panel</h1>
                    <p class="text-gray-600">Over-Stock Management & Audit Reporting - {{ auth()->user()->name }}</p>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-medium">
                    Auditor
                </span>
                
                <!-- Logout Button -->
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Coming Soon Message -->
    <div class="p-6">
        <div class="bg-white rounded-xl shadow p-8 text-center">
            <i data-lucide="construction" class="w-16 h-16 text-yellow-500 mx-auto mb-4"></i>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Auditor Panel Under Construction</h2>
            <p class="text-gray-600 mb-6">
                The auditor panel with over-stock management, audit reporting, and stock adjustment features 
                is currently being developed. This will include:
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-purple-50 p-4 rounded-lg">
                    <i data-lucide="package-x" class="w-8 h-8 text-purple-600 mb-2"></i>
                    <h3 class="font-semibold text-purple-900">Over-Stock Management</h3>
                    <p class="text-purple-700 text-sm">Adjust system quantities to match physical counts</p>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg">
                    <i data-lucide="file-text" class="w-8 h-8 text-blue-600 mb-2"></i>
                    <h3 class="font-semibold text-blue-900">Audit Reports</h3>
                    <p class="text-blue-700 text-sm">Generate detailed audit reports automatically</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <i data-lucide="trending-down" class="w-8 h-8 text-green-600 mb-2"></i>
                    <h3 class="font-semibold text-green-900">Stock Adjustments</h3>
                    <p class="text-green-700 text-sm">Real-time stock deduction and reporting</p>
                </div>
            </div>

            <!-- Update the message since tables are now created -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 max-w-2xl mx-auto">
                <h3 class="font-semibold text-green-800 mb-2">Great News! Database Tables Created ✅</h3>
                <p class="text-green-700 text-sm">
                    The database tables (<strong>audit_reports</strong>, <strong>stock_adjustments</strong>, and <strong>emporia</strong>) 
                    have been successfully created! We're now working on the functional features.
                </p>
            </div>

            <!-- Quick Actions Section -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4 max-w-2xl mx-auto">
                <a href="{{ route('products.index') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg transition flex items-center justify-center gap-2">
                    <i data-lucide="package" class="w-5 h-5"></i>
                    View Products
                </a>
                <a href="{{ route('daily.transactions') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg transition flex items-center justify-center gap-2">
                    <i data-lucide="receipt" class="w-5 h-5"></i>
                    View Transactions
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        lucide.createIcons();
    });
</script>
@endsection