@extends('layouts.app')
@section('title', 'View All Customers')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col">
    <!-- Header -->
    <header class="flex justify-between items-center px-6 py-4 bg-white shadow-sm sticky top-0 z-10">
        <h1 class="text-xl md:text-2xl font-semibold text-gray-800 flex items-center gap-2">
            <i data-lucide="users" class="w-6 h-6 text-yellow-600"></i>
            View All Customers
        </h1>

        <a href="{{ route('ledger.customer-ledger') }}"
           class="flex items-center gap-1 text-indigo-600 hover:text-indigo-800 transition text-sm md:text-base">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to Ledger
        </a>
    </header>

    <!-- Main -->
    <main class="flex-1 p-6 space-y-8">
        <!-- Filter/Search -->
        <section class="bg-white rounded-2xl shadow-md p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <h2 class="text-lg font-semibold text-gray-800">All Registered Customers</h2>
                <input type="text" id="searchCustomer"
                    placeholder="🔍 Search by name or phone..."
                    class="border border-gray-300 rounded-lg px-4 py-2 w-full md:w-80 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
        </section>

        <!-- Customer List -->
        <section class="bg-white rounded-2xl shadow-md p-6 overflow-x-auto">
            <table class="min-w-full text-sm text-left text-gray-700 border-collapse">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="py-3 px-4">Customer Code</th>
                        <th class="py-3 px-4">Customer Name</th>
                        <th class="py-3 px-4">Phone</th>
                        <th class="py-3 px-4">Outstanding (₦)</th>
                        <th class="py-3 px-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="customerBody">
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-4 font-medium text-gray-700">CUS10302501</td>
                        <td class="py-3 px-4">Aliyu Ibrahim</td>
                        <td class="py-3 px-4">08123456789</td>
                        <td class="py-3 px-4 font-semibold text-red-600">₦4,500</td>
                        <td class="py-3 px-4 text-center">
                            <button class="record-btn bg-indigo-600 hover:bg-indigo-700 text-white text-xs px-3 py-1 rounded-lg"
                                data-name="Aliyu Ibrahim">Record Payment</button>
                        </td>
                    </tr>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-4 font-medium text-gray-700">CUS10302602</td>
                        <td class="py-3 px-4">Grace Johnson</td>
                        <td class="py-3 px-4">09022334455</td>
                        <td class="py-3 px-4 font-semibold text-red-600">₦2,000</td>
                        <td class="py-3 px-4 text-center">
                            <button class="record-btn bg-indigo-600 hover:bg-indigo-700 text-white text-xs px-3 py-1 rounded-lg"
                                data-name="Grace Johnson">Record Payment</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>

    <!-- Modal -->
    <div id="paymentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-xl p-6 w-11/12 md:w-1/3">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Record Customer Payment</h3>
            <p class="text-sm text-gray-600 mb-2">Customer: <span id="modalCustomerName" class="font-medium text-gray-800"></span></p>
            <div class="space-y-3">
                <input type="number" id="amountPaid" placeholder="Amount Paid (₦)" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-indigo-500">
                <input type="number" id="outstanding" placeholder="Outstanding Balance (₦)" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-indigo-500">
                <select id="paymentMode" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-indigo-500">
                    <option value="">Select Payment Mode</option>
                    <option value="cash">Cash</option>
                    <option value="transfer">Transfer</option>
                    <option value="pos">POS</option>
                </select>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button id="closeModal" class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300">Cancel</button>
                <button id="savePayment" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Save & Print</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    if (window.lucide) lucide.createIcons();

    const modal = document.getElementById("paymentModal");
    const closeModal = document.getElementById("closeModal");
    const saveBtn = document.getElementById("savePayment");
    const modalCustomer = document.getElementById("modalCustomerName");
    const recordBtns = document.querySelectorAll(".record-btn");

    recordBtns.forEach(btn => {
        btn.addEventListener("click", () => {
            modalCustomer.textContent = btn.dataset.name;
            modal.classList.remove("hidden");
        });
    });

    closeModal.addEventListener("click", () => modal.classList.add("hidden"));

    saveBtn.addEventListener("click", () => {
        const name = modalCustomer.textContent;
        const amount = document.getElementById("amountPaid").value;
        const outstanding = document.getElementById("outstanding").value;
        const mode = document.getElementById("paymentMode").value;

        if (!amount || !mode) {
            alert("⚠ Please fill all payment details.");
            return;
        }

        const receipt = `
            Customer: ${name}\n
            Amount Paid: ₦${amount}\n
            Outstanding: ₦${outstanding}\n
            Payment Mode: ${mode.toUpperCase()}\n
            Date: ${new Date().toLocaleString()}
        `;

        alert("✅ Payment saved!\n\n🧾 Receipt:\n" + receipt);
        modal.classList.add("hidden");
    });

    // Search Filter
    const searchInput = document.getElementById("searchCustomer");
    const rows = document.querySelectorAll("#customerBody tr");

    searchInput.addEventListener("input", function() {
        const val = this.value.toLowerCase();
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(val) ? "" : "none";
        });
    });
});
</script>
@endsection