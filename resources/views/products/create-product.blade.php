@extends('layouts.app')
@section('title', 'Add Product')

@section('content')

@if (session('success'))
    <div class="mb-4 p-3 text-sm bg-green-100 text-green-700 rounded-lg">
        {{ session('success') }}
    </div>
@endif

<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-xl p-8 mt-10">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Add New Product</h2>

    <form action="{{ route('products.store') }}" method="POST" class="space-y-5">
        @csrf

        <!-- Product Name -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Product Name</label>
            <input type="text" name="name" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
        </div>

        <!-- Category -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Category</label>
            <input type="text" name="category"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
        </div>

        <!-- Unit & Quantity -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 font-medium mb-1">Unit</label>
                <input type="text" name="unit" placeholder="e.g., pcs, kg"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Quantity</label>
                <input type="number" name="quantity" min="0" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
            </div>
        </div>

        <!-- Cost, Selling Price & Profit -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 font-medium mb-1">Cost Price (₦)</label>
                <input type="number" id="cost_price" name="cost_price" step="0.01" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Selling Price (₦)</label>
                <input type="number" id="selling_price" name="selling_price" step="0.01" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
            </div>
        </div>

        <!-- Profit (auto-calculated) -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Profit (₦)</label>
            <input type="number" id="profit" name="profit" readonly
                class="w-full bg-gray-100 border border-gray-300 rounded-lg px-4 py-2 text-green-700 font-semibold focus:outline-none">
        </div>

        <!-- Submit Button -->
        <button type="submit"
            class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition duration-200">
            Add Product
        </button>
    </form>

    <!-- Stock List Button -->
    <div class="mt-10 flex justify-center">
        <a href="{{ route('products.index') }}"
            class="flex items-center gap-2 bg-indigo-600 text-white px-6 py-3 rounded-xl shadow hover:bg-indigo-700 transition-all">
            <i data-lucide="list" class="w-5 h-5"></i>
            <span class="font-medium">Back to stock</span>
        </a>
    </div>
</div>

<!-- Script -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    const costInput = document.getElementById("cost_price");
    const sellInput = document.getElementById("selling_price");
    const profitInput = document.getElementById("profit");

    function calculateProfit() {
        const cost = parseFloat(costInput.value) || 0;
        const sell = parseFloat(sellInput.value) || 0;
        const profit = sell - cost;
        profitInput.value = profit.toFixed(2);
    }

    costInput.addEventListener("input", calculateProfit);
    sellInput.addEventListener("input", calculateProfit);

    if (window.lucide) lucide.createIcons();
});
</script>

@endsection