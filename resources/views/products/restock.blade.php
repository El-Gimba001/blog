@extends('layouts.app')
@section('title', 'Restock Product - InventoryPro')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <i data-lucide="package-plus" class="w-5 h-5 text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Restock Product</h1>
                    <p class="text-gray-600 mt-1">Update existing product quantities and pricing</p>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <!-- Session Messages -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 rounded-lg border border-green-200 flex items-center">
                    <i data-lucide="check-circle" class="w-5 h-5 text-green-500 mr-3 flex-shrink-0"></i>
                    <span class="text-green-800 text-sm">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 rounded-lg border border-red-200 flex items-center">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-500 mr-3 flex-shrink-0"></i>
                    <span class="text-red-800 text-sm">{{ session('error') }}</span>
                </div>
            @endif

            <form action="{{ route('products.updateStock') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Product Selection Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i data-lucide="package" class="w-5 h-5 text-indigo-600"></i>
                        Select Product
                    </h3>
                    
                    <div class="relative">
                        <select id="productSelect" name="product_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none transition-colors duration-200">
                            <option value="">-- Choose Product --</option>
                            @foreach ($products as $p)
                                <option value="{{ $p->id }}"
                                    data-quantity="{{ $p->quantity }}"
                                    data-cost="{{ $p->cost_price }}"
                                    data-sell="{{ $p->selling_price }}"
                                    data-profit="{{ $p->profit }}">
                                    {{ $p->name }} ({{ $p->category ?? 'Uncategorized' }})
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i data-lucide="chevron-down" class="h-5 w-5 text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Product Preview Card -->
                <div id="productPreview" class="hidden mt-6">
                    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl border border-indigo-200 overflow-hidden">
                        <div class="px-6 py-4 bg-indigo-600 bg-opacity-10 border-b border-indigo-200">
                            <h4 class="text-lg font-semibold text-indigo-800 flex items-center gap-2">
                                <i data-lucide="info" class="w-5 h-5"></i>
                                Product Summary
                                <span id="productNameTag" class="text-sm font-normal text-indigo-600 ml-2"></span>
                            </h4>
                        </div>
                        
                        <div class="p-6">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                <div class="bg-white rounded-lg p-4 border border-gray-100 shadow-sm">
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                                            <i data-lucide="box" class="w-4 h-4 text-emerald-600"></i>
                                        </div>
                                        <span class="text-sm text-gray-600">Current Quantity</span>
                                    </div>
                                    <p id="currentQty" class="text-2xl font-bold text-emerald-700">—</p>
                                </div>

                                <div class="bg-white rounded-lg p-4 border border-gray-100 shadow-sm">
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                            <i data-lucide="dollar-sign" class="w-4 h-4 text-red-600"></i>
                                        </div>
                                        <span class="text-sm text-gray-600">Cost Price</span>
                                    </div>
                                    <p class="text-2xl font-bold text-red-700">
                                        ₦<span id="currentCost">—</span>
                                    </p>
                                </div>

                                <div class="bg-white rounded-lg p-4 border border-gray-100 shadow-sm">
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i data-lucide="tag" class="w-4 h-4 text-blue-600"></i>
                                        </div>
                                        <span class="text-sm text-gray-600">Selling Price</span>
                                    </div>
                                    <p class="text-2xl font-bold text-blue-700">
                                        ₦<span id="currentSell">—</span>
                                    </p>
                                </div>

                                <div class="bg-white rounded-lg p-4 border border-gray-100 shadow-sm">
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                            <i data-lucide="trending-up" class="w-4 h-4 text-green-600"></i>
                                        </div>
                                        <span class="text-sm text-gray-600">Profit per Unit</span>
                                    </div>
                                    <p class="text-2xl font-bold text-green-700">
                                        ₦<span id="currentProfit">—</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Update Information Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i data-lucide="refresh-cw" class="w-5 h-5 text-indigo-600"></i>
                        Update Information
                    </h3>
                    
                    <div class="space-y-6">
                        <!-- Additional Quantity -->
                        <div>
                            <label for="additional_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                Additional Quantity to Add
                            </label>
                            <div class="relative">
                                <input type="number" name="additional_quantity" id="additional_quantity"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                                    placeholder="Enter quantity to add">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i data-lucide="plus" class="h-5 w-5 text-gray-400"></i>
                                </div>
                            </div>
                            <p class="mt-2 text-xs text-gray-500">Enter the number of units to add to current stock</p>
                        </div>

                        <!-- Optional Price Updates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="cost_price" class="block text-sm font-medium text-gray-700 mb-2">
                                    New Cost Price (₦)
                                </label>
                                <div class="relative">
                                    <input type="number" name="cost_price" id="cost_price" step="0.01"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                                        placeholder="Optional">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <i data-lucide="dollar-sign" class="h-5 w-5 text-gray-400"></i>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="selling_price" class="block text-sm font-medium text-gray-700 mb-2">
                                    New Selling Price (₦)
                                </label>
                                <div class="relative">
                                    <input type="number" name="selling_price" id="selling_price" step="0.01"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                                        placeholder="Optional">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <i data-lucide="tag" class="h-5 w-5 text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-end pt-6 border-t border-gray-200">
                    <a href="{{ route('products.index') }}" 
                       class="flex items-center justify-center gap-2 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        Back to Stock List
                    </a>
                    <button type="submit"
                           class="flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-200">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        Update Stock
                    </button>
                </div>
            </form>
        </div>

        <!-- Help Information -->
        <div class="mt-8 bg-blue-50 rounded-2xl border border-blue-200 p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center gap-2">
                <i data-lucide="help-circle" class="w-5 h-5 text-blue-600"></i>
                Quick Tips
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="bg-white rounded-lg p-4 border border-blue-100">
                    <div class="flex items-center gap-2 mb-2">
                        <i data-lucide="info" class="w-4 h-4 text-blue-600"></i>
                        <span class="font-semibold text-gray-900">Quantity Update</span>
                    </div>
                    <p class="text-gray-600 text-xs">Enter only the additional units. The system will add this to the current quantity.</p>
                </div>
                <div class="bg-white rounded-lg p-4 border border-blue-100">
                    <div class="flex items-center gap-2 mb-2">
                        <i data-lucide="dollar-sign" class="w-4 h-4 text-green-600"></i>
                        <span class="font-semibold text-gray-900">Price Updates</span>
                    </div>
                    <p class="text-gray-600 text-xs">Leave price fields blank if you don't want to change the current prices.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Initialize Lucide icons
    if (window.lucide) {
        lucide.createIcons();
    }

    const productSelect = document.getElementById('productSelect');
    const preview = document.getElementById('productPreview');
    const qty = document.getElementById('currentQty');
    const cost = document.getElementById('currentCost');
    const sell = document.getElementById('currentSell');
    const profit = document.getElementById('currentProfit');

    productSelect.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        if (selected.value) {
            preview.classList.remove('hidden');
            qty.textContent = selected.dataset.quantity;
            cost.textContent = parseFloat(selected.dataset.cost).toFixed(2);
            sell.textContent = parseFloat(selected.dataset.sell).toFixed(2);
            profit.textContent = parseFloat(selected.dataset.profit).toFixed(2);
        } else {
            preview.classList.add('hidden');
        }
    });

    // Auto-focus additional quantity when product is selected
    productSelect.addEventListener('change', function() {
        if (this.value) {
            document.getElementById('additional_quantity').focus();
        }
    });
});
</script>

<style>
select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
}
</style>
@endsection