@extends('layouts.app')

@section('title', 'Low Stock Products')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow p-4 md:p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-amber-100 rounded-lg">
                <i data-lucide="alert-triangle" class="w-6 h-6 text-amber-600"></i>
            </div>
            <div>
                <h1 class="text-xl md:text-2xl font-semibold text-gray-800">Low Stock Products</h1>
                <p class="text-sm text-gray-600">Products that have reached or fallen below their reorder point</p>
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('sales.entry') }}"
               class="flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Back to Products</span>
            </a>
            
            <button onclick="sendAllAlerts()"
                    class="flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">
                <i data-lucide="bell" class="w-4 h-4"></i>
                <span>Send All Alerts</span>
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <main class="p-4 md:p-6 space-y-6">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 md:p-6 rounded-xl shadow border-l-4 border-amber-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-amber-600">Low Stock</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $summary['total_low_stock'] ?? 0 }}</h3>
                    </div>
                    <i data-lucide="alert-triangle" class="w-8 h-8 text-amber-500"></i>
                </div>
            </div>
            
            <div class="bg-white p-4 md:p-6 rounded-xl shadow border-l-4 border-red-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-red-600">Critical</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $summary['critical_stock'] ?? 0 }}</h3>
                    </div>
                    <i data-lucide="alert-circle" class="w-8 h-8 text-red-500"></i>
                </div>
            </div>
            
            <div class="bg-white p-4 md:p-6 rounded-xl shadow border-l-4 border-gray-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Out of Stock</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $summary['out_of_stock'] ?? 0 }}</h3>
                    </div>
                    <i data-lucide="x-circle" class="w-8 h-8 text-gray-500"></i>
                </div>
            </div>
            
            <div class="bg-white p-4 md:p-6 rounded-xl shadow border-l-4 border-blue-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-blue-600">Need Alerts</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $summary['needs_alert'] ?? 0 }}</h3>
                    </div>
                    <i data-lucide="bell" class="w-8 h-8 text-blue-500"></i>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="p-4 md:p-6 border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Low Stock Products</h2>
                        <p class="text-sm text-gray-600">Click on any product to view details or send alerts</p>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <div class="relative">
                            <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                            <input type="text" placeholder="Search products..." 
                                   class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 w-full md:w-64">
                        </div>
                    </div>
                </div>
            </div>
            
            @if($lowStockProducts->isEmpty())
                <div class="p-8 md:p-12 text-center">
                    <i data-lucide="check-circle" class="w-12 h-12 text-emerald-500 mx-auto mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-800 mb-2">No low stock products!</h3>
                    <p class="text-gray-600">All products are sufficiently stocked.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reorder Point</th>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Alert</th>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($lowStockProducts as $product)
                            @php
                                $status = $product->stock_status;
                                $percentage = $product->reorder_point > 0 
                                    ? round(($product->quantity / $product->reorder_point) * 100, 1)
                                    : 0;
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 md:px-6 py-4">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $product->reference }}</div>
                                        @if($product->emporium)
                                            <div class="text-xs text-gray-400 mt-1">
                                                <i data-lucide="store" class="w-3 h-3 inline mr-1"></i>
                                                {{ $product->emporium->name }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                
                                <td class="px-4 md:px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1">
                                            <div class="flex justify-between text-sm mb-1">
                                                <span class="font-medium">{{ $product->quantity }} {{ $product->unit }}</span>
                                                <span class="text-gray-500">{{ $percentage }}%</span>
                                            </div>
                                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-{{ $status['color'] }}-500" style="width: {{ min($percentage, 100) }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-4 md:px-6 py-4">
                                    <input type="number" 
                                           value="{{ $product->reorder_point }}"
                                           min="0"
                                           data-product-id="{{ $product->id }}"
                                           class="w-20 px-3 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm reorder-point-input">
                                </td>
                                
                                <td class="px-4 md:px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $status['color'] }}-100 text-{{ $status['color'] }}-800">
                                        <i data-lucide="{{ $status['icon'] }}" class="w-3 h-3 mr-1"></i>
                                        {{ $status['label'] }}
                                    </span>
                                </td>
                                
                                <td class="px-4 md:px-6 py-4 text-sm text-gray-500">
                                    @if($product->alert_sent_at)
                                        {{ $product->alert_sent_at->diffForHumans() }}
                                    @else
                                        <span class="text-gray-400">Never</span>
                                    @endif
                                </td>
                                
                                <td class="px-4 md:px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <button onclick="sendAlert({{ $product->id }})"
                                                class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition send-alert-btn"
                                                title="Send Alert">
                                            <i data-lucide="bell" class="w-4 h-4"></i>
                                        </button>
                                        
                                        <a href="{{ route('products.restock') }}?product_id={{ $product->id }}"
                                           class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg transition"
                                           title="Restock">
                                            <i data-lucide="package-plus" class="w-4 h-4"></i>
                                        </a>
                                        
                                        <a href="{{ route('products.edit', $product) }}"
                                           class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                           title="Edit">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="px-4 md:px-6 py-4 border-t border-gray-200">
                    {{ $lowStockProducts->links() }}
                </div>
            @endif
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    if (window.lucide) {
        lucide.createIcons();
    }
    
    // Update reorder point
    document.querySelectorAll('.reorder-point-input').forEach(input => {
        input.addEventListener('change', function() {
            const productId = this.dataset.productId;
            const newValue = this.value;
            
            if (newValue < 0) {
                alert('Reorder point cannot be negative');
                this.value = 0;
                return;
            }
            
            const button = this;
            button.disabled = true;
            
            fetch(`/products/${productId}/update-reorder-point`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    reorder_point: newValue
                })
            })
            .then(response => response.json())
            .then(data => {
                button.disabled = false;
                if (data.success) {
                    // Show success message
                    showToast('success', 'Reorder point updated successfully');
                } else {
                    showToast('error', data.message || 'Error updating reorder point');
                }
            })
            .catch(error => {
                button.disabled = false;
                showToast('error', 'Network error. Please try again.');
            });
        });
    });
});

