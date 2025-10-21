{{-- resources/views/catatan/edit.blade.php --}}
<x-personal::layout>
    <x-slot:title>Edit Catatan</x-slot:title>

    {{-- MODIFIKASI: Tambahkan div container pembungkus di sini --}}
    <div class="container mx-auto p-4 sm:p-6 lg:p-8">
        <div class="w-full max-w-2xl mx-auto my-10 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Edit Catatan</h2>
            @include('personal::catatan._form', [
                'catatan' => $catatan, 
                'action' => route('catatan.update', $catatan), 
                'method' => 'PUT'
            ])
        </div>
    </div>
</x-personal::layout>