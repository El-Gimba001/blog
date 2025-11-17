@extends('layouts.app')

@section('title', 'Stock List')

@section('content')
<div class="flex min-h-screen bg-gray-50">
    <!-- Collapsible Sidebar -->
    <aside id="stockSidebar"
        class="w-64 bg-white shadow-lg transition-all duration-300 flex flex-col overflow-hidden">
        <div class="p-5 border-b flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <i data-lucide="boxes" class="w-5 h-5 text-indigo-600"></i>
                <span class="sidebar-text">Stock Panel</span>
            </h2>
            <button onclick="toggleSidebar()" class="text-gray-600 hover:text-indigo-600">
                <i data-lucide="chevron-left" id="sidebarToggleIcon" class="w-5 h-5"></i>
            </button>
        </div>

        <nav class="p-4 space-y-2 flex-1">
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded hover:bg-indigo-50 text-gray-700">
                <i data-lucide="layout-dashboard" class="w-5 h-5 text-blue-600"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>

            <a href="{{ route('products.index') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded bg-indigo-50 text-indigo-700 font-medium">
                <i data-lucide="list" class="w-5 h-5 text-indigo-600"></i>
                <span class="sidebar-text">All Stocks</span>
            </a>

            <a href="{{ route('products.create') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded hover:bg-indigo-50 text-gray-700">
                <i data-lucide="plus-circle" class="w-5 h-5 text-indigo-600"></i>
                <span class="sidebar-text">New Stock</span>
            </a>

            <a href="{{ route('products.restock') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded hover:bg-indigo-50 text-gray-700">
                <i data-lucide="package-plus" class="w-5 h-5 text-indigo-600"></i>
                <span class="sidebar-text">Edit stock</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-indigo-50 text-gray-700">
                <i data-lucide="chart-line" class="w-5 h-5 text-green-600"></i>
                <span class="sidebar-text">Stocks with Profit</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-indigo-50 text-gray-700">
                <i data-lucide="map-pin" class="w-5 h-5 text-yellow-600"></i>
                <span class="sidebar-text">Stock per Location</span>
            </a>

        </nav>
    </aside>
                <!-- ['icon' => 'package-plus', 'label' => 'Add Existing stock', 'link' => route('products.restock'), 'color' => 'text-indigo-600'], -->

    <!-- Main Content -->
    <main class="flex-1 p-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800 flex items-center gap-2">
                <i data-lucide="list" class="w-6 h-6 text-indigo-600"></i>
                Stock List
            </h1>
            <!-- <a href="{{ route('products.create') }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                Add Product
            </a> -->
        </div>

        <!-- Product Table -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-100 text-gray-700 text-sm uppercase">
                    <tr>
                        <th class="px-4 py-3">Ref</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Category</th>
                        <th class="px-4 py-3">Quantity</th>
                        <th class="px-4 py-3">Cost Price (₦)</th>
                        <th class="px-4 py-3">Selling Price (₦)</th>
                        <th class="px-4 py-3 text-green-700">Profit (₦)</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse ($products as $product)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium text-gray-700">#{{ $product->reference }}</td>
                            <td class="px-4 py-3">{{ $product->name }}</td>
                            <td class="px-4 py-3">{{ $product->category ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $product->quantity }}</td>
                            <td class="px-4 py-3">₦{{ number_format($product->cost_price, 2) }}</td>
                            <td class="px-4 py-3">₦{{ number_format($product->selling_price, 2) }}</td>
                            <td class="px-4 py-3 text-green-600 font-semibold">
                                ₦{{ number_format($product->profit ?? ($product->selling_price - $product->cost_price), 2) }}
                            </td>
                            <td class="px-4 py-3 flex gap-2">
                                <a href="{{ route('products.edit', $product->id) }}" 
                                   class="text-blue-600 hover:text-blue-800" title="Edit">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-6 text-gray-500 italic">
                                No products available. Click "Add Product" to create one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- 
            THIS IS WHEN PAGE WAS OPENED WILL AUTOMATICALLY COLLAPSE SIDEBAR
<script>
function toggleSidebar() {
    const sidebar = document.getElementById('stockSidebar');
    const icon = document.getElementById('sidebarToggleIcon');
    const texts = document.querySelectorAll('.sidebar-text');

    sidebar.classList.toggle('w-64');
    sidebar.classList.toggle('w-20');
    icon.classList.toggle('rotate-180');

    texts.forEach(text => {
        if (sidebar.classList.contains('w-20')) {
            text.classList.add('hidden');
        } else {
            text.classList.remove('hidden');
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) lucide.createIcons();
});
</script> -->
<script>
/*
  Sidebar toggle — robust implementation:
  - Explicitly sets classes instead of toggling both.
  - Persists state to localStorage so refresh keeps it.
  - Hides sidebar text when collapsed and restores when expanded.
*/

const SIDEBAR_KEY = 'stockSidebarCollapsed';

function setCollapsed(collapsed) {
    const sidebar = document.getElementById('stockSidebar');
    const icon = document.getElementById('sidebarToggleIcon');
    const texts = document.querySelectorAll('.sidebar-text');

    // Defensive checks
    if (!sidebar || !icon) return;

    // Always remove both width classes to avoid stacking problems
    sidebar.classList.remove('w-64', 'w-20', 'min-w-0');
    // Add the explicit class we want
    if (collapsed) {
        sidebar.classList.add('w-20', 'min-w-0'); // small fixed width
        icon.classList.add('rotate-180');
        texts.forEach(t => t.classList.add('hidden'));
    } else {
        sidebar.classList.add('w-64');
        icon.classList.remove('rotate-180');
        texts.forEach(t => t.classList.remove('hidden'));
    }

    // persist state
    try {
        localStorage.setItem(SIDEBAR_KEY, collapsed ? '1' : '0');
    } catch (e) {
        // ignore localStorage errors (private mode)
        console.warn('Could not persist sidebar state', e);
    }
}

function toggleSidebar() {
    const collapsed = localStorage.getItem(SIDEBAR_KEY) === '1';
    setCollapsed(!collapsed);
}

document.addEventListener('DOMContentLoaded', () => {
    // restore state on load
    const saved = localStorage.getItem(SIDEBAR_KEY);
    const collapsed = saved === '1';

    // ensure DOM exists
    if (document.getElementById('stockSidebar')) {
        setCollapsed(collapsed);
    }

    // create lucide icons (keeps icons working)
    if (window.lucide) lucide.createIcons();
});
</script>
@endsection