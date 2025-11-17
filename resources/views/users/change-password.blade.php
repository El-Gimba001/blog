@extends('layouts.app')
@section('title', 'Change User Password')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col">
    <!-- Header -->
    <header class="flex justify-between items-center px-6 py-4 bg-white shadow-sm sticky top-0 z-10">
        <h1 class="text-xl md:text-2xl font-semibold text-gray-800 flex items-center gap-2">
            <i data-lucide="key-round" class="w-6 h-6 text-fuchsia-600"></i>
            Change Password
        </h1>

        <a href="{{ route('users.manage') }}"
           class="flex items-center gap-1 text-indigo-600 hover:text-indigo-800 transition text-sm md:text-base">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to Manage Users
        </a>
    </header>

    <!-- Main -->
    <main class="flex-1 p-4 md:p-8">
        <section class="bg-white rounded-2xl shadow-md p-6 max-w-md mx-auto">
            <h2 class="text-lg font-semibold text-gray-800 mb-6">Reset Password for <span class="text-indigo-600">{{ $user->name }}</span></h2>

            <form action="{{ route('users.updatePassword', $user->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- New Password -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2">New Password</label>
                    <input type="password" name="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-fuchsia-200 focus:border-fuchsia-500">
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-fuchsia-200 focus:border-fuchsia-500">
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-3 mt-6">
                    <a href="{{ route('users.manage') }}"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-4 py-2 bg-fuchsia-600 text-white rounded-lg hover:bg-fuchsia-700 transition font-medium">
                        Update Password
                    </button>
                </div>
            </form>
        </section>
    </main>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    if (window.lucide) lucide.createIcons();
});
</script>
@endsection