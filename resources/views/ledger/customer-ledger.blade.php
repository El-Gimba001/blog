@extends('layouts.app')
@section('title', 'Customer Ledger')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col">
    <!-- Header -->
    <header class="flex justify-between items-center px-6 py-4 bg-white shadow-sm sticky top-0 z-10">
        <h1 class="text-xl md:text-2xl font-semibold text-gray-800 flex items-center gap-2">
            <i data-lucide="notebook-text" class="w-6 h-6 text-yellow-600"></i>
            Customer Ledger
        </h1>

        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-1 text-indigo-600 hover:text-indigo-800 transition text-sm md:text-base">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to Dashboard
        </a>
    </header>

    <!-- Ledger Content -->
    <main class="flex-1 p-4 md:p-8 space-y-8">
        <!-- Ledger Overview -->
        <section class="bg-white rounded-2xl shadow-md p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Ledger Overview</h2>
                    <p class="text-gray-500 text-sm">Quick summary of customer activities and balances.</p>
                </div>
            </div>

            <!-- Ledger Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-indigo-50 p-4 rounded-xl text-center">
                    <h3 class="text-lg font-semibold text-indigo-700">₦89,550</h3>
                    <p class="text-sm text-gray-600">Total Sales</p>
                </div>
                <div class="bg-emerald-50 p-4 rounded-xl text-center">
                    <h3 class="text-lg font-semibold text-emerald-700">₦12,300</h3>
                    <p class="text-sm text-gray-600">Pending Balance</p>
                </div>
                <div class="bg-yellow-50 p-4 rounded-xl text-center">
                    <h3 class="text-lg font-semibold text-yellow-700">14</h3>
                    <p class="text-sm text-gray-600">Active Customers</p>
                </div>
                <div class="bg-sky-50 p-4 rounded-xl text-center">
                    <h3 class="text-lg font-semibold text-sky-700">28</h3>
                    <p class="text-sm text-gray-600">Transactions</p>
                </div>
            </div>
            <!-- Action Buttons (Enhanced UI) -->
            <div class="mt-10 flex flex-col sm:flex-row flex-wrap gap-4 justify-center md:justify-between">
                <!-- <div class="flex flex-wrap gap-3 mt-3 md:mt-0"> -->
                    <a href="{{ route('customers.create') }}"
                       class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 text-sm font-medium shadow transition">
                       <i data-lucide='user-plus' class="w-4 h-4"></i>
                       New Customer
                    </a>

                    <a href="{{ route('customers.alter') }}"
                       class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 text-sm font-medium shadow transition">
                       <i data-lucide='edit' class="w-4 h-4"></i>
                       Alter Customer
                    </a>
                <!-- </div> -->

                <a href="{{ route('customers.view-all') }}"
                   class="flex items-center justify-center gap-3 px-6 py-3 rounded-xl text-white font-semibold text-sm shadow-md hover:shadow-lg transition bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700">
                   <i data-lucide="users" class="w-5 h-5"></i>
                    View All Customers
                </a>

                <a href="{{ route('customers.track') }}"
                   class="flex items-center justify-center gap-3 px-6 py-3 rounded-xl text-white font-semibold text-sm shadow-md hover:shadow-lg transition bg-gradient-to-r from-yellow-500 to-amber-600 hover:from-yellow-600 hover:to-amber-700">
                   <i data-lucide="activity" class="w-5 h-5"></i>
                    Track Transactions
                </a>
            </div>
        </section>
    </main>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    if (window.lucide) lucide.createIcons();
});
</script>
@endsection