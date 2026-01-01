@extends('layouts.app')
@section('title', 'Manager Panel')

@section('content')
<div class="min-h-screen bg-gray-50 flex" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">
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
                <i data-lucide="trending-up" class="w-8 h-8 text-green-600"></i>
                <div>
                    <h1 class="text-lg font-bold text-gray-900">Manager</h1>
                    <p class="text-gray-500 text-sm">{{ auth()->user()->name }}</p>
                </div>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden">
                <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
            </button>
        </div>

        <nav class="p-4 space-y-1">
            <!-- Sales Section -->
            <div class="px-3 py-2">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Sales</h3>
            </div>
            <a href="{{ route('transaction.daily') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition group">
                <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                <span>Daily Sale</span>
            </a>
            <a href="{{ route('sold.items') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-700 rounded-lg transition group">
                <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                <span>Sold Items</span>
            </a>
            <a href="{{ route('sales.entry') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 rounded-lg transition group">
                <i data-lucide="receipt" class="w-5 h-5"></i>
                <span>Sell</span>
            </a>
            
            <!-- Inventory Section -->
            <div class="px-3 py-2 mt-4">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Inventory</h3>
            </div>
            <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition group">
                <i data-lucide="package" class="w-5 h-5"></i>
                <span>Stock List</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition group">
                <i data-lucide="move-right" class="w-5 h-5"></i>
                <span>Item Transfer</span>
            </a>
            
            <!-- Financial Section -->
            <div class="px-3 py-2 mt-4">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Financial</h3>
            </div>
            <a href="#" class="flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-700 rounded-lg transition group">
                <i data-lucide="book" class="w-5 h-5"></i>
                <span>All Ledger Summary</span>
            </a>
            <a href="{{ route('ledger.customer-ledger') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 rounded-lg transition group">
                <i data-lucide="notebook-text" class="w-5 h-5"></i>
                <span>Customer Ledger</span>
            </a>
            
            <!-- Management Section -->
            <div class="px-3 py-2 mt-4">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Management</h3>
            </div>
            <a href="{{ route('users.create') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg transition group">
                <i data-lucide="user-plus" class="w-5 h-5"></i>
                <span>Create User Account</span>
            </a>
            <a href="{{ route('locations.manage') }}" class="flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition group">
                <i data-lucide="map-pin-house" class="w-5 h-5"></i>
                <span>Location</span>
            </a>
            
            <!-- System Section -->
            <div class="px-3 py-2 mt-4">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">System</h3>
            </div>
            <a href="#" class="flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-gray-50 hover:text-gray-700 rounded-lg transition group">
                <i data-lucide="settings" class="w-5 h-5"></i>
                <span>Settings</span>
            </a>

            <!-- Report Section -->
            <div class="px-3 py-2 mt-4">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Reports</h3>
            </div>
            <button @click="generateReport()" class="w-full flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-red-50 hover:text-red-700 rounded-lg transition group">
                <i data-lucide="send" class="w-5 h-5"></i>
                <span>Send Report to Administrator</span>
            </button>

            <a href="{{ route('manager.audit.reports') }}"
            class="flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg transition group">
                <i data-lucide="file-search" class="w-5 h-5"></i>
                <span>Auditor Reports</span>
            </a>
        </nav>
    </div>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="flex items-center justify-between px-6 py-4">
                <div class="flex items-center gap-4">
                    <!-- Single Toggle Button -->
                    <button @click="sidebarOpen = !sidebarOpen" class="flex">
                        <i data-lucide="menu" class="w-6 h-6 text-gray-600"></i>
                    </button>
                    
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Daily Profit Trend Analytics</h1>
                        <div class="flex items-center gap-4 text-sm text-gray-600">
                            <span>Welcome, {{ auth()->user()->name }}</span>
                            <span>•</span>
                            <span x-text="new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })"></span>
                            <span>•</span>
                            <span x-text="new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' })"></span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
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

        <!-- Profit Trend Analytics -->
        <div class="flex-1 p-6 overflow-auto">
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Today's Profit</p>
                            <p class="text-xl font-bold text-gray-900">₦85,250</p>
                            <p class="text-green-600 text-sm flex items-center gap-1">
                                <i data-lucide="trending-up" class="w-3 h-3"></i>
                                +12% from yesterday
                            </p>
                        </div>
                        <i data-lucide="dollar-sign" class="w-6 h-6 text-green-600"></i>
                    </div>
                </div>
                
                <div class="bg-white p-4 rounded-lg shadow border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">7-Day Average</p>
                            <p class="text-xl font-bold text-gray-900">₦67,980</p>
                            <p class="text-blue-600 text-sm">Per day</p>
                        </div>
                        <i data-lucide="calendar" class="w-6 h-6 text-blue-600"></i>
                    </div>
                </div>
                
                <div class="bg-white p-4 rounded-lg shadow border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Growth Rate</p>
                            <p class="text-xl font-bold text-gray-900">+12.5%</p>
                            <p class="text-purple-600 text-sm">vs last week</p>
                        </div>
                        <i data-lucide="activity" class="w-6 h-6 text-purple-600"></i>
                    </div>
                </div>
                
                <div class="bg-white p-4 rounded-lg shadow border-l-4 border-orange-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Best Day</p>
                            <p class="text-xl font-bold text-gray-900">₦128,500</p>
                            <p class="text-orange-600 text-sm">Yesterday</p>
                        </div>
                        <i data-lucide="award" class="w-6 h-6 text-orange-600"></i>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Profit Trend Chart -->
                <div class="xl:col-span-2 bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-800">Daily Profit Trend (Last 7 Days)</h2>
                        <div class="flex gap-2">
                            <div class="relative">
                                <select class="border border-gray-300 rounded-lg px-4 py-2 text-sm bg-white appearance-none pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option>Last 7 Days</option>
                                    <option>Last 30 Days</option>
                                    <option>Last 90 Days</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Profit Trend Chart Container -->
                    <div class="h-80">
                        <canvas id="profitTrendChart"></canvas>
                    </div>
                </div>

                <!-- Right Sidebar - Quick Actions & Recent -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h2>
                        <div class="space-y-3">
                            <a href="{{ route('sales.entry') }}" class="flex items-center gap-3 p-3 border border-emerald-200 rounded-lg hover:bg-emerald-50 transition group">
                                <div class="bg-emerald-100 p-2 rounded-lg">
                                    <i data-lucide="plus-circle" class="w-4 h-4 text-emerald-600"></i>
                                </div>
                                <span class="font-medium text-gray-900">New Sale</span>
                            </a>
                            
                            <a href="{{ route('products.index') }}" class="flex items-center gap-3 p-3 border border-green-200 rounded-lg hover:bg-green-50 transition group">
                                <div class="bg-green-100 p-2 rounded-lg">
                                    <i data-lucide="package" class="w-4 h-4 text-green-600"></i>
                                </div>
                                <span class="font-medium text-gray-900">Stock Overview</span>
                            </a>
                            
                            <a href="{{ route('users.create') }}" class="flex items-center gap-3 p-3 border border-indigo-200 rounded-lg hover:bg-indigo-50 transition group">
                                <div class="bg-indigo-100 p-2 rounded-lg">
                                    <i data-lucide="user-plus" class="w-4 h-4 text-indigo-600"></i>
                                </div>
                                <span class="font-medium text-gray-900">Add Staff</span>
                            </a>

                            <a href="{{ route('transaction.daily') }}" class="flex items-center gap-3 p-3 border border-blue-200 rounded-lg hover:bg-blue-50 transition group">
                                <div class="bg-blue-100 p-2 rounded-lg">
                                    <i data-lucide="bar-chart-3" class="w-4 h-4 text-blue-600"></i>
                                </div>
                                <span class="font-medium text-gray-900">Daily Sales Report</span>
                            </a>
                        </div>
                    </div>

                    <!-- Recent Profit Activity -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-800">Recent Profit Activity</h2>
                            <a href="{{ route('transaction.daily') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                View All
                            </a>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-2">
                                <div class="flex items-center gap-2">
                                    <div class="bg-green-100 p-1 rounded">
                                        <i data-lucide="trending-up" class="w-3 h-3 text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Today</p>
                                        <p class="text-xs text-gray-500">Current profit</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-green-600">+₦85,250</p>
                                    <p class="text-xs text-gray-500">12% increase</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-2">
                                <div class="flex items-center gap-2">
                                    <div class="bg-green-100 p-1 rounded">
                                        <i data-lucide="trending-up" class="w-3 h-3 text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Yesterday</p>
                                        <p class="text-xs text-gray-500">Previous day</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-green-600">+₦128,500</p>
                                    <p class="text-xs text-gray-500">Best day</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-2">
                                <div class="flex items-center gap-2">
                                    <div class="bg-red-100 p-1 rounded">
                                        <i data-lucide="trending-down" class="w-3 h-3 text-red-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">2 Days Ago</p>
                                        <p class="text-xs text-gray-500">Low performance</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-red-600">₦58,300</p>
                                    <p class="text-xs text-gray-500">8% decrease</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js for profit trends -->
