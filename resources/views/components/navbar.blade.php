{{-- Navbar Lapis Pertama: Logo, Nama Web, dan Profil --}}
<nav class="bg-white border-b border-gray-200 dark:bg-slate-800 dark:border-slate-700">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            {{-- Sisi Kiri: Logo dan Nama Web --}}
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="flex-shrink-0 flex items-center space-x-2">
                    <img class="block w-auto h-8" src="{{ asset('images/Logo.png') }}" alt="Credix Logo">
                    <span class="font-bold text-xl text-gray-800 dark:text-white">Credix</span>
                </a>
            </div>

            {{-- Sisi Kanan: Profil dan Dropdown Interaktif --}}
            <div class="flex items-center">
                @auth
                    <div x-data="{ open: false }" class="relative ml-3">
                        
                        {{-- KONTENER PROFIL INTERAKTIF --}}
                        <div @click="open = !open" class="cursor-pointer">
                            
                            {{-- State 1: Tampilan Awal (Expanded) --}}
                            <div x-show="!open" 
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100"
                                class="flex items-center p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700">
                                <div class="flex items-center space-x-3">
                                    {{-- Teks Nama dan Email (di kiri) --}}
                                    <div class="text-right">
                                        <p class="text-sm font-semibold text-gray-800 dark:text-white truncate">
                                            {{ Auth::user()->name }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                            {{ Auth::user()->email }}
                                        </p>
                                    </div>
                                    {{-- Foto Profil (di kanan) --}}
                                    <div class="flex-shrink-0">
                                        <div class="flex items-center justify-center w-8 h-8 text-sm font-semibold text-white bg-blue-500 rounded-full">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- State 2: Tampilan Saat Diklik (Collapsed) --}}
                            <div x-show="open" x-cloak
                                class="flex text-sm bg-gray-200 rounded-full ring-2 ring-offset-2 ring-offset-gray-200 dark:ring-offset-slate-700 ring-blue-500">
                                <span class="sr-only">Buka menu pengguna</span>
                                <div class="flex items-center justify-center w-8 h-8 text-sm font-semibold text-white bg-blue-500 rounded-full">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            </div>
                        </div>

                        {{-- Menu Dropdown --}}
                        <div x-show="open" @click.outside="open = false" x-cloak
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 w-56 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-slate-700 dark:ring-slate-600"
                            role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                            
                            {{-- Header Profil di dalam Dropdown --}}
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-slate-600">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="flex items-center justify-center w-10 h-10 text-base font-semibold text-white bg-blue-500 rounded-full">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800 dark:text-white">
                                            {{ Auth::user()->name }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ Auth::user()->email }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Bagian Menu Link --}}
                            <div class="py-1" role="none">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-slate-600" role="menuitem" tabindex="-1">Profil</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-slate-600" role="menuitem" tabindex="-1">Pengaturan</a>
                                <button type="button" 
                                    @click="$dispatch('open-logout-modal')" 
                                    class="flex items-center gap-1 w-full px-4 py-2 text-sm text-left text-red-500 hover:bg-red-100/60 dark:hover:bg-red-600/20" 
                                    role="menuitem" tabindex="-1">
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15 3H6C4.89543 3 4 3.89543 4 5V19C4 20.1046 4.89543 21 6 21H15C16.1046 21 17 20.1046 17 19V17M11 12H22M22 12L19 9M22 12L19 15" stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    Keluar
                                </button>
                            </div>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- Navbar Lapis Kedua: Menu Navigasi --}}
<nav class="bg-white shadow-sm dark:bg-slate-800">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex justify-center h-12 space-x-4">
            <a href="{{ route('catatan.index') }}" class="{{ request()->routeIs('catatan.*') ? 'border-blue-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                Dashboard
            </a>
            <a href="{{ route('analysis.show') }}" class="{{ request()->routeIs('analysis.show') ? 'border-blue-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                Analisis
            </a>
            <a href="#" class="{{ request()->routeIs('rencana.*') ? 'border-blue-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                Rencana
            </a>
            <a href="#" class="{{ request()->routeIs('grup.*') ? 'border-blue-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                Grup
            </a>
        </div>
    </div>
</nav>