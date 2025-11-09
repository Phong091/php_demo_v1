<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
<div class="bg-white p-8 rounded shadow-md w-96">
    <h1 class="text-2xl font-bold mb-4">Login</h1>

    @if(session('success'))
        <div class="text-green-500 mb-2">{{ session('success') }}</div>
    @endif

    <div id="ajaxError" class="text-red-500 mb-2 hidden"></div>

    <form id="loginForm" class="space-y-4">
        @csrf
        <input type="email" name="email" placeholder="Email" required class="w-full border p-2 rounded">
        <input type="password" name="password" placeholder="Password" required class="w-full border p-2 rounded">
        <div class="flex items-center" id="remember-container">
            <input type="checkbox" name="remember" id="remember" class="mr-2">
            <label for="remember" class="text-sm">Remember me</label>
        </div>
        <button 
            type="submit" 
            id="submit-btn"
            class="bg-blue-500 text-white px-4 py-2 rounded w-full hover:bg-blue-700 hover:shadow-lg transform hover:scale-[1.02] transition-all duration-200 disabled:bg-gray-400 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none"
        >
            Login
        </button>
    </form>

    <div class="mt-4 flex justify-between text-sm" id="nav-links">
        <a href="/forgot-password" class="text-blue-500 hover:text-blue-700 transition-colors">Forgot Password?</a>
        <a href="/register" class="text-blue-500 hover:text-blue-700 transition-colors">Register</a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const submitBtn = document.getElementById('submit-btn');
    const navLinks = document.getElementById('nav-links');
    const ajaxError = document.getElementById('ajaxError');
    const rememberCheckbox = document.getElementById('remember');
    const rememberContainer = document.getElementById('remember-container');
    
    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        
        // Disable button và ẩn links
        submitBtn.disabled = true;
        rememberCheckbox.disabled = true;
        submitBtn.textContent = 'Đang xử lý...';
        navLinks.classList.add('hidden');
        rememberContainer.classList.add('opacity-50');
        ajaxError.classList.add('hidden');
        
        const data = new FormData(form);
        const payload = {
            email: data.get('email'),
            password: data.get('password'),
            remember: data.get('remember') === 'on'
        };
        
        try {
            const res = await fetch('/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
                },
                body: JSON.stringify(payload),
                credentials: 'same-origin'
            });
            
            if (res.ok) {
                const json = await res.json();
                if (json.success && json.redirect) {
                    window.location.href = json.redirect;
                    return;
                }
            }
            
            let msg = 'Đăng nhập thất bại. Vui lòng kiểm tra lại thông tin.';
            try {
                const err = await res.json();
                if (err) {
                    if (err.message) msg = err.message;
                    if (err.errors) {
                        const list = [];
                        Object.values(err.errors).forEach(arr => {
                            if (Array.isArray(arr)) list.push(...arr);
                        });
                        if (list.length) msg = list.join('\n');
                    }
                }
            } catch (_) {}
            
            ajaxError.textContent = msg;
            ajaxError.classList.remove('hidden');
            
            // Enable lại button và hiện links khi có lỗi
            submitBtn.disabled = false;
            rememberCheckbox.disabled = false;
            submitBtn.textContent = 'Login';
            navLinks.classList.remove('hidden');
            rememberContainer.classList.remove('opacity-50');
            
        } catch (error) {
            ajaxError.textContent = 'Có lỗi xảy ra. Vui lòng thử lại.';
            ajaxError.classList.remove('hidden');
            
            // Enable lại button và hiện links khi có lỗi
            submitBtn.disabled = false;
            rememberCheckbox.disabled = false;
            submitBtn.textContent = 'Login';
            navLinks.classList.remove('hidden');
            rememberContainer.classList.remove('opacity-50');
        }
    });
});
</script>
</body>
</html>