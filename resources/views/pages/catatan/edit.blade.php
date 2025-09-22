{{-- resources/views/catatan/edit.blade.php --}}
<x-layout>
    <x-slot:title>Edit Catatan</x-slot:title>

    <div class="w-full max-w-2xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Edit Catatan</h2>
        @include('pages.catatan._form', ['catatan' => $catatan, 'action' => route('catatan.update', $catatan), 'method' => 'PUT'])
    </div>
</x-layout>