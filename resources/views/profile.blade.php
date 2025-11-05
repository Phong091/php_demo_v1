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

    <form action="/profile" method="POST" class="space-y-4">
        @csrf
        <input type="text" name="name" placeholder="Name" value="{{ $user->name }}" class="w-full border p-2 rounded">
        <input type="date" name="birthday" placeholder="Birthday" value="{{ $user->birthday }}" class="w-full border p-2 rounded">
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Update</button>
    </form>

    <p class="mt-4 text-sm">
        <a href="/logout" class="text-blue-500">Logout</a>
    </p>
</div>
</body>
</html>
