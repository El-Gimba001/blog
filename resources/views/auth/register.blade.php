@extends('layouts.auth')
@section('title', 'Create Account')

@section('content')

<form method="POST" action="{{ route('register') }}" class="space-y-4">  
    @csrf  {{-- Full Name --}}  
<input type="text" name="name" placeholder="Full Name" value="{{ old('name') }}" required  
    class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-indigo-300">  
@error('name')  
    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>  
@enderror  

{{-- Email --}}  
<input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required  
    class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-indigo-300">  
@error('email')  
    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>  
@enderror  

{{-- Password with show/hide and live validation --}}  
<div class="relative">  
    <input type="password" name="password" id="password" placeholder="Password" required  
        class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-indigo-300 pr-10">  
    <button type="button" id="togglePassword"  
        class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-indigo-500">  
        👁  
    </button>  
</div>  

{{-- Password rules feedback --}}  
<ul id="passwordRules" class="text-sm text-gray-600 mt-1 space-y-1">  
    <li id="ruleLength" class="flex items-center gap-2">  
        ❌ Must be at least <strong>6 characters</strong>  
    </li>  
    <li id="ruleFormat" class="flex items-center gap-2">  
        ❌ Only letters (A–Z) and numbers (0–9)  
    </li>  
</ul>  

@error('password')  
    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>  
@enderror  

{{-- Confirm Password --}}  
<input type="password" name="password_confirmation" placeholder="Confirm Password" required  
    class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-indigo-300">  
@error('password_confirmation')  
    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>  
@enderror  

{{-- Register Button --}}  
<button type="submit"  
    class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition">  
    Register  
</button>  

{{-- Link to Login --}}  
<p class="text-sm text-center mt-2">  
    Already have an account?  
    <a href="{{ route('login') }}" class="text-indigo-600 font-semibold hover:underline">Login</a>  
</p>

</form>  {{-- JS: Toggle Password + Live Rules --}}

<script>  
    const password = document.getElementById('password');  
    const toggle = document.getElementById('togglePassword');  
    const ruleLength = document.getElementById('ruleLength');  
    const ruleFormat = document.getElementById('ruleFormat');  
  
    toggle.addEventListener('click', () => {  
        const type = password.type === 'password' ? 'text' : 'password';  
        password.type = type;  
    });  
  
    password.addEventListener('input', () => {  
        const value = password.value;  
        const lengthOK = value.length >= 6;  
        const formatOK = /^[A-Za-z0-9]+$/.test(value);  
  
        ruleLength.innerHTML = lengthOK   
            ? '✅ At least <strong>6 characters</strong>'  
            : '❌ Must be at least <strong>6 characters</strong>';  
        ruleLength.classList.toggle('text-green-600', lengthOK);  
        ruleLength.classList.toggle('text-gray-600', !lengthOK);  
  
        ruleFormat.innerHTML = formatOK   
            ? '✅ Only letters (A–Z) and numbers (0–9)'  
            : '❌ Only letters (A–Z) and numbers (0–9)';  
        ruleFormat.classList.toggle('text-green-600', formatOK);  
        ruleFormat.classList.toggle('text-gray-600', !formatOK);  
    });  
</script>  @endsection