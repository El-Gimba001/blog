@extends('layouts.auth')
@section('title', 'Sign In')

@section('content')
<div class="flex justify-center items-center min-h-[70vh] bg-gray-50">
    <div class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-md border border-gray-100">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Welcome Back 👋</h2>

        <!-- Session status -->
        @if (session('status'))
            <div class="mb-4 text-green-600 text-sm text-center font-medium animate-fadeIn">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label class="block text-gray-700 mb-1 font-medium">Email Address</label>
                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required
                    class="w-full px-4 py-2 border border-gray-800 rounded-lg focus:border-black focus:ring-0 outline-none transition-all duration-150">

                @error('email')
                    <p class="text-red-500 text-xs italic mt-1 animate-fadeIn">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Password -->
            <div class="relative">
                <label class="block text-gray-700 mb-1 font-medium">Password</label>
                <input type="password" name="password" id="password" placeholder="Password" required
                    class="w-full px-4 py-2 border border-gray-800 rounded-lg focus:border-black focus:ring-0 outline-none transition-all duration-150">

                <!-- Eye Toggle -->
                <button type="button" id="togglePassword"
                    class="absolute inset-y-0 right-3 top-6 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none transition-colors duration-150">
                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" class="w-5 h-5 transition-all duration-200">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                    <svg id="eyeOffIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" class="w-5 h-5 hidden transition-all duration-200">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 3l18 18M10.477 10.477A3 3 0 0012 15a3 3 0 001.523-.423M9.88 9.88A5.978 5.978 0 0112 9c3.314 0 6 2.686 6 6 0 .91-.197 1.771-.553 2.548M4.353 4.353C3.197 5.638 2.458 7.233 2.458 9c1.274 4.057 5.064 7 9.542 7 1.104 0 2.172-.176 3.177-.5" />
                    </svg>
                </button>

                @error('password')
                    <p class="text-red-500 text-xs italic mt-1 animate-fadeIn">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Remember Me + Forgot Password -->
            <div class="flex justify-between items-center">
                <label class="flex items-center text-sm text-gray-700">
                    <input id="remember_me" type="checkbox" name="remember"
                        class="h-4 w-4 border border-gray-800 text-indigo focus:ring-0 focus:border-black hover:border-gray-900 transition-all duration-150">
                    <span class="ml-2 select-none">Remember me</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-indigo-600 text-sm font-medium hover:underline">
                    Forgot Password?
                </a>
            </div>

            <!-- Submit -->
            <button type="submit"
                class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition duration-200 disabled:opacity-50 outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-1">
                Login
            </button>
        </form>
    </div>
</div>

<!-- JS -->
<script>
    const toggle = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    const eye = document.getElementById('eyeIcon');
    const eyeOff = document.getElementById('eyeOffIcon');
    const passwordField = document.querySelector('input[name="password"]');
    const emailField = document.querySelector('input[name="email"]');
    const loginBtn = document.querySelector('button[type="submit"]');

    toggle.addEventListener('click', () => {
        const isHidden = password.getAttribute('type') === 'password';
        password.setAttribute('type', isHidden ? 'text' : 'password');
        eye.classList.toggle('hidden', !isHidden);
        eyeOff.classList.toggle('hidden', isHidden);
    });

    function validateInputs() {
        const passwordVal = passwordField.value.trim();
        const emailVal = emailField.value.trim();
        const validPassword = /^[A-Za-z0-9]{6,}$/.test(passwordVal);
        const validEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailVal);
        loginBtn.disabled = !(validPassword && validEmail);
    }

    passwordField.addEventListener('input', validateInputs);
    emailField.addEventListener('input', validateInputs);
</script>
@endsection