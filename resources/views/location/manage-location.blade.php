@extends('layouts.app')
@section('title', 'Manage Locations')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col">
    <!-- Header -->
    <header class="flex justify-between items-center px-6 py-4 bg-white shadow-sm sticky top-0 z-10">
        <h1 class="text-xl md:text-2xl font-semibold text-gray-800 flex items-center gap-2">
            <i data-lucide="map-pin-house" class="w-6 h-6 text-blue-600"></i>
            Manage Locations
        </h1>

        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-1 text-indigo-600 hover:text-indigo-800 transition text-sm md:text-base">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to Dashboard
        </a>
    </header>

    <!-- Main Content -->
    <main class="flex-1 p-4 md:p-8 space-y-8">
        <!-- Add New Location -->
        <section class="bg-white rounded-2xl shadow-md p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Add New Location</h2>
                    <p class="text-gray-500 text-sm">Specify a store, branch, or warehouse where activities take place.</p>
                </div>
            </div>

            <form id="addLocationForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 text-sm mb-1">Location Name</label>
                    <input type="text" id="locationName" class="w-full border-gray-300 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. Main Warehouse">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm mb-1">Description (Optional)</label>
                    <input type="text" id="locationDesc" class="w-full border-gray-300 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. Handles electronics stock">
                </div>

                <div class="col-span-1 md:col-span-2 flex justify-end">
                    <button type="button" id="addLocationBtn"
                        class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm text-sm font-medium">
                        <i data-lucide="plus" class="w-4 h-4"></i> Add Location
                    </button>
                </div>
            </form>
        </section>

        <!-- Location List -->
        <section class="bg-white rounded-2xl shadow-md p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Existing Locations</h2>
                <button id="refreshList"
                    class="flex items-center gap-2 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg">
                    <i data-lucide="refresh-ccw" class="w-4 h-4"></i> Refresh
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-gray-700 border">
                    <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="py-3 px-4">Location Name</th>
                            <th class="py-3 px-4">Description</th>
                            <th class="py-3 px-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="locationTable">
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">Main Warehouse</td>
                            <td class="py-3 px-4">Handles all bulk storage</td>
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
                            <td class="py-3 px-4">Branch Office</td>
                            <td class="py-3 px-4">Retail and transactions</td>
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
        </section>
    </main>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    if (window.lucide) lucide.createIcons();

    const addBtn = document.getElementById("addLocationBtn");
    const nameInput = document.getElementById("locationName");
    const descInput = document.getElementById("locationDesc");
    const table = document.getElementById("locationTable");

    addBtn.addEventListener("click", () => {
        const name = nameInput.value.trim();
        const desc = descInput.value.trim();

        if (!name) {
            alert("⚠ Please enter a location name.");
            nameInput.focus();
            return;
        }

        const row = document.createElement("tr");
        row.className = "border-b hover:bg-gray-50";
        row.innerHTML = `
            <td class="py-3 px-4">${name}</td>
            <td class="py-3 px-4">${desc || "—"}</td>
            <td class="py-3 px-4 text-center">
                <div class="flex justify-center items-center space-x-3">
                    <button class="editBtn bg-amber-500 hover:bg-amber-600 text-white px-3 py-1.5 rounded-lg text-xs flex items-center gap-1 shadow-sm">
                        <i data-lucide='edit' class="w-4 h-4"></i> Edit
                    </button>
                    <button class="deleteBtn bg-red-700 hover:bg-red-800 text-white px-3 py-1.5 rounded-lg text-xs flex items-center gap-1 shadow-sm">
                        <i data-lucide='trash-2' class="w-4 h-4"></i> Delete
                    </button>
                </div>
            </td>`;

        table.appendChild(row);
        nameInput.value = "";
        descInput.value = "";
        if (window.lucide) lucide.createIcons();
    });

    document.addEventListener("click", e => {
        if (e.target.closest(".deleteBtn")) {
            if (confirm("Are you sure you want to delete this location?")) {
                e.target.closest("tr").remove();
            }
        } else if (e.target.closest(".editBtn")) {
            alert("Edit location feature coming soon!");
        }
    });
    if (window.lucide) lucide.createIcons();

});
</script>
@endsection