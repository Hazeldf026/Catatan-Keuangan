{{-- resources/views/personal/pages/profile/index.blade.php --}}

<x-personal::settings-layout title="Profil Saya">

    {{-- Kartu Profil Utama (tidak berubah) --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mb-6">
       {{-- ... (konten profil: foto, nama, email, tanggal bergabung) ... --}}
       <div class="flex items-center space-x-4">
            <img class="w-16 h-16 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&color=fff&size=128" alt="Foto profil {{ $user->name }}">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Bergabung pada: {{ $user->created_at->isoFormat('D MMMM YYYY') }}</p>
            </div>
        </div>
    </div>

    {{-- Kartu Statistik --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Total Catatan (tidak berubah) --}}
        <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-md text-center">
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Catatan</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ $catatanCount }}</dd>
        </div>
        {{-- Total Rencana (tidak berubah) --}}
        <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-md text-center">
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Rencana</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ $rencanaCount }}</dd>
        </div>

        {{-- [PERBAIKAN] Total Hari Aktif --}}
        <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-md text-center">
            {{-- Ganti judul --}}
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Hari Aktif</dt>
            {{-- Tampilkan angka totalHariAktif --}}
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ $totalHariAktif }}</dd>
            {{-- Tambahkan keterangan "hari" --}}
             <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">hari</p>
        </div>
        {{-- [AKHIR PERBAIKAN] --}}
    </div>

</x-personal::settings-layout>