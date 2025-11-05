<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
<div class="bg-white p-8 rounded shadow-md w-96">
    <h1 class="text-2xl font-bold mb-4">Register</h1>

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

    <form action="/register" method="POST" class="space-y-4">
        @csrf
        <input type="email" name="email" placeholder="Email" required class="w-full border p-2 rounded">
        <input type="password" name="password" placeholder="Password" required class="w-full border p-2 rounded">
        <input type="password" name="password_confirmation" placeholder="Confirm Password" required class="w-full border p-2 rounded">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Register</button>
    </form>

    <p class="mt-4 text-sm">
        Already have account? <a href="/login" class="text-blue-500">Login</a>
    </p>
</div>
</body>
</html>
