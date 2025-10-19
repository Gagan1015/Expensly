<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Expensly - Smart Expense Management')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-foreground transition-colors duration-300">
    <div class="min-h-screen flex flex-col">
        @include('landing.components.navigation')
        <main class="flex-grow">
            @yield('content')
        </main>
        @include('landing.components.footer')
    </div>
</body>
</html>
