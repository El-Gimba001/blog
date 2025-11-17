@extends('layouts.app')
@section('title', 'Transaction Tracking')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col">
    <!-- Header -->
    <header class="flex justify-between items-center px-6 py-4 bg-white shadow-sm sticky top-0 z-10">
        <h1 class="text-xl md:text-2xl font-semibold text-gray-800 flex items-center gap-2">
            <i data-lucide="file-search" class="w-6 h-6 text-blue-600"></i>
            Transaction Tracking
        </h1>

        <a href="{{ route('ledger.customer-ledger') }}"
           class="flex items-center gap-1 text-indigo-600 hover:text-indigo-800 transition text-sm md:text-base">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to Ledger
        </a>
    </header>

    <!-- Content -->
    <main class="flex-1 p-4 md:p-8 space-y-6">
        <!-- Filter Section -->
        <section class="bg-white p-6 rounded-xl shadow-md">
            <h2 class="text-lg font-semibold mb-3 text-gray-800 flex items-center gap-2">
                <i data-lucide="user" class="w-5 h-5 text-indigo-600"></i>
                Select Customer
            </h2>
            <select id="customerSelect" class="w-full md:w-1/2 border-gray-300 rounded-lg p-2 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Choose a customer...</option>
                <option value="Aliyu Ibrahim">Aliyu Ibrahim</option>
                <option value="Grace Johnson">Grace Johnson</option>
                <option value="Chinedu Obi">Chinedu Obi</option>
            </select>
        </section>

        <!-- Transaction Overview -->
        <section id="transactionSection" class="hidden bg-white p-6 rounded-xl shadow-md">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Transaction Overview</h2>
                <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm flex items-center gap-2">
                    <i data-lucide="printer" class="w-4 h-4"></i> Print Report
                </button>
            </div>

            <!-- Summary -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-indigo-50 p-4 rounded-xl text-center">
                    <h3 class="text-lg font-semibold text-indigo-700">₦48,000</h3>
                    <p class="text-sm text-gray-600">Total Purchased</p>
                </div>
                <div class="bg-emerald-50 p-4 rounded-xl text-center">
                    <h3 class="text-lg font-semibold text-emerald-700">₦30,000</h3>
                    <p class="text-sm text-gray-600">Total Paid</p>
                </div>
                <div class="bg-rose-50 p-4 rounded-xl text-center">
                    <h3 class="text-lg font-semibold text-rose-700">₦18,000</h3>
                    <p class="text-sm text-gray-600">Outstanding</p>
                </div>
                <div class="bg-yellow-50 p-4 rounded-xl text-center">
                    <h3 class="text-lg font-semibold text-yellow-700">12</h3>
                    <p class="text-sm text-gray-600">Transactions</p>
                </div>
            </div>

            <!-- Transaction Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-gray-700 border">
                    <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="py-3 px-4">Date</th>
                            <th class="py-3 px-4">Product</th>
                            <th class="py-3 px-4">Quantity</th>
                            <th class="py-3 px-4">Payment Type</th>
                            <th class="py-3 px-4">Amount (₦)</th>
                        </tr>
                    </thead>
                    <tbody id="transactionBody">
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">01 Nov 2025</td>
                            <td class="py-3 px-4">Detergent (5pcs)</td>
                            <td class="py-3 px-4">5</td>
                            <td class="py-3 px-4 capitalize">Cash</td>
                            <td class="py-3 px-4 font-semibold">₦10,000.00</td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">29 Oct 2025</td>
                            <td class="py-3 px-4">Rice Bag (50kg)</td>
                            <td class="py-3 px-4">2</td>
                            <td class="py-3 px-4 capitalize">Transfer</td>
                            <td class="py-3 px-4 font-semibold">₦30,000.00</td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">27 Oct 2025</td>
                            <td class="py-3 px-4">Cooking Oil (5L)</td>
                            <td class="py-3 px-4">1</td>
                            <td class="py-3 px-4 capitalize">POS</td>
                            <td class="py-3 px-4 font-semibold">₦8,000.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    if (window.lucide) lucide.createIcons();

    const customerSelect = document.getElementById("customerSelect");
    const section = document.getElementById("transactionSection");

    customerSelect.addEventListener("change", () => {
        section.classList.toggle("hidden", customerSelect.value === "");
    });
});
</script>
@endsection