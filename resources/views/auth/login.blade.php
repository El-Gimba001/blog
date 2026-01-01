@extends('layouts.app')
@section('title', 'Sign In - InventoryPro')

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
                    <a href="{{ url('/') }}" class="text-gray-600 hover:text-gray-900 font-medium transition-colors">
                        Home
                    </a>
                    <a href="{{ route('register') }}" class="text-gray-600 hover:text-gray-900 font-medium transition-colors">
                        Sign Up
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Login Section -->
    <section class="py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-md mx-auto">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="mx-auto w-16 h-16 bg-indigo-600 rounded-xl flex items-center justify-center mb-4 shadow-md">
                        <i data-lucide="log-in" class="w-8 h-8 text-white"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h2>
                    <p class="text-gray-600">Sign in to your InventoryPro account</p>
                </div>

                <!-- Login Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="mb-6 p-4 bg-green-50 rounded-lg border border-green-200 flex items-center">
                            <i data-lucide="check-circle" class="w-5 h-5 text-green-500 mr-3 flex-shrink-0"></i>
                            <span class="text-green-800 text-sm">{{ session('status') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="mail" class="h-5 w-5 text-gray-400"></i>
                                </div>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    autocomplete="email"
                                    required
                                    value="{{ old('email') }}"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                                    placeholder="you@example.com"
                                >
                            </div>
                            @error('email')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i data-lucide="alert-circle" class="w-4 h-4 mr-1 flex-shrink-0"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="lock" class="h-5 w-5 text-gray-400"></i>
                                </div>
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    autocomplete="current-password"
                                    required
                                    class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                                    placeholder="Enter your password"
                                >
                                <button
                                    type="button"
                                    id="togglePassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none transition-colors duration-200"
                                >
                                    <i data-lucide="eye" id="eyeIcon" class="h-5 w-5"></i>
                                    <i data-lucide="eye-off" id="eyeOffIcon" class="h-5 w-5 hidden"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i data-lucide="alert-circle" class="w-4 h-4 mr-1 flex-shrink-0"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Remember & Forgot -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input
                                    id="remember_me"
                                    name="remember"
                                    type="checkbox"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded transition-colors duration-200"
                                >
                                <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                                    Remember me
                                </label>
                            </div>
                            <a href="{{ route('password.request') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition-colors duration-200">
                                Forgot password?
                            </a>
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            id="loginButton"
                            class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <i data-lucide="log-in" class="w-4 h-4 mr-2"></i>
                            <span id="buttonText">Sign in</span>
                            <i data-lucide="loader" id="loadingSpinner" class="w-4 h-4 ml-2 hidden animate-spin"></i>
                        </button>
                    </form>

                    <!-- Sign up link -->
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600">
                            Don't have an account?
                            <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors duration-200">
                                Sign up
                            </a>
                        </p>
                    </div>
                </div>

                <!-- Demo Hint -->
                <div class="mt-8 text-center">
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <p class="text-sm text-gray-600">
                            <i data-lucide="info" class="w-4 h-4 inline mr-1"></i>
                            <strong>Demo Access:</strong> Use your registered credentials
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 mt-20">
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
                        <li><a href="{{ url('/') }}" class="hover:text-white transition-colors">Features</a></li>
                        <li><a href="{{ url('/') }}" class="hover:text-white transition-colors">Pricing</a></li>
                        <li><a href="{{ url('/') }}" class="hover:text-white transition-colors">Security</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Support</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ url('/') }}" class="hover:text-white transition-colors">Help Center</a></li>
                        <li><a href="{{ url('/') }}" class="hover:text-white transition-colors">Contact Us</a></li>
                        <li><a href="{{ url('/') }}" class="hover:text-white transition-colors">Documentation</a></li>
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
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    if (window.lucide) {
        lucide.createIcons();
    }

    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    const eyeOffIcon = document.getElementById('eyeOffIcon');
    const loginButton = document.getElementById('loginButton');
    const buttonText = document.getElementById('buttonText');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const form = document.querySelector('form');

    // Password visibility toggle
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            eyeIcon.classList.toggle('hidden', !isPassword);
            eyeOffIcon.classList.toggle('hidden', isPassword);
        });
    }

    // Form submission loading state
    if (form) {
        form.addEventListener('submit', function() {
            loginButton.disabled = true;
            buttonText.textContent = 'Signing in...';
            loadingSpinner.classList.remove('hidden');
        });
    }

    // Real-time validation
    const emailInput = document.getElementById('email');
    
    function validateForm() {
        const email = emailInput.value.trim();
        const password = passwordInput.value.trim();
        const isValidEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        const isValidPassword = password.length >= 6;
        
        if (loginButton) {
            loginButton.disabled = !(isValidEmail && isValidPassword);
        }
    }

    if (emailInput) {
        emailInput.addEventListener('input', validateForm);
    }
    if (passwordInput) {
        passwordInput.addEventListener('input', validateForm);
    }

    // Initial validation
    validateForm();
});
</script>
@endsection