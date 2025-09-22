{{-- resources/views/catatan/create.blade.php --}}
<x-layout>
    <x-slot:title>Tambah Catatan Baru</x-slot:title>

    <div class="w-full max-w-2xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Tambah Catatan Baru</h2>
        @include('pages.catatan._form', ['action' => route('catatan.store'), 'method' => 'POST'])
    </div>
</x-layout>