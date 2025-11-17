@extends('layouts.app')
@section('title', 'Alter Customer')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col">
    <!-- Header -->
    <header class="flex justify-between items-center px-6 py-4 bg-white shadow-sm sticky top-0 z-10">
        <h1 class="text-xl md:text-2xl font-semibold text-gray-800 flex items-center gap-2">
            <i data-lucide="users" class="w-6 h-6 text-yellow-600"></i>
            Alter Customer
        </h1>

        <a href="{{ route('ledger.customer-ledger') }}"
           class="flex items-center gap-1 text-indigo-600 hover:text-indigo-800 transition text-sm md:text-base">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Ledger
        </a>
    </header>

    <!-- Main Section -->
    <main class="flex-1 p-4 md:p-8 space-y-8">
        <!-- Search Bar -->
        <div class="bg-white p-4 rounded-xl shadow flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div class="flex items-center gap-2">
                <i data-lucide="search" class="w-5 h-5 text-gray-500"></i>
                <input id="searchInput" type="text" placeholder="Search customer..."
                       class="w-full md:w-64 border-gray-300 rounded-lg p-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            </div>
            <button id="refreshBtn"
                    class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium shadow">
                <i data-lucide="refresh-ccw" class="w-4 h-4"></i> Refresh List
            </button>
        </div>

        <!-- Customer Table -->
        <div class="bg-white p-6 rounded-2xl shadow overflow-x-auto">
            <table class="min-w-full text-sm text-left text-gray-700 border">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="py-3 px-4">Customer Code</th>
                        <th class="py-3 px-4">Customer Name</th>
                        <th class="py-3 px-4">Phone</th>
                        <th class="py-3 px-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="customerTable">
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-4">CUS1425051</td>
                        <td class="py-3 px-4">Aliyu Ibrahim</td>
                        <td class="py-3 px-4">08123456789</td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex justify-center items-center space-x-3">
                                <button class="editBtn bg-amber-500 hover:bg-amber-600 text-white px-3 py-1.5 rounded-lg text-xs flex items-center gap-1 shadow-sm">
                                    <i data-lucide='edit' class="w-4 h-4"></i> Edit
                                </button>
                                <button class="deleteBtn bg-red-700 hover:bg-red-800 text-white px-3 py-1.5 rounded-lg text-xs flex items-center gap-1 shadow-sm">
                                    <i data-lucide='trash-2' class="w-4 h-4"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-4">CUS1425252</td>
                        <td class="py-3 px-4">Grace Johnson</td>
                        <td class="py-3 px-4">07098765432</td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex justify-center items-center space-x-3">
                                <button class="editBtn bg-amber-500 hover:bg-amber-600 text-white px-3 py-1.5 rounded-lg text-xs flex items-center gap-1 shadow-sm">
                                    <i data-lucide='edit' class="w-4 h-4"></i> Edit
                                </button>
                                <button class="deleteBtn bg-red-700 hover:bg-red-800 text-white px-3 py-1.5 rounded-lg text-xs flex items-center gap-1 shadow-sm">
                                    <i data-lucide='trash-2' class="w-4 h-4"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    if (window.lucide) lucide.createIcons();

    const searchInput = document.getElementById("searchInput");
    const rows = document.querySelectorAll("#customerTable tr");

    // 🔍 Search Filter
    searchInput.addEventListener("input", function () {
        const val = this.value.toLowerCase();
        rows.forEach(row => {
            const name = row.children[1].textContent.toLowerCase();
            row.style.display = name.includes(val) ? "" : "none";
        });
    });

    // ✏ Edit Button Simulation
    document.querySelectorAll(".editBtn").forEach(btn => {
        btn.addEventListener("click", () => alert("Edit feature coming soon!"));
    });

    // 🗑 Delete Button Simulation
    document.querySelectorAll(".deleteBtn").forEach(btn => {
        btn.addEventListener("click", () => {
            if (confirm("Are you sure you want to delete this customer?")) {
                btn.closest("tr").remove();
            }
        });
    });
});

</script>
@endsection