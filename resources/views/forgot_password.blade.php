<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
<div class="bg-white p-8 rounded shadow-md w-96">
    <h1 class="text-2xl font-bold mb-4">Forgot Password</h1>

    @if($errors->any())
        <div class="text-red-500 mb-2">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="/forgot-password" method="POST" class="space-y-4">
        @csrf
        <input type="email" name="email" placeholder="Email" class="w-full border p-2 rounded" required>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Send Reset Link</button>
    </form>

    <p class="mt-4 text-sm">
        <a href="/login" class="text-blue-500">Back to login</a>
    </p>
</div>
</body>
</html>
