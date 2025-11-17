@extends('layouts.app')
@section('title', 'Add Existing Stock')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-xl p-8 mt-10">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
        <i data-lucide="package-plus" class="w-6 h-6 text-indigo-600"></i>
        Add Existing Stock
    </h2>

    @if (session('success'))
        <div class="mb-4 p-3 text-sm bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('products.updateStock') }}" method="POST" class="space-y-5">
        @csrf

        <!-- Select Product -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Select Product</label>
            <select id="productSelect" name="product_id" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                <option value="">-- Choose Product --</option>
                @foreach ($products as $p)
                    <option value="{{ $p->id }}"
                        data-quantity="{{ $p->quantity }}"
                        data-cost="{{ $p->cost_price }}"
                        data-sell="{{ $p->selling_price }}"
                        data-profit="{{ $p->profit }}">
                        {{ $p->name }} <!--  ({{ $p->category ??'Uncategorized' }}) -->
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Product Info Preview -->
        <div id="productPreview" class="hidden mt-6 bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden transition-all duration-300">
            <div class="bg-indigo-600 text-white px-5 py-3 flex items-center justify-between">
                <h3 class="text-lg font-semibold flex items-center gap-2">
                    <i data-lucide="info" class="w-5 h-5"></i> Product Summary
                </h3>
                <span id="productNameTag" class="text-sm italic opacity-90"></span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-5 text-gray-800">
                <div class="flex flex-col items-start">
                    <span class="text-gray-500 text-sm">Current Quantity</span>
                    <span id="currentQty" class="text-xl font-semibold text-emerald-600">—</span>
                </div>

                <div class="flex flex-col items-start">
                    <span class="text-gray-500 text-sm">Cost Price</span>
                    <span class="text-xl font-semibold text-red-500">₦<span id="currentCost">—</span></span>
                </div>

                <div class="flex flex-col items-start">
                    <span class="text-gray-500 text-sm">Selling Price</span>
                    <span class="text-xl font-semibold text-indigo-600">₦<span id="currentSell">—</span></span>
                </div>

                <div class="flex flex-col items-start">
                    <span class="text-gray-500 text-sm">Profit per Unit</span>
                    <span class="text-xl font-semibold text-green-600">₦<span id="currentProfit">—</span></span>
                </div>
            </div>

            <div class="bg-gray-50 px-5 py-3 text-sm text-gray-500 border-t">
                <i data-lucide="clock" class="inline w-4 h-4 mr-1"></i>
                <span>Last updated: <span id="lastUpdated">—</span></span>
            </div>
        </div>

        <!-- Additional Quantity -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Additional Quantity</label>
            <input tsype="number" name="additional_quantity"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
        </div>

        <!-- Optional price updates -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 font-medium mb-1">New Cost Price (₦)</label>
                <input type="number" name="cost_price" step="0.01"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">New Selling Price (₦)</label>
                <input type="number" name="selling_price" step="0.01"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
            </div>
        </div>

        <button type="submit"
                class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition duration-200">
            Update Stock
        </button>
    </form>

    <!-- Back Button -->
   <div class="mt-10 flex justify-center">
        <a href="{{ route('products.index') }}"
            class="flex items-center gap-2 bg-indigo-600 text-white px-6 py-3 rounded-xl shadow hover:bg-indigo-700 transition-all">
            <i data-lucide="list" class="w-5 h-5"></i>
            <span class="font-medium">Back to stock</span>
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
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

    if (window.lucide) lucide.createIcons();
});
</script>
@endsection