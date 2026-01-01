@extends('layouts.app')
@section('title', 'Auditor Panel')

@section('content')
<!-- Temporary Debug Section -->
<div class="bg-yellow-100 border-l-4 border-yellow-500 p-4 mb-4" id="debugInfo">
    <h3 class="font-bold">Debug Information:</h3>
    <p>Route: <span id="debugRoute">{{ route('auditor.overstock-products') }}</span></p>
    <p>User ID: {{ auth()->id() }}</p>
    <p>Status: <span id="debugStatus">Loading...</span></p>
</div>
<div class="min-h-screen bg-gray-50 flex" x-data="auditorDashboard()">
    <!-- Mobile Overlay -->
    <div x-show="sidebarOpen && window.innerWidth < 1024" @click="sidebarOpen = false" 
         class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden">
    </div>

    <!-- Sidebar Navigation -->
    <div x-show="sidebarOpen" 
         class="fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-lg lg:static lg:inset-0 lg:z-auto"
         :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full">
        
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i data-lucide="clipboard-check" class="w-8 h-8 text-purple-600"></i>
                <div>
                    <h1 class="text-lg font-bold text-gray-900">Auditor</h1>
                    <p class="text-gray-500 text-sm">{{ auth()->user()->name }}</p>
                </div>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden">
                <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
            </button>
        </div>

        <nav class="p-4 space-y-1">
            <!-- Audit Section -->
            <div class="px-3 py-2">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Audit</h3>
            </div>
            <button @click="activeTab = 'overstock'" 
                    :class="activeTab === 'overstock' ? 'bg-purple-50 text-purple-700' : 'text-gray-700 hover:bg-purple-50 hover:text-purple-700'"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-lg transition group">
                <i data-lucide="clipboard-check" class="w-5 h-5"></i>
                <span>Audit Management</span>
            </button>
            
            <!-- Reports Section -->
            <div class="px-3 py-2 mt-4">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Reports</h3>
            </div>
            <button @click="activeTab = 'reports'" 
                    :class="activeTab === 'reports' ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700'"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-lg transition group">
                <i data-lucide="file-text" class="w-5 h-5"></i>
                <span>Audit Reports</span>
            </button>
            
            <!-- Quick Actions -->
            <div class="px-3 py-2 mt-4">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Quick Access</h3>
            </div>
            <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition group">
                <i data-lucide="package" class="w-5 h-5"></i>
                <span>All Products</span>
            </a>
            <a href="{{ route('transaction.daily') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition group">
                <i data-lucide="receipt" class="w-5 h-5"></i>
                <span>Transactions</span>
            </a>
        </nav>
    </div>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="flex items-center justify-between px-6 py-4">
                <div class="flex items-center gap-4">
                    <!-- Toggle Button -->
                    <button @click="sidebarOpen = !sidebarOpen" class="flex">
                        <i data-lucide="menu" class="w-6 h-6 text-gray-600"></i>
                    </button>
                    
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900" x-text="activeTab === 'overstock' ? 'Over-Stock Management' : 'Audit Reports'"></h1>
                        <div class="flex items-center gap-4 text-sm text-gray-600">
                            <span>Welcome, {{ auth()->user()->name }}</span>
                            <span>•</span>
                            <span x-text="currentDate"></span>
                            <span>•</span>
                            <span x-text="currentTime"></span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-medium">
                        Auditor
                    </span>
                    
                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6 overflow-auto">
            <!-- Over-Stock Management Tab -->
            <!-- Audit Management Tab -->
