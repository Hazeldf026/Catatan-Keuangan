{{-- resources/views/personal/components/settings-sidebar.blade.php --}}
<aside class="w-64 bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md flex flex-col" style="height: calc(100vh - 100px);"> {{-- Sesuaikan height jika perlu --}}
    {{-- Alpine.js untuk state submenu --}}
    <div x-data="{ settingsOpen: {{ request()->routeIs('settings.*') ? 'true' : 'false' }} }">
        <nav class="flex-grow">
            <ul class="space-y-2">
                {{-- Link Profil --}}
                <li>
                    <a href="{{ route('profile.index') }}"
                       class="{{ request()->routeIs('profile.index') ? 'bg-gray-100 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }} flex items-center p-2 rounded-lg transition-colors duration-150 group">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-200 {{ request()->routeIs('profile.index') ? 'text-blue-600 dark:text-blue-400' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9.05 14.5h1.9c.406 0 .78.14.997.362A8.949 8.949 0 0 1 10 18Z"/>
                        </svg>
                        <span class="ml-3">Profil</span>
                    </a>
                </li>

                {{-- Tombol Pengaturan (toggle submenu) --}}
                <li>
                    <button @click="settingsOpen = !settingsOpen" type="button"
                            class="{{ request()->routeIs('settings.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }} flex items-center w-full p-2 text-base text-gray-900 dark:text-white rounded-lg group hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-200 {{ request()->routeIs('settings.*') ? 'text-blue-600 dark:text-blue-400' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                           <path d="M18 7.5h-.423l-.452-1.09.3-.3a1.5 1.5 0 0 0 0-2.121L16.01 2.575a1.5 1.5 0 0 0-2.121 0l-.3.3-1.089-.452V2A1.5 1.5 0 0 0 11 .5H9A1.5 1.5 0 0 0 7.5 2v.423l-1.09.452-.3-.3a1.5 1.5 0 0 0-2.121 0L2.576 3.99a1.5 1.5 0 0 0 0 2.121l.3.3L2.423 7.5H2A1.5 1.5 0 0 0 .5 9v2A1.5 1.5 0 0 0 2 12.5h.423l.452 1.09-.3.3a1.5 1.5 0 0 0 0 2.121l1.415 1.415a1.5 1.5 0 0 0 2.121 0l.3-.3 1.09.452V18A1.5 1.5 0 0 0 9 19.5h2a1.5 1.5 0 0 0 1.5-1.5v-.423l1.09-.452.3.3a1.5 1.5 0 0 0 2.121 0l1.415-1.415a1.5 1.5 0 0 0 0-2.121l-.3-.3.452-1.09H18A1.5 1.5 0 0 0 19.5 11V9A1.5 1.5 0 0 0 18 7.5Zm-8 6a3.5 3.5 0 1 1 0-7 3.5 3.5 0 0 1 0 7Z"/>
                        </svg>
                        <span class="flex-1 ml-3 text-left whitespace-nowrap">Pengaturan</span>
                        <svg class="w-3 h-3 transition-transform duration-200" :class="{ 'rotate-180': settingsOpen }" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                        </svg>
                    </button>
                    {{-- Submenu Pengaturan --}}
                    <ul x-show="settingsOpen" x-transition class="py-2 space-y-2 pl-4">
                        {{-- Link Akun --}}
                        <li>
                            <a href="{{ route('settings.account.index') }}"
                               class="{{ request()->routeIs('settings.account.*') ? 'text-blue-600 dark:text-blue-400 font-medium' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }} flex items-center p-2 rounded-lg transition-colors duration-150 group">
                                <span class="ml-3">Akun</span>
                            </a>
                        </li>
                        {{-- Link Tampilan --}}
                        <li>
                            <a href="{{ route('settings.appearance.index') }}"
                               class="{{ request()->routeIs('settings.appearance.*') ? 'text-blue-600 dark:text-blue-400 font-medium' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }} flex items-center p-2 rounded-lg transition-colors duration-150 group">
                                <span class="ml-3">Tampilan</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>

    {{-- Tombol Logout di Bawah --}}
    <div class="mt-auto pt-4 border-t border-gray-200 dark:border-gray-700">
        <button @click="$dispatch('open-logout-modal')" {{-- Trigger modal logout di layout utama --}}
                class="flex items-center p-2 text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 w-full group transition-colors duration-150">
            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 16">
                 <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3"/>
            </svg>
            <span class="ml-3">Logout</span>
        </button>
    </div>
</aside>