{{-- resources/views/catatan/show.blade.php --}}
<x-layout>
    <x-slot:title>
        Detail Transaksi
    </x-slot:title>

    <div class="w-full max-w-2xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Detail Transaksi</h2>
            <a href="{{ route('catatan.index') }}" class="text-gray-500 hover:underline">
                &larr; Kembali
            </a>
        </div>
        <dl class="space-y-2">
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</dt>
                <dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ $catatan->created_at->format('d F Y, H:i') }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kategori</dt>
                <dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ $catatan->category->nama }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi</dt>
                <dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ $catatan->deskripsi ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah</dt>
                <dd class="text-lg font-bold {{ $catatan->category->tipe == 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                    {{ $catatan->category->tipe == 'pemasukan' ? '+' : '-' }} Rp {{ number_format($catatan->jumlah, 2, ',', '.') }}
                </dd>
            </div>
        </dl>
    </div>
</x-layout>