<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
<div class="bg-white p-8 rounded shadow-md w-96">
    <h1 class="text-2xl font-bold mb-4">Register</h1>

    <div id="success-message" class="text-green-500 mb-2 hidden"></div>
    <div id="error-message" class="text-red-500 mb-2 hidden"></div>

    <form id="register-form" class="space-y-4">
        <div>
            <input 
                type="email" 
                name="email" 
                id="email"
                placeholder="Email" 
                required 
                class="w-full border p-2 rounded"
            >
        </div>

        <div>
            <input 
                type="password" 
                name="password" 
                id="password"
                placeholder="Password" 
                required 
                minlength="8"
                class="w-full border p-2 rounded"
            >
        </div>

        <div>
            <input 
                type="password" 
                name="password_confirmation" 
                id="password_confirmation"
                placeholder="Confirm Password" 
                required 
                minlength="8"
                class="w-full border p-2 rounded"
            >
        </div>

        <button 
            type="submit" 
            id="submit-btn"
            class="bg-blue-500 text-white px-4 py-2 rounded w-full hover:bg-blue-700 hover:shadow-lg transform hover:scale-[1.02] transition-all duration-200 disabled:bg-gray-400 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none"
        >
            Register
        </button>
    </form>

    <p class="mt-4 text-sm" id="login-link">
        Already have account? <a href="/login" class="text-blue-500 hover:text-blue-700 transition-colors">Login</a>
    </p>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('register-form');
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');
    const submitBtn = document.getElementById('submit-btn');
    const loginLink = document.getElementById('login-link');
    
    // Regex validate password
    const passwordRegex = /^(?=.*[A-Z])(?=.*[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]).{8,}$/;
    
    // AJAX submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        document.getElementById('success-message').classList.add('hidden');
        document.getElementById('error-message').classList.add('hidden');
        
        // Validate password bằng regex
        if (!passwordRegex.test(password.value)) {
            const errorMsg = document.getElementById('error-message');
            errorMsg.textContent = 'Password phải có ít nhất 8 ký tự, bao gồm ít nhất 1 chữ hoa và 1 ký tự đặc biệt';
            errorMsg.classList.remove('hidden');
            return;
        }
        
        // Check password confirmation
        if (password.value !== passwordConfirmation.value) {
            const errorMsg = document.getElementById('error-message');
            errorMsg.textContent = 'Password xác nhận không khớp';
            errorMsg.classList.remove('hidden');
            return;
        }
        
        // Disable button và ẩn link
        submitBtn.disabled = true;
        submitBtn.textContent = 'Đang xử lý...';
        loginLink.classList.add('hidden');
        
        try {
            const formData = new FormData(form);
            const response = await fetch('/register', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    email: formData.get('email'),
                    password: formData.get('password'),
                    password_confirmation: formData.get('password_confirmation')
                })
            });
            
            const data = await response.json();
            
            if (response.ok) {
                const successMsg = document.getElementById('success-message');
                successMsg.textContent = data.message || 'Đăng ký thành công!';
                successMsg.classList.remove('hidden');
                form.reset();
                
                setTimeout(() => {
                    window.location.href = data.redirect || '/login';
                }, 2000);
            } else {
                const errorMsg = document.getElementById('error-message');
                if (data.errors) {
                    let errorText = '';
                    Object.values(data.errors).forEach(errors => {
                        errors.forEach(error => {
                            errorText += error + '<br>';
                        });
                    });
                    errorMsg.innerHTML = errorText;
                } else {
                    errorMsg.textContent = data.message || 'Có lỗi xảy ra';
                }
                errorMsg.classList.remove('hidden');
                
                // Enable lại button và hiện link khi có lỗi
                submitBtn.disabled = false;
                submitBtn.textContent = 'Register';
                loginLink.classList.remove('hidden');
            }
        } catch (error) {
            const errorMsg = document.getElementById('error-message');
            errorMsg.textContent = 'Có lỗi xảy ra khi kết nối server';
            errorMsg.classList.remove('hidden');
            
            // Enable lại button và hiện link khi có lỗi
            submitBtn.disabled = false;
            submitBtn.textContent = 'Register';
            loginLink.classList.remove('hidden');
        }
    });
});
</script>
</body>
</html>