<template x-if="activeTab === 'overstock'">
    <div class="space-y-6">
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-xl shadow border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Total Products</p>
                        <p class="text-2xl font-bold text-gray-900" x-text="stats.total_products || 0"></p>
                        <p class="text-purple-600 text-sm">Available for audit</p>
                    </div>
                    <i data-lucide="package" class="w-8 h-8 text-purple-600"></i>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-xl shadow border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Reports This Month</p>
                        <p class="text-2xl font-bold text-gray-900" x-text="stats.reports_this_month || 0"></p>
                        <p class="text-blue-600 text-sm">Audit reports</p>
                    </div>
                    <i data-lucide="file-text" class="w-8 h-8 text-blue-600"></i>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-xl shadow border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Pending Reviews</p>
                        <p class="text-2xl font-bold text-gray-900" x-text="stats.pending_reviews || 0"></p>
                        <p class="text-yellow-600 text-sm">Awaiting admin</p>
                    </div>
                    <i data-lucide="clock" class="w-8 h-8 text-yellow-600"></i>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-xl shadow border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Total Adjustments</p>
                        <p class="text-2xl font-bold text-gray-900" x-text="stats.total_adjustments || 0"></p>
                        <p class="text-green-600 text-sm">Stock corrections</p>
                    </div>
                    <i data-lucide="trending-down" class="w-8 h-8 text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- 🧾 Audit Adjustment Form -->
        <form id="auditForm" class="bg-white p-6 rounded-xl shadow space-y-6">
            <div class="grid md:grid-cols-3 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Location</label>
                    <select name="location" class="w-full border rounded-lg px-4 py-2">
                        <option value="">Select Location</option>
                        <option>Main Shop</option>
                        <option>Godown</option>
                        <option>Branch</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Customer Name</label>
                    <input type="text" name="customer" placeholder="Enter customer name"
                           class="w-full border rounded-lg px-4 py-2" id="customerName">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Audit Reference</label>
                    <input type="text" id="auditReference" readonly
                        class="w-full border rounded-lg px-4 py-2 bg-gray-100 font-mono text-gray-700">
                </div>

                <!-- Payment Type Selection -->
                <div>
                  <label class="block text-gray-700 font-medium mb-2">Payment Type</label>
                  <div id="paymentTypeGroup" class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <label class="flex items-center justify-center gap-2 border rounded-lg p-2 cursor-pointer hover:bg-purple-50 transition">
                      <input type="radio" name="paymentType" value="Cash" class="hidden" checked>
                      <i data-lucide="wallet" class="w-5 h-5 text-purple-600"></i>
                      <span class="font-medium text-gray-700">Cash</span>
                    </label>

                    <label class="flex items-center justify-center gap-2 border rounded-lg p-2 cursor-pointer hover:bg-purple-50 transition">
                      <input type="radio" name="paymentType" value="POS" class="hidden">
                      <i data-lucide="credit-card" class="w-5 h-5 text-purple-600"></i>
                      <span class="font-medium text-gray-700">POS</span>
                    </label>

                    <label class="flex items-center justify-center gap-2 border rounded-lg p-2 cursor-pointer hover:bg-purple-50 transition">
                      <input type="radio" name="paymentType" value="Bank Transfer" class="hidden">
                      <i data-lucide="banknote" class="w-5 h-5 text-purple-600"></i>
                      <span class="font-medium text-gray-700">Transfer</span>
                    </label>

                    <label class="flex items-center justify-center gap-2 border rounded-lg p-2 cursor-pointer hover:bg-purple-50 transition">
                      <input type="radio" name="paymentType" value="Credit" class="hidden">
                      <i data-lucide="file-minus" class="w-5 h-5 text-purple-600"></i>
                      <span class="font-medium text-gray-700">Credit</span>
                    </label>
                  </div>
                </div>
            </div>

            <hr class="my-4">

            <!-- Product Selection -->
            <div class="grid md:grid-cols-5 sm:grid-cols-2 gap-4 items-end">
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Select Product</label>
                    <select id="productSelect" class="w-full border rounded-lg px-3 py-2">
                        <option value="">Select Product to Audit</option>
                        <!-- Products will be loaded via JavaScript -->
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Unit Price (₦)</label>
                    <input type="number" id="unitPrice" readonly
                           class="w-full border rounded-lg px-3 py-2 bg-gray-100">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Discount (₦)</label>
                    <input type="number" id="discount" min="0" value="0"
                           class="w-full border rounded-lg px-3 py-2">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Quantity to Adjust</label>
                    <input type="number" id="quantity" min="0.01" step="0.01"
                           class="w-full border rounded-lg px-3 py-2" placeholder="Enter quantity">
                </div>

                <div>
                    <button type="button" id="addItemBtn"
                            class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 transition">
                        Add to Audit
                    </button>
                </div>
            </div>

            <!-- Audit Details -->
            <div class="grid md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Reason for Adjustment</label>
                    <textarea name="reason" class="w-full border rounded-lg px-3 py-2" rows="3"
                              placeholder="Explain why this adjustment is needed (e.g., stock discrepancy, damage, etc.)..." required></textarea>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Audit Findings & Impact</label>
                    <textarea name="discrepancy_notes" class="w-full border rounded-lg px-3 py-2" rows="3"
                              placeholder="Provide detailed explanation of the situation and its financial impact..." required></textarea>
                </div>
            </div>
        </form>

        <!-- 🧮 Audit Items Table -->
        <section class="bg-white p-6 rounded-xl shadow space-y-6">
            <h2 class="text-lg font-semibold text-gray-800">Audit Adjustment Items</h2>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-sm text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3">Product</th>
                            <th class="p-3">Current Stock</th>
                            <th class="p-3">Unit Price</th>
                            <th class="p-3">Discount</th>
                            <th class="p-3">Qty to Adjust</th>
                            <th class="p-3">Total</th>
                            <th class="p-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody id="auditItemsBody" class="divide-y divide-gray-100">
                        <tr id="emptyRow">
                            <td colspan="7" class="text-center text-gray-500 py-4 italic">
                                No audit items added yet.
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-gray-50 font-semibold text-gray-800">
                        <tr>
                            <td colspan="5" class="p-3 text-right">Audit Total:</td>
                            <td id="auditTotal" class="p-3">₦0.00</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- ⚙ Action Buttons -->
            <div class="flex flex-col gap-6 mt-8">
                <!-- Transaction Control Buttons -->
                <div class="flex flex-wrap justify-end gap-3">
                    <button id="resetBtn" type="button"
                        class="flex items-center gap-2 bg-gray-100 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-200 transition">
                        <i data-lucide="refresh-ccw" class="w-4 h-4"></i>
                        <span>Reset Audit</span>
                    </button>

                    <button id="completeAuditBtn" type="button"
                        class="flex items-center gap-2 bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition group">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        <span>Send Audit Report to Admin</span>
                    </button>
                </div>
            </div>
        </section>
    </div>
