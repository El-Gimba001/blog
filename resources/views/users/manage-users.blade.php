@extends('layouts.app')
@section('title', 'Manage Users')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col">
    <!-- Header -->
    <header class="flex justify-between items-center px-6 py-4 bg-white shadow-sm sticky top-0 z-10">
        <h1 class="text-xl md:text-2xl font-semibold text-gray-800 flex items-center gap-2">
            <i data-lucide="users" class="w-6 h-6 text-violet-600"></i>
            Manage Users
        </h1>

        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-1 text-indigo-600 hover:text-indigo-800 transition text-sm md:text-base">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to Dashboard
        </a>
    </header>

    <!-- Main Content -->
    <main class="flex-1 p-4 md:p-8 space-y-8">
        <!-- User Management Overview -->
        <section class="bg-white rounded-2xl shadow-md p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">User Accounts</h2>
                    <p class="text-gray-500 text-sm">Manage registered users and reset their passwords.</p>
                </div>
                <a href="{{ route('users.create') }}"
                    class="mt-3 md:mt-0 bg-violet-600 hover:bg-violet-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 text-sm font-medium shadow">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    Add New User
                </a>
            </div>

            <!-- User Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-gray-700 border">
                    <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="py-3 px-4">#</th>
                            <th class="py-3 px-4">Name</th>
                            <th class="py-3 px-4">Email</th>
                            <th class="py-3 px-4">Role</th>
                            <th class="py-3 px-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $index => $user)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $index + 1 }}</td>
                            <td class="py-3 px-4">{{ $user->name }}</td>
                            <td class="py-3 px-4">{{ $user->email }}</td>
                            <td class="py-3 px-4 capitalize">{{ $user->role ?? 'User' }}</td>
                            <td class="py-3 px-4 flex justify-center gap-3 flex-wrap">
                                <!-- Edit -->
                                <a href="{{ route('users.edit', $user->id) }}"
                                    class="flex items-center gap-1 text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    <i data-lucide="edit" class="w-4 h-4"></i> Edit
                                </a>

                                <!-- Change Password -->
                                <form action="{{ route('users.updatePassword', $user->id) }}" method="POST" onsubmit="return confirm('Change password for {{ $user->name }}?')">
                                    @csrf
                                    <input type="hidden" name="password" value="default123">
                                    <button type="button"
                                        onclick="openPasswordModal({{ $user->id }})"
                                        class="flex items-center gap-1 text-fuchsia-600 hover:text-fuchsia-800 text-sm font-medium">
                                        <i data-lucide="key-round" class="w-4 h-4"></i> Change Password
                                    </button>
                                </form>

                                <!-- Delete -->
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Delete {{ $user->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="flex items-center gap-1 text-red-600 hover:text-red-800 text-sm font-medium">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Password Modal -->
                <div id="passwordModal"
                     class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                  <div class="bg-white p-6 rounded-xl shadow-xl w-full max-w-md">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">
                      Change Password
                    </h2>
                    <form id="passwordForm" method="POST">
                      @csrf
                      <div>
                        <label class="block text-sm text-gray-700 mb-2">New Password</label>
                        <input type="password" name="password"
                               placeholder="Enter new password"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-fuchsia-200 focus:border-fuchsia-500"
                               required>
                      </div>
                      <div class="flex justify-end gap-3 mt-5">
                        <button type="button" onclick="closePasswordModal()"
                                class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg text-gray-700">
                          Cancel
                        </button>
                        <button type="submit"
                                class="bg-fuchsia-600 hover:bg-fuchsia-700 text-white px-4 py-2 rounded-lg">
                          Update
                        </button>
                      </div>
                    </form>
                  </div>
                </div>  
            </div>
        </section>
    </main>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    if (window.lucide) lucide.createIcons();
});

let currentUserId = null;

function openPasswordModal(id) {
    currentUserId = id;
    const modal = document.getElementById('passwordModal');
    const form = document.getElementById('passwordForm');
    form.action = "{{ url('/users') }}/" + id + "/change-password"; // ✅ Fixed
    modal.classList.remove('hidden');
}

function closePasswordModal() {
    document.getElementById('passwordModal').classList.add('hidden');
}
</script>
@endsection