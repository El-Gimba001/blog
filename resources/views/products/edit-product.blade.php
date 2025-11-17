@extends('layouts.app')
@section('title', 'Edit Product')

@section('content')

@if (session('success'))
    <div class="mb-4 p-3 text-sm bg-green-100 text-green-700 rounded-lg">
        {{ session('success') }}
    </div>
@endif

<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-xl p-8 mt-10">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Edit Product Details</h2>

    <form action="{{ route('products.update', $product->id) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        {{-- Product Reference --}}
        <div>
            <label class="block text-gray-700 font-medium mb-1">Reference Number</label>
            <input type="text" value="{{ $product->reference }}" readonly
                   class="w-full bg-gray-100 border border-gray-300 rounded-lg px-4 py-2 text-gray-700 cursor-not-allowed">
        </div>

        {{-- Product Name (Read-only) --}}
        <div>
            <label class="block text-gray-700 font-medium mb-1">Product Name</label>
            <input type="text" value="{{ $product->name }}" readonly
                   class="w-full bg-gray-100 border border-gray-300 rounded-lg px-4 py-2 text-gray-700 cursor-not-allowed">
        </div>

        {{-- Unit --}}
        <div>
            <label class="block text-gray-700 font-medium mb-1">Unit</label>
            <input type="text" name="unit" value="{{ $product->unit }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
        </div>

        {{-- Quantity --}}
        <div>
            <label class="block text-gray-700 font-medium mb-1">Quantity</label>
            <input type="number" name="quantity" min="0" value="{{ $product->quantity }}" required
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
        </div>

        {{-- Prices --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 font-medium mb-1">Cost Price (₦)</label>
                <input type="number" name="cost_price" step="0.01" value="{{ $product->cost_price }}" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none"
                       id="cost_price">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Selling Price (₦)</label>
                <input type="number" name="selling_price" step="0.01" value="{{ $product->selling_price }}" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none"
                       id="selling_price">
            </div>
        </div>

        {{-- Profit (auto-calculated, read-only) --}}
        <div>
            <label class="block text-gray-700 font-medium mb-1">Profit (₦)</label>
            <input type="number" id="profit" value="{{ $product->profit }}" readonly
                   class="w-full bg-gray-100 border border-gray-300 rounded-lg px-4 py-2 text-gray-700 cursor-not-allowed">
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition duration-200">
            Update Product
        </button>
    </form>

    <!-- Back to Stock List -->
    <div class="mt-10 flex justify-center">
        <a href="{{ route('products.index') }}"
           class="flex items-center gap-2 bg-indigo-600 text-white px-6 py-3 rounded-xl shadow hover:bg-indigo-700 transition-all">
            <i data-lucide="list" class="w-5 h-5"></i>
            <span class="font-medium">Back to stock</span>
        </a>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const costInput = document.getElementById("cost_price");
    const sellInput = document.getElementById("selling_price");
    const profitInput = document.getElementById("profit");

    function calculateProfit() {
        const cost = parseFloat(costInput.value) || 0;
        const sell = parseFloat(sellInput.value) || 0;
        profitInput.value = (sell - cost).toFixed(2);
    }

    costInput.addEventListener("input", calculateProfit);
    sellInput.addEventListener("input", calculateProfit);

    if (window.lucide) lucide.createIcons();
});
</script>

@endsection