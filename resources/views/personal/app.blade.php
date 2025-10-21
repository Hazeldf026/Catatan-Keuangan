<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Credix' }}</title>
    @vite('resources/css/app.css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.15.0/dist/cdn.min.js"></script>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <style>
    [x-cloak] { display: none !important; }
    </style>
    <script>
        (function() {
            const theme = localStorage.getItem('theme');
            const html = document.documentElement;
            
            if (theme === 'dark') {
                html.classList.add('dark');
            } else if (theme === 'light') {
                html.classList.remove('dark');
            } else {
                // Default sistem
                if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    html.classList.add('dark');
                } else {
                    html.classList.remove('dark');
                }
            }
        })();
    </script>
</head>
<body 
    x-data="{ logoutModalOpen: false }" 
    @open-logout-modal.window="logoutModalOpen = true"
    class="bg-gray-100 dark:bg-gray-900">

    @hasSection('start')
        <div>
            <x-notifikasi></x-notifikasi>
            @yield('start')
        </div>
        @stack('script')
    @endif
    
    @hasSection('content')
        <div>
            @yield('content')
        </div>
        
    @endif

    <x-logout-popup />

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

    
</body>
</html>