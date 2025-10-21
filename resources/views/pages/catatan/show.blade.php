{{-- resources/views/pages/catatan/show.blade.php --}}
<x-layout>
    <x-slot:title>
        Detail Catatan
    </x-slot:title>

    {{-- 1. Tambahkan x-data di sini --}}
    <div x-data="{ deleteModalOpen: false, deleteAction: '' }" class="container mx-auto p-4 sm:p-6 lg:p-8">
        <div class="w-full max-w-2xl mx-auto my-12 bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-lg shadow-md">

            {{-- ... (Bagian Judul Kartu, Info Utama, Garis Pembatas tidak berubah) ... --}}
            {{-- 1. Judul Kartu --}}
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Detail Catatan</h2>
            {{-- 2. Info Utama: Judul (Kategori) dan Jumlah --}}
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-500 dark:text-gray-400 break-words">
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

            {{-- 3. Info Detail (tidak berubah) --}}
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

                @if ($catatan->category->tipe == 'pemasukan')
                    @if ($catatan->alokasi === 'rencana' && $catatan->rencana)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dialokasikan ke Rencana</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $catatan->rencana->nama }}</dd>
                        </div>
                    @elseif ($catatan->alokasi === 'media' && $catatan->media)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Masuk ke Media</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ ucfirst($catatan->media) }}</dd>
                        </div>
                    @endif
                @else
                    @if ($catatan->media)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Sumber Dana (Media)</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ ucfirst($catatan->media) }}</dd>
                        </div>
                    @endif
                @endif

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
                    
                    {{-- 2. Ganti <form> dengan <button> --}}
                    <button type="button" 
                            @click="deleteAction = '{{ route('catatan.destroy', $catatan) }}'; deleteModalOpen = true" 
                            class="text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Hapus
                    </button>
                </div>
            </div>
        </div>

        {{-- 3. Sertakan komponen modal --}}
        <x-delete-confirmation>
            Apakah Anda yakin ingin menghapus catatan ini? Tindakan ini tidak dapat diurungkan.
        </x-delete-confirmation>
    </div>
</x-layout>