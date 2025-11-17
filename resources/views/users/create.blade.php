@extends('layouts.app')
@section('title', 'Add New User')

@section('content')
<div class="flex justify-center items-center min-h-[70vh]">
    <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-md">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Add New User</h2>

        <form method="POST" action="{{ route('users.store') }}" id="userForm" class="space-y-5">
            @csrf

            <!-- Full Name -->
            <div>
                <label class="block text-gray-700 mb-1">Full Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="w-full px-0 py-2 border-0 border-b-2 border-gray-300 focus:border-indigo-500 focus:ring-0 outline-none transition">
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    class="w-full px-0 py-2 border-0 border-b-2 border-gray-300 focus:border-indigo-500 focus:ring-0 outline-none transition">
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role -->
            <div>
                <label class="block text-gray-700 font-medium mb-2">Role</label>
                <select name="role"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-indigo-200 focus:border-indigo-500">
                    <option value="administrator">Administrator</option>
                    <option value="manager">Manager</option>
                    <option value="auditor">Auditor</option>
                    <option value="sales_user">Sales User</option>
                    <option value="store_manager">Store Manager</option>
                </select>
            </div>

            <!-- Password with toggle -->
            <div class="relative">
                <label class="block text-gray-700 mb-1">Password</label>
                <input type="password" name="password" id="password"
                    placeholder="At least 6 characters, letters & digits" required
                    class="w-full px-0 py-2 border-0 border-b-2 border-gray-300 focus:border-indigo-500 focus:ring-0 outline-none pr-10 transition">

                <button type="button" id="togglePassword"
                    class="absolute right-0 top-7 text-gray-500 focus:outline-none">
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
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-gray-700 mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="w-full px-0 py-2 border-0 border-b-2 border-gray-300 focus:border-indigo-500 focus:ring-0 outline-none transition">
            </div>

            <!-- Submit -->
            <button type="submit" id="submitBtn" disabled
                class="w-full bg-indigo-300 text-white py-2 rounded-lg cursor-not-allowed transition duration-200">
                Register User
            </button>
        </form>
    </div>
</div>

<!-- Scripts -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("userForm");
    const name = document.getElementById("name");
    const email = document.getElementById("email");
    const role = document.getElementById("role");
    const password = document.getElementById("password");
    const confirm = document.getElementById("password_confirmation");
    const submitBtn = document.getElementById("submitBtn");
    const toggle = document.getElementById("togglePassword");

    // If these rules don’t exist yet, you can add them near your password field
    let ruleLength = document.getElementById("ruleLength");
    let ruleFormat = document.getElementById("ruleFormat");
    if (!ruleLength || !ruleFormat) {
        const ruleBox = document.createElement("div");
        ruleBox.classList.add("text-sm", "mt-2", "space-y-1");
        ruleBox.innerHTML = `
            <p id="ruleLength" class="text-gray-600">❌ Must be at least <strong>6 characters</strong></p>
            <p id="ruleFormat" class="text-gray-600">❌ Only letters (A–Z) and numbers (0–9)</p>
        `;
        password.insertAdjacentElement("afterend", ruleBox);
        ruleLength = document.getElementById("ruleLength");
        ruleFormat = document.getElementById("ruleFormat");
    }

    // ✅ Toggle password visibility
    toggle.addEventListener("click", () => {
        password.type = password.type === "password" ? "text" : "password";
    });

    // ✅ Live password rules
    password.addEventListener("input", () => {
        const val = password.value;
        const lengthOK = val.length >= 6;
        const formatOK = /^[A-Za-z0-9]+$/.test(val);

        ruleLength.innerHTML = lengthOK 
            ? "✅ At least <strong>6 characters</strong>"
            : "❌ Must be at least <strong>6 characters</strong>";
        ruleLength.classList.toggle("text-green-600", lengthOK);
        ruleLength.classList.toggle("text-gray-600", !lengthOK);

        ruleFormat.innerHTML = formatOK
            ? "✅ Only letters (A–Z) and numbers (0–9)"
            : "❌ Only letters (A–Z) and numbers (0–9)";
        ruleFormat.classList.toggle("text-green-600", formatOK);
        ruleFormat.classList.toggle("text-gray-600", !formatOK);

        checkFormValidity();
    });

    // ✅ Check form validity before enabling submit
    const checkFormValidity = () => {
        const validPassword = /^[A-Za-z0-9]{6,}$/.test(password.value);
        const allFilled =
            name.value.trim() &&
            email.value.trim() &&
            role.value &&
            validPassword &&
            password.value === confirm.value;

        submitBtn.disabled = !allFilled;
        submitBtn.classList.toggle("bg-indigo-600", allFilled);
        submitBtn.classList.toggle("bg-indigo-300", !allFilled);
        submitBtn.classList.toggle("cursor-pointer", allFilled);
        submitBtn.classList.toggle("cursor-not-allowed", !allFilled);
    };

    // ✅ Run validation on all input fields
    form.querySelectorAll("input, select").forEach(el => {
        el.addEventListener("input", checkFormValidity);
        el.addEventListener("change", checkFormValidity);
    });

    // ✅ Initial run
    checkFormValidity();
});
</script>
@endsection