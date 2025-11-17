@extends('layouts.auth')
@section('title', 'Reset Password')

@section('content')
<div class="flex justify-center items-center min-h-[70vh] bg-gray-50">
    <div class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-md border border-gray-100">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Reset Your Password</h2>

        @if (session('status'))
            <div class="mb-4 text-green-600 text-sm text-center font-medium animate-fadeIn">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email -->
            <div>
                <label for="email" class="block text-gray-700 mb-1 font-medium">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email', $request->email) }}" required
                    class="w-full px-4 py-2 border border-gray-800 rounded-lg focus:border-black focus:ring-0 outline-none transition-all duration-150">
                @error('email')
                    <p class="text-xs italic text-red-600 mt-1 animate-fadeIn">{{ $message }}</p>
                @enderror
            </div>

            <!-- New Password -->
            <div class="relative">
                <label for="password" class="block text-gray-700 mb-1 font-medium">New Password</label>
                <input type="password" id="password" name="password" placeholder="Enter new password" required
                    class="w-full px-4 py-2 border border-gray-800 rounded-lg focus:border-black focus:ring-0 outline-none transition-all duration-150 pr-10">

                <!-- Eye toggle -->
                <button type="button" id="togglePassword"
                    class="absolute inset-y-0 right-3 top-6 flex items-center text-gray-500 focus:outline-none">
                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                    <svg id="eyeOffIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" class="w-5 h-5 hidden">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 3l18 18M10.477 10.477A3 3 0 0012 15a3 3 0 001.523-.423M9.88 9.88A5.978 5.978 0 0112 9c3.314 0 6 2.686 6 6 0 .91-.197 1.771-.553 2.548M4.353 4.353C3.197 5.638 2.458 7.233 2.458 9c1.274 4.057 5.064 7 9.542 7 1.104 0 2.172-.176 3.177-.5" />
                    </svg>
                </button>


                @error('password')
                    <p class="text-xs italic text-red-600 mt-1 animate-fadeIn">{{ $message }}</p>
                @enderror
            </div>

            <p class="text-gray-500 text-xs italic mt-1">Password must be at least 6 characters (letters or numbers)</p>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-gray-700 mb-1 font-medium">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                    class="w-full px-4 py-2 border border-gray-800 rounded-lg focus:border-black focus:ring-0 outline-none transition-all duration-150">
                @error('password_confirmation')
                    <p class="text-xs italic text-red-600 mt-1 animate-fadeIn">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <button type="submit" id="resetBtn"
                class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition duration-200 disabled:opacity-50">
                Reset Password
            </button>
        </form>

        <!-- Back to login -->
        <p class="text-center text-sm text-gray-600 mt-5">
            Remember your password?
            <a href="{{ route('login') }}" class="text-indigo-600 font-medium hover:underline">
                Back to Login
            </a>
        </p>
    </div>
</div>

<!-- Password toggle and validation -->
<script>
    const password = document.getElementById('password');
    const confirm = document.getElementById('password_confirmation');
    const btn = document.getElementById('resetBtn');
    const toggle = document.getElementById('togglePassword');
    const eye = document.getElementById('eyeIcon');
    const eyeOff = document.getElementById('eyeOffIcon');

    function validatePassword() {
        const passVal = password.value.trim();
        const confirmVal = confirm.value.trim();
        const validPass = /^[A-Za-z0-9]{6,}$/.test(passVal);
        btn.disabled = !(validPass && passVal === confirmVal);
    }

    password.addEventListener('input', validatePassword);
    confirm.addEventListener('input', validatePassword);
    validatePassword();

    toggle.addEventListener('click', () => {
        const isHidden = password.getAttribute('type') === 'password';
        password.setAttribute('type', isHidden ? 'text' : 'password');
        eye.classList.toggle('hidden', !isHidden);
        eyeOff.classList.toggle('hidden', isHidden);
    });
</script>
@endsection