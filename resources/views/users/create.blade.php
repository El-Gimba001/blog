@extends('layouts.app')
@section('title', 'Add New User - InventoryPro')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <i data-lucide="user-plus" class="w-5 h-5 text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Add New User</h1>
                    <p class="text-gray-600 mt-1">Create a new user account with specific role permissions</p>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                <!-- Session Messages -->
                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 rounded-lg border border-green-200 flex items-center">
                        <i data-lucide="check-circle" class="w-5 h-5 text-green-500 mr-3 flex-shrink-0"></i>
                        <span class="text-green-800 text-sm">{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 p-4 bg-red-50 rounded-lg border border-red-200 flex items-center">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-red-500 mr-3 flex-shrink-0"></i>
                        <span class="text-red-800 text-sm">{{ session('error') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('users.store') }}" id="userForm" class="space-y-6">
                    @csrf

                    <!-- Personal Information Section -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i data-lucide="user" class="w-5 h-5 text-indigo-600"></i>
                            Personal Information
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Full Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                <div class="relative">
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                                        placeholder="Enter full name">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <i data-lucide="user" class="h-5 w-5 text-gray-400"></i>
                                    </div>
                                </div>
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i data-lucide="alert-circle" class="w-4 h-4 mr-1 flex-shrink-0"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                <div class="relative">
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                                        placeholder="Enter email address">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <i data-lucide="mail" class="h-5 w-5 text-gray-400"></i>
                                    </div>
                                </div>
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i data-lucide="alert-circle" class="w-4 h-4 mr-1 flex-shrink-0"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Role Selection -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i data-lucide="shield" class="w-5 h-5 text-indigo-600"></i>
                            Role Assignment
                        </h3>
                        
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">User Role *</label>
                            <div class="relative">
                                <select name="role" id="role" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none transition-colors duration-200">
                                    <option value="">Select a role</option>
                                    <option value="administrator" {{ old('role') == 'administrator' ? 'selected' : '' }}>Administrator</option>
                                    <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                                    <option value="auditor" {{ old('role') == 'auditor' ? 'selected' : '' }}>Auditor</option>
                                    <option value="sales_user" {{ old('role') == 'sales_user' ? 'selected' : '' }}>Sales User</option>
                                    <option value="store_manager" {{ old('role') == 'store_manager' ? 'selected' : '' }}>Store Manager</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i data-lucide="chevron-down" class="h-5 w-5 text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Password Section -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i data-lucide="lock" class="w-5 h-5 text-indigo-600"></i>
                            Security Settings
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                                <div class="relative">
                                    <input type="password" name="password" id="password" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 pr-12"
                                        placeholder="Create password">
                                    <button type="button" id="togglePassword"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none transition-colors duration-200">
                                        <i data-lucide="eye" id="eyeIcon" class="h-5 w-5"></i>
                                        <i data-lucide="eye-off" id="eyeOffIcon" class="h-5 w-5 hidden"></i>
                                    </button>
                                </div>
                                <!-- Password Rules -->
                                <div class="mt-3 space-y-2">
                                    <p id="ruleLength" class="text-sm text-gray-600 flex items-center gap-2">
                                        <i data-lucide="circle" class="w-3 h-3 text-gray-400"></i>
                                        Must be at least <strong>6 characters</strong>
                                    </p>
                                    <p id="ruleFormat" class="text-sm text-gray-600 flex items-center gap-2">
                                        <i data-lucide="circle" class="w-3 h-3 text-gray-400"></i>
                                        Only letters (A–Z) and numbers (0–9)
                                    </p>
                                </div>
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i data-lucide="alert-circle" class="w-4 h-4 mr-1 flex-shrink-0"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password *</label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" id="password_confirmation" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                                        placeholder="Confirm password">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <i data-lucide="lock" class="h-5 w-5 text-gray-400"></i>
                                    </div>
                                </div>
                                <p id="passwordMatch" class="mt-2 text-sm text-gray-600 flex items-center gap-2">
                                    <i data-lucide="circle" class="w-3 h-3 text-gray-400"></i>
                                    Passwords must match
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-end pt-6 border-t border-gray-200">
                        <a href="{{ route('users.manage') }}" 
                           class="flex items-center justify-center gap-2 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i>
                            Back to Users
                        </a>
                        <button type="submit" id="submitBtn" disabled
                            class="flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                            <i data-lucide="user-plus" class="w-4 h-4"></i>
                            <span id="buttonText">Create User</span>
                            <i data-lucide="loader" id="loadingSpinner" class="w-4 h-4 hidden animate-spin"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Role Information -->
            <div class="mt-8 bg-blue-50 rounded-2xl border border-blue-200 p-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center gap-2">
                    <i data-lucide="info" class="w-5 h-5 text-blue-600"></i>
                    Role Permissions Overview
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                    <div class="bg-white rounded-lg p-4 border border-blue-100">
                        <div class="flex items-center gap-2 mb-2">
                            <i data-lucide="shield" class="w-4 h-4 text-purple-600"></i>
                            <span class="font-semibold text-gray-900">Administrator</span>
                        </div>
                        <p class="text-gray-600 text-xs">Full system access, user management, and configuration</p>
                    </div>
                    <div class="bg-white rounded-lg p-4 border border-blue-100">
                        <div class="flex items-center gap-2 mb-2">
                            <i data-lucide="bar-chart-3" class="w-4 h-4 text-green-600"></i>
                            <span class="font-semibold text-gray-900">Manager</span>
                        </div>
                        <p class="text-gray-600 text-xs">Reports, analytics, and team oversight</p>
                    </div>
                    <div class="bg-white rounded-lg p-4 border border-blue-100">
                        <div class="flex items-center gap-2 mb-2">
                            <i data-lucide="search-check" class="w-4 h-4 text-orange-600"></i>
                            <span class="font-semibold text-gray-900">Auditor</span>
                        </div>
                        <p class="text-gray-600 text-xs">Stock verification and audit reporting</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Initialize Lucide icons
    if (window.lucide) {
        lucide.createIcons();
    }

    const form = document.getElementById("userForm");
    const name = document.getElementById("name");
    const email = document.getElementById("email");
    const role = document.getElementById("role");
    const password = document.getElementById("password");
    const confirm = document.getElementById("password_confirmation");
    const submitBtn = document.getElementById("submitBtn");
    const buttonText = document.getElementById("buttonText");
    const loadingSpinner = document.getElementById("loadingSpinner");
    const toggle = document.getElementById("togglePassword");
    const ruleLength = document.getElementById("ruleLength");
    const ruleFormat = document.getElementById("ruleFormat");
    const passwordMatch = document.getElementById("passwordMatch");

    // Password visibility toggle
    toggle.addEventListener("click", () => {
        const isPassword = password.type === "password";
        password.type = isPassword ? "text" : "password";
        
        const eyeIcon = document.getElementById("eyeIcon");
        const eyeOffIcon = document.getElementById("eyeOffIcon");
        
        eyeIcon.classList.toggle("hidden", !isPassword);
        eyeOffIcon.classList.toggle("hidden", isPassword);
    });

    // Update password rule indicators
    function updatePasswordRules() {
        const val = password.value;
        const lengthOK = val.length >= 6;
        const formatOK = /^[A-Za-z0-9]+$/.test(val);

        // Update length rule
        const lengthIcon = ruleLength.querySelector('i');
        lengthIcon.className = `w-3 h-3 ${lengthOK ? 'text-green-500' : 'text-gray-400'}`;
        lengthIcon.setAttribute('data-lucide', lengthOK ? 'check-circle' : 'circle');
        ruleLength.className = `text-sm ${lengthOK ? 'text-green-600' : 'text-gray-600'} flex items-center gap-2`;

        // Update format rule
        const formatIcon = ruleFormat.querySelector('i');
        formatIcon.className = `w-3 h-3 ${formatOK ? 'text-green-500' : 'text-gray-400'}`;
        formatIcon.setAttribute('data-lucide', formatOK ? 'check-circle' : 'circle');
        ruleFormat.className = `text-sm ${formatOK ? 'text-green-600' : 'text-gray-600'} flex items-center gap-2`;

        // Update password match indicator
        const matchOK = password.value === confirm.value && password.value.length > 0;
        const matchIcon = passwordMatch.querySelector('i');
        matchIcon.className = `w-3 h-3 ${matchOK ? 'text-green-500' : 'text-gray-400'}`;
        matchIcon.setAttribute('data-lucide', matchOK ? 'check-circle' : 'circle');
        passwordMatch.className = `text-sm ${matchOK ? 'text-green-600' : 'text-gray-600'} flex items-center gap-2`;

        // Re-initialize icons
        if (window.lucide) {
            lucide.createIcons();
        }
    }

    // Check form validity
    const checkFormValidity = () => {
        const validPassword = /^[A-Za-z0-9]{6,}$/.test(password.value);
        const passwordsMatch = password.value === confirm.value;
        const allFilled = name.value.trim() && 
                         email.value.trim() && 
                         role.value && 
                         validPassword && 
                         passwordsMatch;

        submitBtn.disabled = !allFilled;
        submitBtn.classList.toggle('bg-indigo-600', allFilled);
        submitBtn.classList.toggle('bg-indigo-400', !allFilled);
    };

    // Event listeners for form validation
    [name, email, role, password, confirm].forEach(el => {
        el.addEventListener('input', () => {
            if (el === password || el === confirm) {
                updatePasswordRules();
            }
            checkFormValidity();
        });
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        if (!submitBtn.disabled) {
            submitBtn.disabled = true;
            buttonText.textContent = 'Creating User...';
            loadingSpinner.classList.remove('hidden');
        }
    });

    // Initial validation check
    checkFormValidity();
    updatePasswordRules();
});
</script>

<style>
select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
}
</style>
@endsection