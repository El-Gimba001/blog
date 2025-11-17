@extends('layouts.app')
@section('title', 'New Customer')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col">
    <!-- Header -->
    <header class="flex justify-between items-center px-6 py-4 bg-white shadow-sm sticky top-0">
        <h1 class="text-xl md:text-2xl font-semibold text-gray-800 flex items-center gap-2">
            <i data-lucide="user-plus" class="w-6 h-6 text-indigo-600"></i>
            New Customer
        </h1>
        <a href="{{ route('ledger.customer-ledger') }}"
           class="flex items-center gap-1 text-indigo-600 hover:text-indigo-800 text-sm md:text-base">
           <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Ledger
        </a>
    </header>

    <!-- Form Section -->
    <main class="flex-1 p-6 md:p-10">
        <div class="bg-white rounded-xl shadow-md p-6 max-w-2xl mx-auto space-y-6">
            <h2 class="text-lg font-semibold text-gray-800 border-b pb-2">Create New Customer</h2>

            <form id="createCustomerForm" class="space-y-5">
                <!-- Customer Code -->
                <div>
                    <label class="block text-gray-600 text-sm mb-1">Customer Code</label>
                    <input type="text" id="customerCode" readonly
                        class="w-full border-gray-300 rounded-lg p-2 bg-gray-100 font-semibold text-gray-700 cursor-not-allowed" />
                </div>

                <!-- Customer Name -->
                <div>
                    <label class="block text-gray-600 text-sm mb-1">Customer Name</label>
                    <input type="text" id="customerName" placeholder="Enter customer name"
                        class="w-full border-gray-300 rounded-lg p-2 focus:ring-indigo-500 focus:border-indigo-500" required />
                </div>

                <!-- Customer Phone -->
                <div>
                    <label class="block text-gray-600 text-sm mb-1">Customer Phone</label>
                    <input type="tel" id="customerPhone" placeholder="Enter customer phone"
                        class="w-full border-gray-300 rounded-lg p-2 focus:ring-indigo-500 focus:border-indigo-500" required />
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-lg font-semibold transition flex items-center justify-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Save Customer
                </button>
            </form>
        </div>
    </main>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    if (window.lucide) lucide.createIcons();

    const showFormBtn = document.getElementById("showFormBtn");
    const form = document.getElementById("createCustomerForm");
    const codeInput = document.getElementById("customerCode");

    // Make sure all elements exist
    if (showFormBtn && form && codeInput) {
        showFormBtn.addEventListener("click", function () {
            // Show form, hide button
            form.classList.remove("hidden");
            showFormBtn.classList.add("hidden");

            // Generate customer code
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            // For rank, just simulate a sequence 1–9
            const rank = Math.floor(Math.random() * 9) + 1;

            // Build code
            const customerCode = CUS${hours}${minutes}${seconds}${rank};

            // Set the value in the input
            codeInput.value = customerCode;
        });
    }
});
</script>
@endsection