@extends('layouts.app')
@section('title', 'InventoryPro - Business Inventory Management')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <i data-lucide="package" class="w-5 h-5 text-white"></i>
                        </div>
                        <span class="text-xl font-bold text-gray-900">InventoryPro</span>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 font-medium transition-colors">
                        Sign In
                    </a>
                    <a href="{{ route('login') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-indigo-600 to-purple-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                    Streamline Your 
                    <span class="text-indigo-200">Business Inventory</span>
                </h1>
                <p class="text-xl md:text-2xl text-indigo-100 mb-8 max-w-3xl mx-auto leading-relaxed">
                    Powerful, intuitive inventory management designed for growing businesses. 
                    Track sales, manage stock, and boost profitability with ease.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12">
                    <a href="{{ route('login') }}" 
                       class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center gap-2">
                        <i data-lucide="rocket" class="w-5 h-5"></i>
                        Launch Dashboard
                    </a>
                    <a href="{{ route('login') }}" 
                       class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white hover:text-indigo-600 transition-all duration-200 flex items-center gap-2">
                        <i data-lucide="play-circle" class="w-5 h-5"></i>
                        Watch Demo
                    </a>
                </div>
                
                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                        <i data-lucide="trending-up" class="w-8 h-8 text-indigo-200 mx-auto mb-3"></i>
                        <div class="text-2xl font-bold text-white">99.9%</div>
                        <div class="text-indigo-200">Uptime</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                        <i data-lucide="users" class="w-8 h-8 text-indigo-200 mx-auto mb-3"></i>
                        <div class="text-2xl font-bold text-white">500+</div>
                        <div class="text-indigo-200">Businesses</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                        <i data-lucide="package" class="w-8 h-8 text-indigo-200 mx-auto mb-3"></i>
                        <div class="text-2xl font-bold text-white">1M+</div>
                        <div class="text-indigo-200">Products Managed</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Everything You Need</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Comprehensive inventory management tools designed to save you time and boost your bottom line.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-gray-50 rounded-2xl p-8 hover:shadow-lg transition-all duration-300 border border-gray-100">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-6">
                        <i data-lucide="shopping-cart" class="w-6 h-6 text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Smart Sales Tracking</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Record sales instantly with our intuitive POS interface. Track transactions, calculate profits, and manage customer interactions seamlessly.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-gray-50 rounded-2xl p-8 hover:shadow-lg transition-all duration-300 border border-gray-100">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-6">
                        <i data-lucide="package" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Inventory Management</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Complete stock control with real-time updates. Track quantities, set reorder points, and manage multiple product categories.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-gray-50 rounded-2xl p-8 hover:shadow-lg transition-all duration-300 border border-gray-100">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-6">
                        <i data-lucide="bar-chart-3" class="w-6 h-6 text-purple-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Advanced Analytics</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Gain insights with comprehensive reports. Monitor sales trends, profit margins, and inventory performance with beautiful dashboards.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-gray-50 rounded-2xl p-8 hover:shadow-lg transition-all duration-300 border border-gray-100">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mb-6">
                        <i data-lucide="users" class="w-6 h-6 text-orange-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Role-Based Access</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Secure multi-user access with customizable roles. Manager, Sales, Admin, Auditor - each with appropriate permissions and dashboards.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-gray-50 rounded-2xl p-8 hover:shadow-lg transition-all duration-300 border border-gray-100">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-6">
                        <i data-lucide="shield" class="w-6 h-6 text-red-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Secure & Reliable</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Enterprise-grade security with data encryption and regular backups. Your business data is safe and always accessible.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-gray-50 rounded-2xl p-8 hover:shadow-lg transition-all duration-300 border border-gray-100">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-6">
                        <i data-lucide="smartphone" class="w-6 h-6 text-indigo-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Responsive Design</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Access your inventory from any device. Fully responsive interface that works perfectly on desktop, tablet, and mobile.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-indigo-600 to-purple-700">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-white mb-6">Ready to Transform Your Inventory Management?</h2>
            <p class="text-xl text-indigo-100 mb-8 max-w-2xl mx-auto">
                Join hundreds of businesses that trust InventoryPro to streamline their operations and drive growth.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('login') }}" 
                   class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition-all duration-200 shadow-lg hover:shadow-xl inline-flex items-center justify-center gap-2">
                    <i data-lucide="log-in" class="w-5 h-5"></i>
                    Sign In to Your Account
                </a>
                <a href="{{ route('login') }}" 
                   class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white hover:text-indigo-600 transition-all duration-200 inline-flex items-center justify-center gap-2">
                    <i data-lucide="user-plus" class="w-5 h-5"></i>
                    Create New Account
                </a>
            </div>
            <p class="text-indigo-200 mt-6 text-sm">
                Already have an account? Contact your administrator for access.
            </p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <i data-lucide="package" class="w-5 h-5 text-white"></i>
                        </div>
                        <span class="text-xl font-bold">InventoryPro</span>
                    </div>
                    <p class="text-gray-400 max-w-md">
                        Powerful inventory management solution designed to help businesses of all sizes streamline their operations and maximize profitability.
                    </p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Product</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Features</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Pricing</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Security</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Updates</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Support</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Help Center</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Contact Us</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Documentation</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Status</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2024 InventoryPro. All rights reserved. Built with ❤️ for businesses worldwide.</p>
            </div>
        </div>
    </footer>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (window.lucide) {
            lucide.createIcons();
        }
    });
</script>
@endsection