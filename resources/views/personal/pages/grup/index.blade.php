<x-layout>
    <x-slot:title>
        Grup Saya
    </x-slot:title>

    {{-- Root AplineJS untuk mengontrol modal --}}
    <div x-data="{ createModal: false, joinModal: false }" class="container mx-auto p-4 sm:p-6 lg:p-8">

        {{-- Header Halaman dan Tombol Aksi --}}
        <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-4">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Grup Saya</h1>
            <div class="flex items-center gap-2">
                <button @click="joinModal = true"
                    class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.5 21h-3A12.318 12.318 0 014 19.235z" /></svg>
                    Gabung Grup
                </button>
                <button @click="createModal = true"
                    class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Buat Grup Baru
                </button>
            </div>
        </div>

        {{-- Daftar Grup yang Diikuti --}}
        @if ($grups->isEmpty())
            <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg shadow-md">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">Kamu belum bergabung dengan grup</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Buat grup baru atau gabung dengan grup yang sudah ada.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($grups as $grup)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden flex flex-col">
                        <div class="p-6 flex-grow">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $grup->nama }}</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 h-16 overflow-y-auto">
                                {{ $grup->deskripsi ?? 'Tidak ada deskripsi.' }}
                            </p>
                            <span class="inline-block bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-xs font-mono px-2 py-1 rounded">
                                KODE: {{ $grup->grup_code }}
                            </span>
                        </div>
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-100 dark:border-gray-600">
                            <div class="flex items-center justify-between">
                                {{-- Stack Avatar Anggota --}}
                                <div class="flex -space-x-2 overflow-hidden">
                                    @foreach ($grup->users->take(5) as $user)
                                        <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white dark:ring-gray-800"
                                            src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random"
                                            alt="{{ $user->name }}">
                                    @endforeach
                                    @if ($grup->users->count() > 5)
                                        <span class="flex items-center justify-center h-8 w-8 rounded-full bg-gray-200 text-xs font-medium text-gray-700 ring-2 ring-white dark:bg-gray-600 dark:text-gray-200 dark:ring-gray-800">
                                            +{{ $grup->users->count() - 5 }}
                                        </span>
                                    @endif
                                </div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $grup->users->count() }} Anggota</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>


    {{-- ====================================================================== --}}
    {{-- Modal "Buat Grup Baru" --}}
    {{-- ====================================================================== --}}
    <div x-show="createModal" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
        @keydown.escape.window="createModal = false">
        
        <div class_form="relative bg-white dark:bg-gray-800 w-full max-w-lg mx-4 p-6 rounded-lg shadow-xl"
            @click.outside="createModal = false">
            
            <button @click="createModal = false" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">Buat Grup Baru</h3>

            <form action="{{ route('grup.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Grup</label>
                        <input type="text" name="nama" id="nama" required maxlength="255"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Misal: Keluarga Cemara">
                    </div>
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi (Opsional)</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Misal: Grup untuk mencatat pengeluaran keluarga..."></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" @click="createModal = false"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600">
                        Buat Grup
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ====================================================================== --}}
    {{-- Modal "Gabung Grup" --}}
    {{-- ====================================================================== --}}
    <div x-show="joinModal" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
        @keydown.escape.window="joinModal = false">
        
        <div class_form="relative bg-white dark:bg-gray-800 w-full max-w-lg mx-4 p-6 rounded-lg shadow-xl"
            @click.outside="joinModal = false">
            
            <button @click="joinModal = false" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">Gabung ke Grup</h3>

            <form action="{{ route('grup.join') }}" method="POST">
                @csrf
                <div>
                    <label for="grup_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kode Undangan</label>
                    <input type="text" name="grup_code" id="grup_code" required
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="Masukkan 8 digit kode grup">
                </div>
                
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" @click="joinModal = false"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600">
                        Gabung
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-layout>