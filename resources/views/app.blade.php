<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite('resources/css/app.css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.15.0/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900">

    @hasSection('start')
        <div>
            @yield('start')
        </div>
        @stack('script')
    @endif
    
    @hasSection('content')
        <div class="container mx-auto p-4 sm:p-6 lg:p-8 min-h-screen flex items-center justify-center">
            @yield('content')
        </div>
    @endif



    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>
</html>