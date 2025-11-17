@extends('layouts.auth')
@section('title', 'Forgot Password')

@section('content')
<div class="flex justify-center items-center min-h-[70vh]">
    <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-md">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Reset Your Password</h2>

        <!-- Status message (email sent confirmation) -->
        @if (session('status'))
            <div class="mb-4 text-green-600 text-sm italic text-center">
                {{ session('status') }}
            </div>
        @endif

        <!-- Validation errors -->
        @if ($errors->any())
            <div class="mb-3 space-y-1">
                @foreach ($errors->all() as $error)
                    <p class="text-sm italic text-red-600">⚠ {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" id="email" placeholder="Enter your registered email" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:outline-none">
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition duration-200">
                Send Reset Link
            </button>

            <p class="text-sm text-center mt-3 text-gray-600">
                <a href="{{ route('login') }}" class="text-indigo-600 font-semibold hover:underline">Back to Login</a>
            </p>
        </form>
    </div>
</div>
@endsection