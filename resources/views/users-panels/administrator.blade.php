@extends('layouts.app')
@section('title', 'Administrator Panel')

@section('content')
<div class="min-h-screen bg-gray-50 flex" x-data="adminDashboard()">
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
                <i data-lucide="shield" class="w-8 h-8 text-red-600"></i>
                <div>
                    <h1 class="text-lg font-bold text-gray-900">Administrator</h1>
                    <p class="text-gray-500 text-sm">{{ auth()->user()->name }}</p>
                </div>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden">
                <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
            </button>
        </div>

        <nav class="p-4 space-y-1">
            <!-- Overview Section -->
            <div class="px-3 py-2">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Overview</h3>
            </div>
            <button @click="activeTab = 'overview'" 
                    :class="activeTab === 'overview' ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700'"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-lg transition group">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span>Dashboard Overview</span>
            </button>
            
            <!-- Reports Section -->
            <div class="px-3 py-2 mt-4">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Reports</h3>
            </div>
            <button @click="activeTab = 'managerReports'" 
                    :class="activeTab === 'managerReports' ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-green-50 hover:text-green-700'"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-lg transition group">
                <i data-lucide="file-text" class="w-5 h-5"></i>
                <span>Manager Reports</span>
                <span x-show="pendingReportsCount > 0" x-text="pendingReportsCount" 
                      class="bg-red-500 text-white text-xs px-2 py-1 rounded-full ml-auto"></span>
            </button>
            
            <!-- Management Section -->
            <div class="px-3 py-2 mt-4">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Management</h3>
            </div>
            <a href="{{ route('users.manage') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg transition group">
                <i data-lucide="user-cog" class="w-5 h-5"></i>
                <span>User Management</span>
            </a>
            <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition group">
                <i data-lucide="package" class="w-5 h-5"></i>
                <span>Products</span>
            </a>
            <a href="{{ route('transaction.daily') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-700 rounded-lg transition group">
                <i data-lucide="receipt" class="w-5 h-5"></i>
                <span>Transactions</span>
            </a>
            
            <!-- System Section -->
            <div class="px-3 py-2 mt-4">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">System</h3>
            </div>
            <a href="#" class="flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-gray-50 hover:text-gray-700 rounded-lg transition group">
                <i data-lucide="settings" class="w-5 h-5"></i>
                <span>System Config</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg transition group">
                <i data-lucide="database" class="w-5 h-5"></i>
                <span>Data Backup</span>
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
                        <h1 class="text-2xl font-bold text-gray-900" x-text="activeTab === 'overview' ? 'Administrator Panel' : 'Manager Reports'"></h1>
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
                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                        Administrator
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
            <!-- Overview Tab -->
            <template x-if="activeTab === 'overview'">
                <div>
                    <!-- System Overview -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <!-- Total Users -->
                        <div class="bg-white p-6 rounded-xl shadow">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm">Total Users</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\User::count() }}</p>
                                </div>
                                <i data-lucide="users" class="w-8 h-8 text-blue-600"></i>
                            </div>
                            <a href="{{ route('users.manage') }}" class="text-blue-600 text-sm hover:underline mt-2 block">
                                Manage Users →
                            </a>
                        </div>
                        
                        <!-- Products -->
                        <div class="bg-white p-6 rounded-xl shadow">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm">Products</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Product::count() }}</p>
                                </div>
                                <i data-lucide="package" class="w-8 h-8 text-green-600"></i>
                            </div>
                            <a href="{{ route('products.index') }}" class="text-green-600 text-sm hover:underline mt-2 block">
                                View Products →
                            </a>
                        </div>
                        
                        <!-- Transactions -->
                        <div class="bg-white p-6 rounded-xl shadow">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm">Transactions</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Transaction::count() }}</p>
                                </div>
                                <i data-lucide="receipt" class="w-8 h-8 text-purple-600"></i>
                            </div>
                            <a href="{{ route('transaction.daily') }}" class="text-purple-600 text-sm hover:underline mt-2 block">
                                View Transactions →
                            </a>
                        </div>
                        
                        <!-- Manager Reports -->
                        <div class="bg-white p-6 rounded-xl shadow">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm">Manager Reports</p>
                                    <p class="text-2xl font-bold text-gray-900" x-text="totalReportsCount"></p>
                                    <p class="text-xs text-gray-500" x-text="pendingReportsCount + ' pending'"></p>
                                </div>
                                <i data-lucide="file-text" class="w-8 h-8 text-orange-600"></i>
                            </div>
                            <button @click="activeTab = 'managerReports'; loadManagerReports();" class="text-orange-600 text-sm hover:underline mt-2 block">
                                View Reports →
                            </button>
                        </div>
                    </div>

                    <!-- Administrator Tools -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Administration Tools</h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <!-- User Management -->
                            <a href="{{ route('users.manage') }}" class="bg-white p-4 rounded-lg shadow text-center hover:shadow-md transition border-2 border-transparent hover:border-blue-500">
                                <i data-lucide="user-cog" class="w-8 h-8 text-blue-600 mx-auto mb-2"></i>
                                <span class="font-medium text-gray-700">User Management</span>
                                <p class="text-xs text-gray-500 mt-1">Add, edit, remove users</p>
                            </a>
                            
                            <!-- System Configuration -->
                            <a href="{{ route('products.index') }}" class="bg-white p-4 rounded-lg shadow text-center hover:shadow-md transition border-2 border-transparent hover:border-green-500">
                                <i data-lucide="settings" class="w-8 h-8 text-green-600 mx-auto mb-2"></i>
                                <span class="font-medium text-gray-700">System Config</span>
                                <p class="text-xs text-gray-500 mt-1">System settings</p>
                            </a>
                            
                            <!-- Audit Logs -->
                            <a href=" # " class="bg-white p-4 rounded-lg shadow text-center hover:shadow-md transition border-2 border-transparent hover:border-purple-500">
                                <i data-lucide="file-text" class="w-8 h-8 text-purple-600 mx-auto mb-2"></i>
                                <span class="font-medium text-gray-700">Audit Logs</span>
                                <p class="text-xs text-gray-500 mt-1">System activity logs</p>
                            </a>
                            
                            <!-- Data Management -->
                            <a href="#" class="bg-white p-4 rounded-lg shadow text-center hover:shadow-md transition border-2 border-transparent hover:border-orange-500">
                                <i data-lucide="database" class="w-8 h-8 text-orange-600 mx-auto mb-2"></i>
                                <span class="font-medium text-gray-700">Data Backup</span>
                                <p class="text-xs text-gray-500 mt-1">Backup & restore</p>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Reports -->
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Quick Reports</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <a href="{{ route('transaction.daily') }}" class="bg-white p-4 rounded-lg shadow hover:shadow-md transition border-l-4 border-blue-500">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="bar-chart-3" class="w-6 h-6 text-blue-600"></i>
                                    <div>
                                        <h3 class="font-medium text-gray-800">Sales Report</h3>
                                        <p class="text-sm text-gray-600">Daily transactions</p>
                                    </div>
                                </div>
                            </a>
                            
                            <a href="{{ route('products.index') }}" class="bg-white p-4 rounded-lg shadow hover:shadow-md transition border-l-4 border-green-500">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="package" class="w-6 h-6 text-green-600"></i>
                                    <div>
                                        <h3 class="font-medium text-gray-800">Inventory Report</h3>
                                        <p class="text-sm text-gray-600">Stock levels</p>
                                    </div>
                                </div>
                            </a>
                            
                            <button @click="activeTab = 'managerReports'; loadManagerReports();" class="bg-white p-4 rounded-lg shadow hover:shadow-md transition border-l-4 border-orange-500 text-left w-full">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="file-text" class="w-6 h-6 text-orange-600"></i>
                                    <div>
                                        <h3 class="font-medium text-gray-800">Manager Reports</h3>
                                        <p class="text-sm text-gray-600" x-text="pendingReportsCount + ' pending'"></p>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Manager Reports Tab -->
            <template x-if="activeTab === 'managerReports'">
                <div>
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-800">Manager Reports</h2>
                                    <p class="text-gray-600 text-sm">Review and manage reports from your emporia managers</p>
                                </div>
                                <div class="flex gap-2">
                                    <div class="relative">
                                        <select x-model="reportFilter" @change="loadManagerReports()" class="border border-gray-300 rounded-lg px-4 py-2 text-sm bg-white appearance-none pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="all">All Reports</option>
                                            <option value="recent">Recent (Last 7 days)</option>
                                            <option value="pending">Pending Review</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <div class="space-y-4" id="reports-container">
                                <div class="text-center py-8 text-gray-500">
                                    <i data-lucide="loader-2" class="w-8 h-8 animate-spin mx-auto mb-2"></i>
                                    <p>Loading reports...</p>
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
<!-- Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Initialize Lucide icons first
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });

    // Sample report data for demonstration
    const sampleReports = [
        {
            id: 1,
            manager: { name: 'John Manager' },
            emporia: { name: 'Main Store' },
            start_date: '2024-12-16',
            end_date: '2024-12-22',
            total_sales: 452300,
            total_profit: 85250,
            transaction_count: 47,
            is_reconstructed: false,
            sales_data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Today'],
                profits: [75200, 58300, 89400, 67100, 112300, 128500, 85250]
            },
            created_at: '2024-12-22T10:30:00Z'
        },
        {
            id: 2,
            manager: { name: 'Sarah Manager' },
            emporia: { name: 'Downtown Branch' },
            start_date: '2024-12-16',
            end_date: '2024-12-22',
            total_sales: 387600,
            total_profit: 72300,
            transaction_count: 38,
            is_reconstructed: true,
            sales_data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Today'],
                profits: [65200, 48300, 79400, 57100, 92300, 118500, 72300]
            },
            created_at: '2024-12-21T14:20:00Z'
        }
    ];

    // Alpine.js component
    document.addEventListener('alpine:init', () => {
        Alpine.data('adminDashboard', () => ({
            sidebarOpen: window.innerWidth >= 1024,
            activeTab: 'overview',
            reportFilter: 'all',
            pendingReportsCount: 2, // Default sample data
            totalReportsCount: 2,   // Default sample data
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
                
                // Try to load real data, fallback to sample data
                this.loadReportsCount();
                
                // Load reports if manager reports tab is active initially
                if (this.activeTab === 'managerReports') {
                    this.loadManagerReports();
                }
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

            async loadReportsCount() {
                try {
                    const response = await fetch('{{ route("manager.reports") }}');
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    const reports = await response.json();
                    
                    this.totalReportsCount = reports.length;
                    this.pendingReportsCount = reports.filter(report => !report.is_reconstructed).length;
                } catch (error) {
                    console.log('Using sample data for reports count');
                    // Use sample data as fallback
                    this.totalReportsCount = sampleReports.length;
                    this.pendingReportsCount = sampleReports.filter(report => !report.is_reconstructed).length;
                }
            },

            async loadManagerReports() {
                try {
                    const container = document.getElementById('reports-container');
                    container.innerHTML = `
                        <div class="text-center py-8 text-gray-500">
                            <i data-lucide="loader-2" class="w-8 h-8 animate-spin mx-auto mb-2"></i>
                            <p>Loading reports...</p>
                        </div>
                    `;
                    
                    // Recreate icons for loading state
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }

                    const response = await fetch('{{ route("manager.reports") }}');
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    const reports = await response.json();
                    this.renderReports(container, reports);
                    
                } catch (error) {
                    console.log('Using sample data for reports:', error.message);
                    const container = document.getElementById('reports-container');
                    this.renderReports(container, sampleReports);
                }
            },

            renderReports(container, reports) {
                if (reports.length === 0) {
                    container.innerHTML = `
                        <div class="text-center py-8 text-gray-500">
                            <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3"></i>
                            <p>No reports available</p>
                            <p class="text-sm">Reports will appear here when managers send them</p>
                        </div>
                    `;
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                    return;
                }
                
                // Filter reports based on selection
                let filteredReports = reports;
                if (this.reportFilter === 'recent') {
                    const oneWeekAgo = new Date();
                    oneWeekAgo.setDate(oneWeekAgo.getDate() - 7);
                    filteredReports = reports.filter(report => new Date(report.created_at) >= oneWeekAgo);
                } else if (this.reportFilter === 'pending') {
                    filteredReports = reports.filter(report => !report.is_reconstructed);
                }
                
                container.innerHTML = filteredReports.map(report => `
                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-semibold text-gray-900 text-lg">Report from ${report.manager ? report.manager.name : 'Manager'}</h3>
                                <p class="text-sm text-gray-500">
                                    ${report.emporia ? report.emporia.name : 'Emporia'} • 
                                    ${new Date(report.start_date).toLocaleDateString()} - ${new Date(report.end_date).toLocaleDateString()}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    Submitted: ${new Date(report.created_at).toLocaleString()}
                                </p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-medium ${
                                report.is_reconstructed 
                                    ? 'bg-purple-100 text-purple-800'
                                    : 'bg-blue-100 text-blue-800'
                            }">
                                ${report.is_reconstructed ? 'Reconstructed' : 'New'}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div class="text-center p-4 bg-blue-50 rounded-lg">
                                <p class="text-2xl font-bold text-blue-600">₦${(report.total_sales || 0).toLocaleString()}</p>
                                <p class="text-sm text-gray-600">Total Sales</p>
                            </div>
                            <div class="text-center p-4 bg-green-50 rounded-lg">
                                <p class="text-2xl font-bold text-green-600">₦${(report.total_profit || 0).toLocaleString()}</p>
                                <p class="text-sm text-gray-600">Total Profit</p>
                            </div>
                            <div class="text-center p-4 bg-purple-50 rounded-lg">
                                <p class="text-2xl font-bold text-purple-600">${report.transaction_count || 0}</p>
                                <p class="text-sm text-gray-600">Transactions</p>
                            </div>
                        </div>
                        
                        ${report.sales_data ? `
                        <div class="mb-4">
                            <h4 class="font-medium text-gray-700 mb-2">Sales Trend</h4>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <canvas id="chart-${report.id}" height="100"></canvas>
                            </div>
                        </div>
                        ` : ''}
                        
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
                
                // Initialize charts for each report
                filteredReports.forEach(report => {
                    if (report.sales_data) {
                        this.initializeReportChart(report);
                    }
                });
                
                // Recreate icons after rendering reports
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            },

            initializeReportChart(report) {
                const ctx = document.getElementById(`chart-${report.id}`)?.getContext('2d');
                if (!ctx) return;
                
                const salesData = report.sales_data;
                
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: salesData.labels || ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                        datasets: [{
                            label: 'Profit (₦)',
                            data: salesData.profits || [],
                            borderColor: '#10B981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '₦' + value.toLocaleString();
                                    }
                                }
                            },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }
        }));
    });

    function viewReportDetails(reportId) {
        alert('Viewing details for report #' + reportId);
        // Implement detailed report view modal
    }
</script>
@endsection