function sendAlert(productId) {
    const button = document.querySelector(`[onclick="sendAlert(${productId})"]`);
    const originalHTML = button.innerHTML;
    
    button.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>';
    if (window.lucide) lucide.createIcons();
    
    fetch(`/products/${productId}/send-alert`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        button.innerHTML = originalHTML;
        if (window.lucide) lucide.createIcons();
        
        if (data.success) {
            showToast('success', data.message || 'Alert sent successfully');
        } else {
            showToast('error', data.message || 'Error sending alert');
        }
    })
    .catch(error => {
        button.innerHTML = originalHTML;
        if (window.lucide) lucide.createIcons();
        showToast('error', 'Network error. Please try again.');
    });
}

function sendAllAlerts() {
    if (!confirm('Send low stock alerts for all products needing alerts?')) {
        return;
    }
    
    const button = event.target;
    const originalHTML = button.innerHTML;
    
    button.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin mr-2"></i>Sending...';
    if (window.lucide) lucide.createIcons();
    button.disabled = true;
    
    fetch('/products/send-all-alerts', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        button.innerHTML = originalHTML;
        if (window.lucide) lucide.createIcons();
        button.disabled = false;
        
        if (data.success) {
            showToast('success', data.message || 'Alerts sent successfully');
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast('error', data.message || 'Error sending alerts');
        }
    })
    .catch(error => {
        button.innerHTML = originalHTML;
        if (window.lucide) lucide.createIcons();
        button.disabled = false;
        showToast('error', 'Network error. Please try again.');
    });
}

function showToast(type, message) {
    // Create toast container if it doesn't exist
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'fixed top-4 right-4 z-50 space-y-2';
        document.body.appendChild(container);
    }
    
    const toastId = 'toast-' + Date.now();
    const toast = document.createElement('div');
    toast.id = toastId;
    toast.className = `px-4 py-3 rounded-lg shadow-lg flex items-center justify-between min-w-64 bg-${type === 'success' ? 'emerald' : 'red'}-50 border border-${type === 'success' ? 'emerald' : 'red'}-200`;
    
    toast.innerHTML = `
        <div class="flex items-center">
            <i data-lucide="${type === 'success' ? 'check-circle' : 'alert-circle'}" 
               class="w-5 h-5 mr-3 text-${type === 'success' ? 'emerald' : 'red'}-600"></i>
            <span class="text-gray-800">${message}</span>
        </div>
        <button onclick="document.getElementById('${toastId}').remove()" 
                class="ml-4 text-gray-400 hover:text-gray-600">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    `;
    
    container.appendChild(toast);
    
    if (window.lucide) {
        lucide.createIcons();
    }
    
    // Auto-remove toast after 5 seconds
    setTimeout(() => {
        if (document.getElementById(toastId)) {
            document.getElementById(toastId).remove();
        }
    }, 5000);
}
</script>

<style>
.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>
@endsection