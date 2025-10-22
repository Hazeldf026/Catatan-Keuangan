{{-- Navbar Lapis Pertama - FIXED LIGHT MODE --}}
<nav class="bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700 shadow-sm">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            {{-- Logo --}}
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="flex-shrink-0 flex items-center space-x-2">
                    <img class="block w-auto h-8" src="{{ asset('images/Logo.png') }}" alt="Credix Logo">
                    <span class="font-bold text-xl text-gray-700 dark:text-white">Credix</span>
                </a>
            </div>

            {{-- Profile Dropdown --}}
            <div class="flex items-center">
                @auth
                    <div x-data="{ open: false }" class="relative ml-3">
                        <div @click="open = !open" class="cursor-pointer">
                            {{-- State Expanded --}}
                            <div x-show="!open" 
                                class="flex items-center p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <div class="text-right">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                            {{ Auth::user()->name }}
                                        </p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 truncate">
                                            {{ Auth::user()->email }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="flex items-center justify-center w-8 h-8 text-sm font-semibold text-white bg-blue-600 dark:bg-blue-500 rounded-full">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- State Collapsed --}}
                            <div x-show="open" x-cloak
                                class="flex text-sm bg-gray-100 dark:bg-gray-700 rounded-full ring-2 ring-offset-2 ring-offset-white dark:ring-offset-slate-800 ring-blue-500">
                                <div class="flex items-center justify-center w-8 h-8 text-sm font-semibold text-white bg-blue-600 dark:bg-blue-500 rounded-full">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            </div>
                        </div>

                        {{-- Dropdown Menu --}}
                        <div x-show="open" @click.outside="open = false" x-cloak
                            class="absolute right-0 w-56 mt-2 origin-top-right bg-white dark:bg-slate-700 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 dark:ring-slate-600">
                            
                            {{-- Header --}}
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-slate-600">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="flex items-center justify-center w-10 h-10 text-base font-semibold text-white bg-blue-600 dark:bg-blue-500 rounded-full">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ Auth::user()->name }}
                                        </p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                            {{ Auth::user()->email }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Menu Links --}}
                            <div class="py-1">
                                <a href="{{ route('profile.index') }}" 
                                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-600">
                                    Profil
                                </a>
                                <a href="{{ route('settings.account.index') }}" 
                                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-600">
                                    Pengaturan
                                </a>
                                <button type="button" @click="$dispatch('open-logout-modal')" 
                                    class="flex items-center gap-1 w-full px-4 py-2 text-sm text-left text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-600/20">
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none">
                                        <path d="M15 3H6C4.89543 3 4 3.89543 4 5V19C4 20.1046 4.89543 21 6 21H15C16.1046 21 17 20.1046 17 19V17M11 12H22M22 12L19 9M22 12L19 15" 
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
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

{{-- Navbar Lapis Kedua - Navigation --}}
<nav class="bg-white dark:bg-slate-800 shadow-sm border-b border-gray-200 dark:border-slate-700">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex justify-center h-12 space-x-4">
            <a href="{{ route('catatan.index') }}" 
            class="{{ request()->routeIs('catatan.*') 
                ? 'border-blue-600 text-blue-700 dark:border-blue-500 dark:text-blue-400' 
                : 'border-transparent text-gray-600 hover:border-gray-300 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200' }} 
                inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                Dashboard
            </a>
            <a href="{{ route('analysis.show') }}" 
            class="{{ request()->routeIs('analysis.show') 
                ? 'border-blue-600 text-blue-700 dark:border-blue-500 dark:text-blue-400' 
                : 'border-transparent text-gray-600 hover:border-gray-300 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200' }} 
                inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                Analisis
            </a>
            <a href="{{ route('rencana.index') }}" 
            class="{{ request()->routeIs('rencana.*') 
                ? 'border-blue-600 text-blue-700 dark:border-blue-500 dark:text-blue-400' 
                : 'border-transparent text-gray-600 hover:border-gray-300 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200' }} 
                inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                Rencana
            </a>
            <a href="{{ route('grup.index') }}" 
            class="{{ request()->routeIs('grup.*') 
                ? 'border-blue-600 text-blue-700 dark:border-blue-500 dark:text-blue-400' 
                : 'border-transparent text-gray-600 hover:border-gray-300 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200' }} 
                inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                Grup
            </a>
        </div>
    </div>
</nav>