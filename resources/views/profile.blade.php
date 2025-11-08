<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
<div class="bg-white p-8 rounded shadow-md w-96">
    <h1 class="text-2xl font-bold mb-4">Profile</h1>

    @if(session('success'))
        <div class="text-green-500 mb-2">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="text-red-500 mb-2">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="/profile" method="POST" class="space-y-4" id="profile-form">
        @csrf
        <input 
            type="text" 
            name="name" 
            placeholder="Name" 
            value="{{ $user->name }}" 
            class="w-full border p-2 rounded"
        >
        <input 
            type="date" 
            name="birthday" 
            placeholder="Birthday" 
            value="{{ $user->birthday }}" 
            class="w-full border p-2 rounded"
        >
        <button 
            type="submit" 
            id="submit-btn"
            class="bg-green-500 text-white px-4 py-2 rounded w-full hover:bg-green-700 hover:shadow-lg transform hover:scale-[1.02] transition-all duration-200 disabled:bg-gray-400 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none"
        >
            Update
        </button>
    </form>

    <p class="mt-4 text-sm" id="logout-link">
        <a href="/logout" id="logout-btn" class="text-blue-500 hover:text-blue-700 transition-colors">Logout</a>
    </p>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('profile-form');
    const submitBtn = document.getElementById('submit-btn');
    const logoutLink = document.getElementById('logout-link');
    
    form.addEventListener('submit', function(e) {
        // Disable button và ẩn logout link
        submitBtn.disabled = true;
        submitBtn.textContent = 'Đang xử lý...';
        logoutLink.classList.add('hidden');
        
        // Form sẽ submit bình thường (không preventDefault)
    });
    const logoutBtn = document.getElementById('logout-btn');

    logoutBtn.addEventListener('click', function(e) {
        // Ẩn form và các button
        submitBtn.classList.add('hidden');
        
        // Đổi text logout
        logoutBtn.textContent = 'Đang đăng xuất...';
        
        window.location.href = '/logout';
    });
    // Enable lại nếu có lỗi validation từ server
    @if($errors->any())
        submitBtn.disabled = false;
        submitBtn.textContent = 'Update';
        logoutLink.classList.remove('hidden');
    @endif
});
</script>
</body>
</html>