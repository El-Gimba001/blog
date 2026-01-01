@extends('layouts.app')
@section('title', 'Add New Product - InventoryPro')

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
                    <h1 class="text-3xl font-bold text-gray-900">Add New Product</h1>
                    <p class="text-gray-600 mt-1">Register a new product in your inventory</p>
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

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 rounded-lg border border-red-200">
                    <div class="flex items-center gap-2 mb-2">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
                        <h4 class="text-sm font-medium text-red-800">Please fix the following errors:</h4>
                    </div>
                    <ul class="text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="flex items-center gap-2">
                                <i data-lucide="circle" class="w-2 h-2 text-red-500"></i>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('products.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Product Information Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i data-lucide="package" class="w-5 h-5 text-indigo-600"></i>
                        Product Information
                    </h3>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Product Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Product Name *
                            </label>
                            <div class="relative">
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                                    placeholder="Enter product name">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i data-lucide="tag" class="h-5 w-5 text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                Category
                            </label>
                            <div class="relative">
                                <input type="text" name="category" id="category" value="{{ old('category') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                                    placeholder="e.g., Electronics, Clothing, Food">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i data-lucide="layers" class="h-5 w-5 text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Unit & Quantity -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">
                                    Unit
                                </label>
                                <div class="relative">
                                    <input type="text" name="unit" id="unit" value="{{ old('unit') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                                        placeholder="e.g., pcs, kg, liters">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <i data-lucide="ruler" class="h-5 w-5 text-gray-400"></i>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                    Initial Quantity *
                                </label>
                                <div class="relative">
                                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity', 0) }}" min="0" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                                        placeholder="Enter quantity">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <i data-lucide="hash" class="h-5 w-5 text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pricing Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i data-lucide="dollar-sign" class="w-5 h-5 text-indigo-600"></i>
                        Pricing Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Cost Price -->
                        <div>
                            <label for="cost_price" class="block text-sm font-medium text-gray-700 mb-2">
                                Cost Price (₦) *
                            </label>
                            <div class="relative">
                                <input type="number" id="cost_price" name="cost_price" value="{{ old('cost_price') }}" step="0.01" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                                    placeholder="0.00">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i data-lucide="dollar-sign" class="h-5 w-5 text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Selling Price -->
                        <div>
                            <label for="selling_price" class="block text-sm font-medium text-gray-700 mb-2">
                                Selling Price (₦) *
                            </label>
                            <div class="relative">
                                <input type="number" id="selling_price" name="selling_price" value="{{ old('selling_price') }}" step="0.01" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                                    placeholder="0.00">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i data-lucide="tag" class="h-5 w-5 text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profit Display -->
                    <div class="mt-6">
                        <label for="profit" class="block text-sm font-medium text-gray-700 mb-2">
                            Profit per Unit (₦)
                        </label>
                        <div class="relative">
                            <input type="number" id="profit" name="profit" readonly
                                class="w-full px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-lg text-emerald-700 font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200"
                                placeholder="Auto-calculated">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i data-lucide="trending-up" class="h-5 w-5 text-emerald-500"></i>
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-emerald-600">
                            <i data-lucide="info" class="w-3 h-3 inline mr-1"></i>
                            Profit is automatically calculated as: Selling Price - Cost Price
                        </p>
                    </div>
                </div>

                <!-- Additional Options -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i data-lucide="settings" class="w-5 h-5 text-indigo-600"></i>
                        Additional Settings
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Store Assignment (for admins) -->
                        @if(auth()->user()->isAdministrator())
                        <div>
                            <label for="emporia_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Assign to Store
                            </label>
                            <div class="relative">
                                <select name="emporia_id" id="emporia_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none transition-colors duration-200">
                                    <option value="">Default Store</option>
                                    @foreach(\App\Models\Emporia::where('is_active', true)->get() as $store)
                                        <option value="{{ $store->id }}" {{ old('emporia_id') == $store->id ? 'selected' : '' }}>
                                            {{ $store->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i data-lucide="chevron-down" class="h-5 w-5 text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Reorder Point -->
                        <div>
                            <label for="reorder_point" class="block text-sm font-medium text-gray-700 mb-2">
                                Low Stock Alert Level
                            </label>
                            <div class="relative">
                                <input type="number" name="reorder_point" id="reorder_point" value="{{ old('reorder_point', 10) }}" min="1"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                                    placeholder="Default: 10">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i data-lucide="bell" class="h-5 w-5 text-gray-400"></i>
                                </div>
                            </div>
                            <p class="mt-2 text-xs text-gray-500">Alert when quantity falls below this number</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-end pt-6 border-t border-gray-200">
                    <a href="{{ route('products.index') }}" 
                       class="flex items-center justify-center gap-2 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        Cancel
                    </a>
                    <button type="submit"
                           class="flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-200">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        Add Product
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div class="bg-white rounded-lg p-4 border border-blue-100">
                    <div class="flex items-center gap-2 mb-2">
                        <i data-lucide="package" class="w-4 h-4 text-blue-600"></i>
                        <span class="font-semibold text-gray-900">Product Details</span>
                    </div>
                    <p class="text-gray-600 text-xs">Ensure product names are clear and descriptive for easy identification.</p>
                </div>
                <div class="bg-white rounded-lg p-4 border border-blue-100">
                    <div class="flex items-center gap-2 mb-2">
                        <i data-lucide="dollar-sign" class="w-4 h-4 text-green-600"></i>
                        <span class="font-semibold text-gray-900">Pricing</span>
                    </div>
                    <p class="text-gray-600 text-xs">Set accurate prices. Profit is calculated automatically.</p>
                </div>
                <div class="bg-white rounded-lg p-4 border border-blue-100">
                    <div class="flex items-center gap-2 mb-2">
                        <i data-lucide="bell" class="w-4 h-4 text-yellow-600"></i>
                        <span class="font-semibold text-gray-900">Stock Alerts</span>
                    </div>
                    <p class="text-gray-600 text-xs">Set reorder points to receive low stock notifications.</p>
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

    const costInput = document.getElementById("cost_price");
    const sellInput = document.getElementById("selling_price");
    const profitInput = document.getElementById("profit");

    function calculateProfit() {
        const cost = parseFloat(costInput.value) || 0;
        const sell = parseFloat(sellInput.value) || 0;
        const profit = sell - cost;
        profitInput.value = profit.toFixed(2);
        
        // Update profit field color based on value
        if (profit < 0) {
            profitInput.classList.remove('bg-emerald-50', 'border-emerald-200', 'text-emerald-700');
            profitInput.classList.add('bg-red-50', 'border-red-200', 'text-red-700');
        } else if (profit > 0) {
            profitInput.classList.remove('bg-red-50', 'border-red-200', 'text-red-700');
            profitInput.classList.add('bg-emerald-50', 'border-emerald-200', 'text-emerald-700');
        } else {
            profitInput.classList.remove('bg-emerald-50', 'border-emerald-200', 'text-emerald-700', 'bg-red-50', 'border-red-200', 'text-red-700');
            profitInput.classList.add('bg-gray-50', 'border-gray-200', 'text-gray-700');
        }
    }

    costInput.addEventListener("input", calculateProfit);
    sellInput.addEventListener("input", calculateProfit);

    // Initial calculation
    calculateProfit();
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