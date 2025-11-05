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

    @if($errors->any())
        <div class="text-red-500 mb-2">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="/login" method="POST" class="space-y-4">
        @csrf
        <input type="email" name="email" placeholder="Email" required class="w-full border p-2 rounded">
        <input type="password" name="password" placeholder="Password" required class="w-full border p-2 rounded">
        <div class="flex items-center">
            <input type="checkbox" name="remember" id="remember" class="mr-2">
            <label for="remember" class="text-sm">Remember me</label>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Login</button>
    </form>

    <div class="mt-4 flex justify-between text-sm">
        <a href="/forgot-password" class="text-blue-500">Forgot Password?</a>
        <a href="/register" class="text-blue-500">Register</a>
    </div>
</div>
</body>
</html>
