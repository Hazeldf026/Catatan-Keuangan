{{-- Menggunakan layout personal --}}
<x-personal::layout>
    <x-slot:title>
        Grup Saya
    </x-slot:title>

    {{-- Root AplineJS --}}
    <div x-data="{
            createModal: false,
            joinModal: false,
            confirmJoinModal: false,
            foundGrup: null,
            joinLoading: false,
            joinError: null,
            grupCodeInput: ''
        }"
         class="container mx-auto p-4 sm:p-6 lg:p-8">

        {{-- Header Halaman dan Tombol Aksi --}}
        <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-4">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Grup Saya</h1>
            <div class="flex items-center gap-2">
                <button @click="joinModal = true; joinError = null; grupCodeInput = '';"
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
                             <a href="{{ route('group.catatan.index', ['grup' => $grup->id]) }}"
                                class="mt-4 inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-800">
                                Masuk Room
                                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                </svg>
                            </a>
                        </div>
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-100 dark:border-gray-600">
                           <div class="flex items-center justify-between">
                                <div class="flex -space-x-2 overflow-hidden">
                                    @foreach ($grup->users->take(5) as $anggota)
                                        <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white dark:ring-gray-800"
                                            src="https://ui-avatars.com/api/?name={{ urlencode($anggota->name) }}&background=random"
                                            alt="{{ $anggota->name }}">
                                    @endforeach
                                    @if ($grup->users_count > 5)
                                        <span class="flex items-center justify-center h-8 w-8 rounded-full bg-gray-200 text-xs font-medium text-gray-700 ring-2 ring-white dark:bg-gray-600 dark:text-gray-200 dark:ring-gray-800">
                                            +{{ $grup->users_count - 5 }}
                                        </span>
                                    @endif
                                </div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $grup->users_count }} Anggota</span>
                           </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif


        {{-- ====================================================================== --}}
        {{-- Modal "Buat Grup Baru" --}}
        {{-- ====================================================================== --}}
        <div x-show="createModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
            @keydown.escape.window="createModal = false">
            
            <div class="relative bg-white dark:bg-gray-800 w-full max-w-lg mx-4 p-6 rounded-lg shadow-xl"
                @click.outside="createModal = false">
                <button @click="createModal = false" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">Buat Grup Baru</h3>
                <form action="{{ route('grup.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="nama_create" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Grup</label>
                            <input type="text" name="nama" id="nama_create" required maxlength="255"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Misal: Keluarga Cemara">
                        </div>
                        <div>
                            <label for="deskripsi_create" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi (Opsional)</label>
                            <textarea name="deskripsi" id="deskripsi_create" rows="3"
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
        {{-- Modal "Gabung Grup" (Masukkan Kode) --}}
        {{-- ====================================================================== --}}
        <div x-show="joinModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
            @keydown.escape.window="joinModal = false">
            
            <div class="relative bg-white dark:bg-gray-800 w-full max-w-lg mx-4 p-6 rounded-lg shadow-xl"
                @click.outside="joinModal = false">
                
                <button @click="joinModal = false" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">Gabung ke Grup</h3>

                <form @submit.prevent="
                    joinLoading = true;
                    joinError = null;
                    fetch('{{ route('grup.find') }}?grup_code=' + grupCodeInput.toUpperCase(), {
                        method: 'GET',
                        headers: { 'Accept': 'application/json' }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => { throw new Error(err.error || 'Grup tidak ditemukan'); });
                        }
                        return response.json();
                    })
                    .then(data => {
                        foundGrup = data;
                        joinModal = false;
                        confirmJoinModal = true;
                    })
                    .catch(error => {
                        joinError = error.message;
                    })
                    .finally(() => {
                        joinLoading = false;
                    });
                ">
                    <div>
                        <label for="grup_code_input" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kode Undangan</label>
                        <input type="text" name="grup_code" id="grup_code_input" x-model="grupCodeInput" required maxlength="8" pattern="[a-zA-Z0-9]{8}"
                            x-on:input="grupCodeInput = grupCodeInput.toUpperCase()"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white uppercase"
                            placeholder="Masukkan 8 digit kode grup">
                        <p x-show="joinError" x-text="joinError" class="mt-2 text-sm text-red-600 dark:text-red-400"></p>
                    </div>
                    
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="joinModal = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600">
                            Batal
                        </button>
                        <button type="submit" :disabled="joinLoading || grupCodeInput.length !== 8"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600 disabled:opacity-50">
                            <svg x-show="joinLoading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="joinLoading ? 'Mencari...' : 'Cari Grup'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ====================================================================== --}}
        {{-- Modal Konfirmasi Gabung Grup --}}
        {{-- ====================================================================== --}}
        <div x-show="confirmJoinModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
            @keydown.escape.window="confirmJoinModal = false; foundGrup = null;">
            
            <div class="relative bg-white dark:bg-gray-800 w-full max-w-lg mx-4 p-6 rounded-lg shadow-xl"
                @click.outside="confirmJoinModal = false; foundGrup = null;">
                
                <button @click="confirmJoinModal = false; foundGrup = null;" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">Konfirmasi Gabung Grup</h3>

                <template x-if="foundGrup">
                    <div class="mb-6 border dark:border-gray-700 rounded-lg p-4">
                        <h4 class="text-lg font-bold text-gray-900 dark:text-white" x-text="foundGrup.nama"></h4>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400" x-text="foundGrup.deskripsi || 'Tidak ada deskripsi.'"></p>
                        <div class="mt-3 flex justify-between items-center text-sm text-gray-500 dark:text-gray-400">
                            <span class="font-mono bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded" x-text="'KODE: ' + foundGrup.grup_code"></span>
                            <span x-text="foundGrup.users_count + ' Anggota'"></span>
                        </div>
                         <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Dibuat oleh: <span x-text="foundGrup.owner_name"></span></p>
                    </div>
                </template>
                
                <form action="{{ route('grup.join') }}" method="POST">
                    @csrf
                    <input type="hidden" name="grup_id" :value="foundGrup ? foundGrup.id : ''">

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="confirmJoinModal = false; foundGrup = null;"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600">
                            Batal
                        </button>
                        <button type="submit" :disabled="!foundGrup"
                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:bg-green-500 dark:hover:bg-green-600 disabled:opacity-50">
                            Masuk Grup Ini
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div> {{-- <<<===== PINDAHKAN PENUTUP DIV x-data KE SINI =====>>> --}}

</x-personal::layout>