</template>

            <!-- Audit Reports Tab -->
            <template x-if="activeTab === 'reports'">
                <div>
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-800">Audit Reports</h2>
                                    <p class="text-gray-600 text-sm">Your submitted over-stock adjustment reports</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <div class="space-y-4" id="audit-reports-container">
                                <div class="text-center py-8 text-gray-500">
                                    <i data-lucide="loader-2" class="w-8 h-8 animate-spin mx-auto mb-2"></i>
                                    <p>Loading audit reports...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<!-- Load Lucide Icons -->
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<!-- Load Alpine.js -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<!-- Load Axios for API calls -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    // Initialize Lucide icons first
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        // Initialize audit form
        initializeAuditForm();
    });

    // Alpine.js component
    document.addEventListener('alpine:init', () => {
        Alpine.data('auditorDashboard', () => ({
            sidebarOpen: window.innerWidth >= 1024,
            activeTab: 'overstock',
            stats: {
                total_products: 0,
                reports_this_month: 0,
                pending_reviews: 0,
                total_adjustments: 0
            },
            currentDate: '',
            currentTime: '',

            init() {
                // Ensure Lucide icons are created
                this.$nextTick(() => {
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                });

                this.updateClock();
                setInterval(() => this.updateClock(), 1000);
                
                // Load initial data
                this.loadStats();
                
                // Load reports when tab is active
                this.$watch('activeTab', (value) => {
                    if (value === 'reports') {
                        this.loadAuditReports();
                    }
                });
            },

            updateClock() {
                const now = new Date();
                this.currentDate = now.toLocaleDateString('en-US', { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                });
                this.currentTime = now.toLocaleTimeString('en-US', { 
                    hour: '2-digit', 
                    minute: '2-digit', 
                    second: '2-digit' 
                });
            },

            async loadStats() {
                try {
                    const response = await fetch('{{ route("auditor.stats") }}');
                    if (response.ok) {
                        this.stats = await response.json();
                    }
                } catch (error) {
                    console.error('Error loading stats:', error);
                }
            },

            async loadAuditReports() {
                try {
                    const container = document.getElementById('audit-reports-container');
                    container.innerHTML = `
                        <div class="text-center py-8 text-gray-500">
                            <i data-lucide="loader-2" class="w-8 h-8 animate-spin mx-auto mb-2"></i>
                            <p>Loading audit reports...</p>
                        </div>
                    `;
                    
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }

                    const response = await fetch('{{ route("auditor.reports") }}');
                    const reports = await response.json();
                    
                    this.renderAuditReports(container, reports);
                    
                } catch (error) {
                    console.error('Error loading reports:', error);
                    const container = document.getElementById('audit-reports-container');
                    container.innerHTML = `
                        <div class="text-center py-8 text-red-500">
                            <i data-lucide="alert-circle" class="w-8 h-8 mx-auto mb-2"></i>
                            <p>Error loading reports</p>
                        </div>
                    `;
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            },

            renderAuditReports(container, reports) {
                if (reports.length === 0) {
                    container.innerHTML = `
                        <div class="text-center py-8 text-gray-500">
                            <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3"></i>
                            <p>No audit reports yet</p>
                            <p class="text-sm">Your audit reports will appear here</p>
                        </div>
                    `;
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                    return;
                }
                
                container.innerHTML = reports.map(report => `
                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-semibold text-gray-900 text-lg">${report.product ? report.product.name : 'Product'}</h3>
                                <p class="text-sm text-gray-500">
                                    ${report.emporia ? report.emporia.name : 'Emporia'} • 
                                    ${new Date(report.created_at).toLocaleDateString()}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    Quantity Removed: ${report.adjusted_quantity} units
                                </p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-medium ${
                                report.status === 'pending_review' 
                                    ? 'bg-yellow-100 text-yellow-800'
                                    : 'bg-green-100 text-green-800'
                            }">
                                ${report.status === 'pending_review' ? 'Pending Review' : 'Approved'}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div class="text-center p-4 bg-blue-50 rounded-lg">
                                <p class="text-xl font-bold text-blue-600">₦${(report.total_sales || 0).toLocaleString()}</p>
                                <p class="text-sm text-gray-600">Total Sales</p>
                            </div>
                            <div class="text-center p-4 bg-green-50 rounded-lg">
                                <p class="text-xl font-bold text-green-600">₦${(report.total_profit || 0).toLocaleString()}</p>
                                <p class="text-sm text-gray-600">Profit</p>
                            </div>
                            <div class="text-center p-4 bg-purple-50 rounded-lg">
                                <p class="text-xl font-bold text-purple-600">${report.adjusted_quantity || 0}</p>
                                <p class="text-sm text-gray-600">Units Removed</p>
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                            <span class="text-sm text-gray-500">
                                Report ID: #${report.id.toString().padStart(6, '0')}
                            </span>
                            <button onclick="viewReportDetails(${report.id})" 
                                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700 transition flex items-center gap-2">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                                View Details
                            </button>
                        </div>
                    </div>
                `).join('');
                
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }
        }));
    });

    // Audit Form JavaScript
    function initializeAuditForm() {
        console.log('🔧 Initializing audit form...');
        
        // Elements
        const auditReferenceEl = document.getElementById("auditReference");
        const productSelect = document.getElementById("productSelect");
        const unitPrice = document.getElementById("unitPrice");
        const discountInput = document.getElementById("discount");
        const qtyInput = document.getElementById("quantity");
        const addItemBtn = document.getElementById("addItemBtn");
        const auditItemsBody = document.getElementById("auditItemsBody");
        const auditTotalEl = document.getElementById("auditTotal");
        const completeAuditBtn = document.getElementById("completeAuditBtn");
        const customerInput = document.getElementById("customerName");
        const resetBtn = document.getElementById("resetBtn");
        const paymentTypeGroup = document.getElementById("paymentTypeGroup");

        // Generate audit reference
        const ref = "AUD" + new Date().toISOString().replace(/\D/g, "").slice(0, 14);
        if (auditReferenceEl) auditReferenceEl.value = ref;

        // Load products for audit
        async function loadOverstockProducts() {
            try {
                console.log('🔄 Loading products from:', '{{ route("auditor.overstock-products") }}');
                
                const response = await fetch('{{ route("auditor.overstock-products") }}');
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                console.log('📦 Products API response:', data);
                
                // Clear existing options
                if (productSelect) {
                    productSelect.innerHTML = '<option value="">Select Product to Audit</option>';
                    
                    // Check if data is an array
                    if (!Array.isArray(data)) {
                        console.error('❌ Products data is not an array:', data);
                        const option = document.createElement('option');
                        option.value = "";
                        option.textContent = "Error: Invalid data format";
                        productSelect.appendChild(option);
                        return;
                    }
                    
                    // Add products
                    if (data.length === 0) {
                        console.log('ℹ️ No products available');
                        const option = document.createElement('option');
                        option.value = "";
                        option.textContent = "No products available";
                        productSelect.appendChild(option);
                    } else {
                        console.log(`✅ Loaded ${data.length} products`);
                        data.forEach(product => {
                            const option = document.createElement('option');
                            option.value = product.id;
                            option.textContent = `${product.name} (${product.quantity} units in stock)`;
                            option.dataset.price = product.selling_price || product.price || product.unit_price || 0;
                            option.dataset.quantity = product.quantity || product.stock || 0;
                            productSelect.appendChild(option);
                        });
                    }
                }
            } catch (error) {
                console.error('❌ Error loading products:', error);
                
                // Show error in dropdown
                if (productSelect) {
                    productSelect.innerHTML = `
                        <option value="">Error loading products</option>
                        <option value="" disabled>${error.message}</option>
                    `;
                }
                
                // Show error notification
                showNotification('Error loading products. Please check console for details.', 'error');
            }
        }

        // Payment type selection
        if (paymentTypeGroup) {
            paymentTypeGroup.addEventListener("click", function (e) {
                const label = e.target.closest("label");
                if (!label) return;

                paymentTypeGroup.querySelectorAll("label").forEach(l => {
                    l.classList.remove("border-purple-500", "bg-purple-50");
                });

                label.classList.add("border-purple-500", "bg-purple-50");
                const input = label.querySelector("input[type='radio']");
                if (input) input.checked = true;
            });
        }

        // Auto-fill unit price on product change
        if (productSelect) {
            productSelect.addEventListener("change", function() {
                const opt = this.selectedOptions[0];
                const price = opt && opt.dataset && opt.dataset.price ? parseFloat(opt.dataset.price) : 0;
                const currentQty = opt && opt.dataset && opt.dataset.quantity ? parseInt(opt.dataset.quantity) : 0;
                
                if (unitPrice) unitPrice.value = price ? price.toFixed(2) : "";
                
                // Update quantity placeholder with max value
                if (qtyInput) {
                    qtyInput.placeholder = `Max: ${currentQty}`;
                    qtyInput.max = currentQty;
                }
            });
        }

        // Recalc helper
        window.recalcTotal = function() {
            let sum = 0;
            const rows = auditItemsBody.querySelectorAll("tr");
            rows.forEach(row => {
                if (row.id === "emptyRow") return;
                const totalCell = row.children[5];
                if (totalCell) {
                    const raw = totalCell.textContent.replace(/[₦,]/g, "").trim();
                    const n = parseFloat(raw) || 0;
                    sum += n;
                }
            });
            if (auditTotalEl) auditTotalEl.textContent = "₦" + sum.toFixed(2);
        };

        // Add audit item
        if (addItemBtn) {
            addItemBtn.addEventListener("click", function() {
                const selectedOption = productSelect.options[productSelect.selectedIndex];
                const productId = productSelect.value;
                const productName = selectedOption ? selectedOption.text.trim() : "";
                const currentStock = selectedOption ? parseInt(selectedOption.dataset.quantity) : 0;
                const price = parseFloat(unitPrice.value || 0);
                const discount = parseFloat(discountInput?.value || 0);
                const qty = parseFloat(qtyInput?.value || 0);

                // Validation
                if (!productId) {
                    alert("Select a product to audit.");
                    productSelect.focus();
                    return;
                }
                if (!(qty > 0)) {
                    alert("Enter a valid quantity greater than 0.");
                    qtyInput?.focus();
                    return;
                }
                if (qty > currentStock) {
                    alert(`Cannot remove more than current stock of ${currentStock} units.`);
                    qtyInput?.focus();
                    return;
                }

                const discountedUnit = price - discount;
                if (discountedUnit <= 0) {
                    alert("Invalid discount — price after discount must be greater than 0.");
                    discountInput?.focus();
                    return;
                }

                const itemTotal = discountedUnit * qty;

                // Remove placeholder if present
                const placeholder = document.getElementById("emptyRow");
                if (placeholder) placeholder.remove();

                // Create audit item row
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td class="p-3" data-product-id="${productId}">${escapeHtml(productName)}</td>
                    <td class="p-3">${currentStock}</td>
                    <td class="p-3">₦${price.toFixed(2)}</td>
                    <td class="p-3 text-red-600">₦${discount.toFixed(2)}</td>
                    <td class="p-3">${qty % 1 === 0 ? qty.toFixed(0) : qty.toFixed(2)}</td>
                    <td class="p-3 font-semibold">₦${itemTotal.toFixed(2)}</td>
                    <td class="p-3 text-right">
                        <button type="button" class="delete-btn text-red-500 hover:text-red-700 transition" title="Remove Item">🗑</button>
                    </td>`;

                auditItemsBody.appendChild(tr);

                // Reset fields
                productSelect.selectedIndex = 0;
                if (unitPrice) unitPrice.value = "";
                if (discountInput) discountInput.value = "0";
                if (qtyInput) qtyInput.value = "";
                productSelect.focus();

                // Recalc total
                recalcTotal();
            });
        }

        // Delegated delete handler
        if (auditItemsBody) {
            auditItemsBody.addEventListener("click", function(e) {
                const target = e.target;
                if (target.classList.contains("delete-btn")) {
                    const row = target.closest("tr");
                    const productCell = row?.children[0];
                    const productName = productCell ? productCell.textContent.trim() : "this audit item";

                    if (confirm("Remove " + productName + " from audit?")) {
                        row.style.transition = "opacity 0.2s ease, height 0.2s ease";
                        row.style.opacity = 0;
                        setTimeout(() => {
                            row.remove();
                            recalcTotal();

                            // Restore placeholder if empty
                            const rowsLeft = auditItemsBody.querySelectorAll("tr");
                            if (rowsLeft.length === 0) {
                                auditItemsBody.innerHTML = `
                                    <tr id="emptyRow">
                                        <td colspan="7" class="text-center text-gray-500 py-4 italic">No audit items added yet.</td>
                                    </tr>`;
                            }
                        }, 180);
                    }
                }
            });
        }

        // Reset audit
        if (resetBtn) {
            resetBtn.addEventListener("click", function() {
                if (confirm("Reset this audit? This will remove all added items.")) {
                    document.getElementById("auditForm")?.reset();
                    if (auditItemsBody) {
                        auditItemsBody.innerHTML = `
                            <tr id="emptyRow">
                                <td colspan="7" class="text-center text-gray-500 py-4 italic">No audit items added yet.</td>
                            </tr>`;
                    }
                    if (auditTotalEl) auditTotalEl.textContent = "₦0.00";
                    
                    // Regenerate reference
                    const newRef = "AUD" + new Date().toISOString().replace(/\D/g, "").slice(0, 14);
                    if (auditReferenceEl) auditReferenceEl.value = newRef;
                }
            });
        }

        // Complete audit and send to admin
        if (completeAuditBtn) {
            completeAuditBtn.addEventListener("click", async function () {
                // Basic validation
                const rows = auditItemsBody.querySelectorAll("tr:not(#emptyRow)");
                const hasItems = Array.from(rows).some(r => !r.id || r.id !== "emptyRow");
                const customerName = customerInput ? customerInput.value.trim() : "";
                const reason = document.querySelector("textarea[name='reason']")?.value.trim();
                const discrepancyNotes = document.querySelector("textarea[name='discrepancy_notes']")?.value.trim();

                if (!customerName) {
                    alert("⚠ Please enter the customer name for this audit adjustment.");
                    customerInput?.focus();
                    return;
                }

                if (!hasItems) {
                    alert("⚠ You must add at least one product to audit.");
                    return;
                }

                if (!reason) {
                    alert("⚠ Please provide the reason for adjustment.");
                    document.querySelector("textarea[name='reason']")?.focus();
                    return;
                }

                if (!discrepancyNotes) {
                    alert("⚠ Please provide detailed audit findings and impact analysis.");
                    document.querySelector("textarea[name='discrepancy_notes']")?.focus();
                    return;
                }

                // Build payload
                try {
                    const payload = {
                        reference: auditReferenceEl ? auditReferenceEl.value : ref,
                        customer_name: customerName,
                        location: document.querySelector("select[name='location']")?.value,
                        payment_type: document.querySelector("input[name='paymentType']:checked")?.value,
                        reason: reason,
                        discrepancy_notes: discrepancyNotes,
                        items: Array.from(document.querySelectorAll("#auditItemsBody tr:not(#emptyRow)")).map((row) => {
                            const cells = row.children;
                            
                            const productId = parseInt(cells[0].dataset.productId);
                            const unitPriceText = cells[2].textContent.replace(/[₦,]/g, "");
                            const discountText = cells[3].textContent.replace(/[₦,]/g, "");
                            const quantityText = cells[4].textContent;
                            const totalText = cells[5].textContent.replace(/[₦,]/g, "");

                            return {
                                product_id: productId,
                                unit_price: parseFloat(unitPriceText),
                                discount: parseFloat(discountText),
                                quantity: parseFloat(quantityText),
                                total: parseFloat(totalText),
                            };
                        }),
                        total_amount: parseFloat(auditTotalEl ? auditTotalEl.textContent.replace(/[₦,]/g, "") : 0)
                    };

                    // Show loading state
                    completeAuditBtn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Sending Audit Report...';
                    completeAuditBtn.disabled = true;

                    const response = await axios.post("{{ route('auditor.send-report') }}", payload, {
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json",
                            "Content-Type": "application/json"
                        },
                        timeout: 15000
                    });

                    if (response.data.success) {
                        alert("✅ " + response.data.message);
                        // Reset form and redirect to reports
                        document.getElementById("auditForm")?.reset();
                        if (auditItemsBody) {
                            auditItemsBody.innerHTML = `
                                <tr id="emptyRow">
                                    <td colspan="7" class="text-center text-gray-500 py-4 italic">No audit items added yet.</td>
                                </tr>`;
                        }
                        if (auditTotalEl) auditTotalEl.textContent = "₦0.00";
                        
                        // Generate new reference
                        const newRef = "AUD" + new Date().toISOString().replace(/\D/g, "").slice(0, 14);
                        if (auditReferenceEl) auditReferenceEl.value = newRef;
                        
                        // Refresh stats
                        Alpine.store('auditorDashboard').loadStats();
                    } else {
                        alert("⚠ " + response.data.message);
                    }

                } catch (error) {
                    console.error('Audit submission error:', error);
                    let userMessage = "Audit submission failed: ";
                    if (error.response?.data?.message) {
                        userMessage += error.response.data.message;
                    } else if (error.message) {
                        userMessage += error.message;
                    } else {
                        userMessage += "Unknown error occurred";
                    }
                    alert("⚠ " + userMessage);
                } finally {
                    // Reset button
                    completeAuditBtn.innerHTML = '<i data-lucide="send" class="w-4 h-4"></i> Send Audit Report to Admin';
                    completeAuditBtn.disabled = false;
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            });
        }

        // Helper function
        function escapeHtml(unsafe) {
            return unsafe
                .replaceAll("&", "&amp;")
                .replaceAll("<", "&lt;")
                .replaceAll(">", "&gt;")
                .replaceAll('"', "&quot;")
                .replaceAll("'", "&#039;");
        }

        // Load products on page load
        console.log('🚀 Starting to load products...');
        loadOverstockProducts();
    }

    // Global functions
    function viewReportDetails(reportId) {
        alert('Viewing details for audit report #' + reportId);
        // Implement detailed report view modal
    }

    function showNotification(message, type = 'info') {
        // Remove existing notifications
        const existingNotification = document.getElementById('audit-notification');
        if (existingNotification) {
            existingNotification.remove();
        }

        // Create notification element
        const notification = document.createElement('div');
        notification.id = 'audit-notification';
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg border-l-4 ${
            type === 'success' 
                ? 'bg-green-50 border-green-500 text-green-700' 
                : 'bg-red-50 border-red-500 text-red-700'
        }`;
        notification.innerHTML = `
            <div class="flex items-center gap-3">
                <i data-lucide="${type === 'success' ? 'check-circle' : 'alert-circle'}" class="w-5 h-5"></i>
                <span class="font-medium">${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        `;

        document.body.appendChild(notification);
        
        // Recreate icons for the notification
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    }

    // Make functions globally available
    window.showNotification = showNotification;
    window.viewReportDetails = viewReportDetails;
</script>
@endsection