<!DOCTYPE html>
<html>
<head>
    <title>Admin Profile</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
<div class="bg-white p-8 rounded shadow-md w-96">
    <h1 class="text-2xl font-bold mb-4">Admin Profile</h1>

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
        <div class="flex gap-2" id="action-buttons">
            <button 
                type="submit" 
                id="submit-btn"
                class="bg-green-500 text-white px-4 py-2 rounded flex-1 hover:bg-green-700 hover:shadow-lg transform hover:scale-[1.02] transition-all duration-200 disabled:bg-gray-400 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none"
            >
                Update
            </button>
            <a 
                href="/admin/users" 
                id="manage-link"
                class="bg-blue-500 text-white px-4 py-2 rounded flex-1 text-center hover:bg-blue-700 hover:shadow-lg transform hover:scale-[1.02] transition-all duration-200"
            >
                Management User
            </a>
        </div>
    </form>

    <p class="mt-4 text-sm" id="logout-link">
        <a href="/logout" id="logout-btn" class="text-blue-500 hover:text-blue-700 transition-colors">Logout</a>
    </p>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('profile-form');
    const submitBtn = document.getElementById('submit-btn');
    const manageLink = document.getElementById('manage-link');
    const logoutBtn = document.getElementById('logout-btn');
    const actionButtons = document.getElementById('action-buttons');
    const logoutLink = document.getElementById('logout-link');
    
    // Function để reset về trạng thái ban đầu
    function resetUI() {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Update';
        
        manageLink.style.pointerEvents = 'auto';
        manageLink.style.opacity = '1';
        manageLink.textContent = 'Management User';
        
        actionButtons.classList.remove('hidden');
        logoutLink.classList.remove('hidden');
    }
    
    // Xử lý submit form
    form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Đang xử lý...';
        manageLink.style.pointerEvents = 'none';
        manageLink.style.opacity = '0.5';
        logoutLink.classList.add('hidden');
    });
    
    // Xử lý click Management User
    manageLink.addEventListener('click', function(e) {
        e.preventDefault();
        
        submitBtn.disabled = true;
        manageLink.style.pointerEvents = 'none';
        manageLink.style.opacity = '0.5';
        manageLink.textContent = 'Đang chuyển...';
        logoutLink.classList.add('hidden');
        
        setTimeout(() => {
            window.location.href = '/admin/users';
        }, 100);
    });
    
    // Xử lý click Logout
    logoutLink.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Ẩn form và các button
        actionButtons.classList.add('hidden');
        
        // Đổi text logout
        logoutBtn.style.pointerEvents = 'none';
        logoutBtn.style.opacity = '0.5';
        logoutBtn.textContent = 'Đang đăng xuất...';
        
        setTimeout(() => {
            window.location.href = '/logout';
        }, 100);
    });
    
    // Enable lại nếu có lỗi validation từ server
    @if($errors->any())
        resetUI();
    @endif
    
    // Reset UI khi user bấm back button
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            resetUI();
        }
    });
});
</script>
</body>
</html>