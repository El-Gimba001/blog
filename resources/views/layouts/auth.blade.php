<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Authentication' }} | MyApp</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-white shadow-2xl rounded-2xl p-8">
        <h1 class="text-3xl font-bold text-center text-indigo-600 mb-6">
            {{ $title ?? 'Welcome' }}
        </h1>

        @yield('content')
    </div>

</body>
</html>