<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
<div class="bg-white p-8 rounded shadow-md w-96">
    <h1 class="text-2xl font-bold mb-4">Reset Password</h1>

    @if($errors->any())
        <div class="text-red-500 mb-2">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form id="resetForm" action="/reset-password" method="POST" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input 
            type="password" 
            name="password" 
            placeholder="New Password" 
            class="w-full border p-2 rounded" 
            required
        >
        <input 
            type="password" 
            name="password_confirmation" 
            placeholder="Confirm Password" 
            class="w-full border p-2 rounded" 
            required
        >
        <button 
            type="submit" 
            id="submit-btn"
            class="bg-green-500 text-white px-4 py-2 rounded w-full hover:bg-green-700 hover:shadow-lg transform hover:scale-[1.02] transition-all duration-200 disabled:bg-gray-400 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none"
        >
            Reset Password
        </button>
    </form>

    <p class="mt-4 text-sm" id="nav-link">
        <a href="/login" class="text-blue-500 hover:text-blue-700 transition-colors">Back to login</a>
    </p>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('resetForm');
    const submitBtn = document.getElementById('submit-btn');
    const navLink = document.getElementById('nav-link');
    
    form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Đang xử lý...';
        navLink.classList.add('hidden');
    });
    
    @if($errors->any())
        submitBtn.disabled = false;
        submitBtn.textContent = 'Reset Password';
        navLink.classList.remove('hidden');
    @endif
});
</script>
</body>
</html>