<!-- Chart.js for profit trends -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        
        // Real-time clock update
        function updateClock() {
            const now = new Date();
            const timeElements = document.querySelectorAll('[x-text*="toLocaleTimeString"]');
            timeElements.forEach(element => {
                element.textContent = now.toLocaleTimeString('en-US', { 
                    hour: '2-digit', 
                    minute: '2-digit', 
                    second: '2-digit' 
                });
            });
        }
        setInterval(updateClock, 1000);
        
        // Profit Trend Chart
        const ctx = document.getElementById('profitTrendChart');
        if (ctx) {
            const profitChart = new Chart(ctx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Today'],
                    datasets: [{
                        label: 'Daily Profit (₦)',
                        data: [75200, 58300, 89400, 67100, 112300, 128500, 85250],
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#10B981',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    return `Profit: ₦${context.parsed.y.toLocaleString()}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            grid: {
                                drawBorder: false,
                            },
                            ticks: {
                                callback: function(value) {
                                    return '₦' + value.toLocaleString();
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Report generation function
        window.generateReport = async function() {
            const button = event.target.closest('button');
            const originalHTML = button.innerHTML;
            
            try {
                // Show loading state
                button.innerHTML = '<i data-lucide="loader-2" class="w-5 h-5 animate-spin"></i><span>Generating Report...</span>';
                button.disabled = true;

                // Prepare report data
                const reportPayload = {
                    total_sales: 452300,
                    total_profit: 85250,
                    transaction_count: 47,
                    sales_data: {
                        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Today'],
                        profits: [75200, 58300, 89400, 67100, 112300, 128500, 85250],
                        sales: [201500, 158000, 245000, 189000, 312000, 385000, 265000]
                    },
                    notes: `Daily sales report for ${new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}`
                };

                // Send report to server
                const response = await fetch('{{ route("manager.send-report") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(reportPayload)
                });

                const result = await response.json();

                if (result.success) {
                    showNotification(`✅ ${result.message} (${result.report_date})`, 'success');
                } else {
                    showNotification('❌ ' + result.message, 'error');
                }

            } catch (error) {
                console.error('Error sending report:', error);
                showNotification('❌ Failed to send report. Please try again.', 'error');
            } finally {
                // Reset button
                button.innerHTML = originalHTML;
                button.disabled = false;
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }
        };

        // Notification function
        function showNotification(message, type = 'info') {
            // Remove existing notifications
            const existingNotification = document.getElementById('report-notification');
            if (existingNotification) {
                existingNotification.remove();
            }

            // Create notification element
            const notification = document.createElement('div');
            notification.id = 'report-notification';
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

        // Make notification function globally available
        window.showNotification = showNotification;
    });
</script>
@endsection