@extends('layouts.app')
@section('title', 'Stock List - InventoryPro')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <i data-lucide="boxes" class="w-5 h-5 text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Stock List</h1>
                    <p class="text-gray-600 mt-1">Manage and monitor your store inventory</p>
                </div>
            </div>
        </div>

        <!-- Store Context -->
        @if(auth()->user()->emporia)
        <div class="mb-6 bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center border-2 border-indigo-200">
                        <i data-lucide="store" class="w-6 h-6 text-indigo-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ auth()->user()->emporia->name }}</h3>
                        <p class="text-gray-600">{{ auth()->user()->emporia->location }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Store Code</p>
                    <p class="text-lg font-semibold text-gray-900">{{ auth()->user()->emporia->code }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Action Bar -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <input type="text" placeholder="Search products..." 
                           class="pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                        <i data-lucide="search" class="h-5 w-5 text-gray-400"></i>
                    </div>
                </div>
                <select class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option>All Categories</option>
                    <option>Electronics</option>
                    <option>Accessories</option>
                    <option>Appliances</option>
                </select>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                            Logout
                        </button>
                    </form>
        </div>

        <!-- Stats Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Products</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $products->total() }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="package" class="w-5 h-5 text-blue-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">In Stock</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">148</h3>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Low Stock</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">12</h3>
                    </div>
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Out of Stock</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">5</h3>
                    </div>
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="x-circle" class="w-5 h-5 text-red-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Table Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">All Products</h3>
            </div>

            <!-- Product Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ref</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost Price (₦)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Selling Price (₦)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profit (₦)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($products as $product)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        #{{ $product->reference }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                            @if($product->code)
                                            <div class="text-xs text-gray-500">{{ $product->code }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                        {{ $product->category ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($product->quantity <= ($product->reorder_point ?? 5))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ $product->quantity }} ⚠️
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-900">{{ $product->quantity }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ₦{{ number_format($product->cost_price, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    ₦{{ number_format($product->selling_price, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                                    ₦{{ number_format($product->profit ?? ($product->selling_price - $product->cost_price), 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('products.edit', $product->id) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 transition-colors duration-150"
                                           title="Edit">
                                            <i data-lucide="edit-2" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this product?');"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 transition-colors duration-150"
                                                    title="Delete">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i data-lucide="package" class="w-8 h-8 text-gray-400"></i>
                                        </div>
                                        <h4 class="text-lg font-medium text-gray-900 mb-2">No products found</h4>
                                        <p class="text-gray-600 mb-4">Get started by adding your first product</p>
                                        <a href="{{ route('products.create') }}"
                                           class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition flex items-center gap-2">
                                            <i data-lucide="plus-circle" class="w-4 h-4"></i>
                                            Add Product
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Footer -->
            @if($products->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Showing <span class="font-medium">{{ $products->firstItem() }}</span>
                        to <span class="font-medium">{{ $products->lastItem() }}</span>
                        of <span class="font-medium">{{ $products->total() }}</span> results
                    </div>
                    <div class="flex space-x-2">
                        @if($products->onFirstPage())
                        <span class="px-3 py-1 border border-gray-300 rounded text-gray-400 cursor-not-allowed">
                            Previous
                        </span>
                        @else
                        <a href="{{ $products->previousPageUrl() }}" 
                           class="px-3 py-1 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                            Previous
                        </a>
                        @endif

                        @if($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}" 
                           class="px-3 py-1 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                            Next
                        </a>
                        @else
                        <span class="px-3 py-1 border border-gray-300 rounded text-gray-400 cursor-not-allowed">
                            Next
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Quick Links -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('products.restock') }}" 
               class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:border-indigo-300 hover:shadow-md transition-all duration-200">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="package-plus" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">Restock Products</h4>
                        <p class="text-sm text-gray-600 mt-1">Update existing product quantities</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('products.create') }}" 
               class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:border-green-300 hover:shadow-md transition-all duration-200">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="plus-circle" class="w-6 h-6 text-green-600"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">Add New Product</h4>
                        <p class="text-sm text-gray-600 mt-1">Add a completely new product</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('dashboard') }}" 
               class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:border-purple-300 hover:shadow-md transition-all duration-200">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="layout-dashboard" class="w-6 h-6 text-purple-600"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">Back to Dashboard</h4>
                        <p class="text-sm text-gray-600 mt-1">Return to main dashboard</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Initialize Lucide icons
    if (window.lucide) {
        lucide.createIcons();
    }
});
</script>
@endsection