{{-- resources/views/catatan/show.blade.php --}}
<x-layout>
    <x-slot:title>
        Detail Catatan
    </x-slot:title>

    <div  class="container mx-auto p-4 sm:p-6 lg:p-8">
        <div class="w-full max-w-2xl mx-auto my-12 bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-lg shadow-md">

            {{-- 1. Judul Kartu --}}
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Detail Catatan</h2>
            {{-- 2. Info Utama: Judul (Kategori) dan Jumlah --}}
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-500 dark:text-gray-400 break-words">
                        {{-- Logika untuk menampilkan Custom Category sebagai judul utama --}}
                        {{ $catatan->category->nama === 'Lainnya...' && !empty($catatan->custom_category) ? $catatan->custom_category : $catatan->category->nama }}
                    </h1>
                </div>
                <div class="text-right flex-shrink-0 ml-4">
                    <p class="text-2xl font-bold {{ $catatan->category->tipe == 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $catatan->category->tipe == 'pemasukan' ? '+' : '-' }} Rp {{ number_format($catatan->jumlah, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            <div class="border-b border-gray-200 dark:border-gray-700 mb-6"></div>

            {{-- 3. Info Detail: Kategori, Tipe, Tanggal, Deskripsi --}}
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                <div>
                    <dt class="font-medium text-gray-500 dark:text-gray-400">Kategori</dt>
                    <dd class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $catatan->category->nama }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500 dark:text-gray-400">Tipe</dt>
                    <dd class="mt-1 font-semibold text-gray-900 dark:text-white">{{ ucfirst($catatan->category->tipe) }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500 dark:text-gray-400">Tanggal</dt>
                    <dd class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $catatan->created_at->format('d F Y, H:i') }}</dd>
                </div>
                @if($catatan->deskripsi)
                    <div class="sm:col-span-2">
                        <dt class="font-medium text-gray-500 dark:text-gray-400">Deskripsi</dt>
                        <dd class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $catatan->deskripsi }}</dd>
                    </div>
                @endif
            </dl>

            {{-- 4. Tombol Aksi --}}
            <div class="border-t border-gray-200 dark:border-gray-700 mt-6 pt-6 flex justify-between items-center">
                <a href="{{ route('catatan.index') }}" class="w-full sm:w-auto text-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 sm:mr-auto">
                    &larr; Kembali ke Dashboard
                </a>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('catatan.edit', $catatan) }}" class="text-white bg-yellow-500 hover:bg-yellow-600 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Edit</a>
                    <form action="{{ route('catatan.destroy', $catatan) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus catatan ini?')" class="text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>

    <div class="container mx-auto p-4 sm:p-6 lg:p-8">
        <div class="w-full max-w-2xl mx-auto my-12 bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-lg shadow-md">
            
            @php
                // Logika untuk menentukan judul utama transaksi
                $judul = ($catatan->category->nama === 'Lainnya...' && !empty($catatan->custom_category)) 
                        ? $catatan->custom_category 
                        : $catatan->category->nama;
            @endphp

            {{-- 1. Judul Utama dan Jumlah --}}
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between border-b border-gray-200 dark:border-gray-700 pb-6 mb-6">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ $judul }}</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Detail Transaksi</p>
                </div>
                <p class="text-2xl sm:text-right font-bold mt-2 sm:mt-0 {{ $catatan->category->tipe == 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                    {{ $catatan->category->tipe == 'pemasukan' ? '+' : '-' }} Rp {{ number_format($catatan->jumlah, 0, ',', '.') }}
                </p>
            </div>

            {{-- 2. Detail Transaksi (Kategori, Tipe, Tanggal, Deskripsi) --}}
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kategori</dt>
                    <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $catatan->category->nama }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipe</dt>
                    <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ ucfirst($catatan->category->tipe) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</dt>
                    <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $catatan->created_at->format('d F Y, H:i') }}</dd>
                </div>
                @if($catatan->deskripsi)
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi</dt>
                    <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $catatan->deskripsi }}</dd>
                </div>
                @endif
            </dl>

            {{-- 3. Tombol Aksi (Kembali, Edit, Hapus) --}}
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row-reverse items-center gap-3">
                <div class="flex items-center space-x-3 w-full sm:w-auto">
                    <a href="{{ route('catatan.edit', $catatan) }}" class="w-full text-center text-white bg-yellow-500 hover:bg-yellow-600 font-medium rounded-lg text-sm px-5 py-2.5">Edit</a>
                    <form action="{{ route('catatan.destroy', $catatan) }}" method="POST" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus catatan ini?')" class="w-full text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm px-5 py-2.5">Hapus</button>
                    </form>
                </div>
                 <a href="{{ route('catatan.index') }}" class="w-full sm:w-auto text-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 sm:mr-auto">
                    &larr; Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>