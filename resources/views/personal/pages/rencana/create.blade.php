<x-personal::layout>
    <x-slot:title>Tambah Rencana Baru</x-slot:title>

    <div class="container mx-auto p-4 sm:p-6 lg:p-8">
        <div class="w-full max-w-2xl mx-auto my-12 bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Buat Rencana Keuangan Baru</h2>
            @include('personal::rencana._form', [
                'action' => route('rencana.store'),
                'method' => 'POST',
                'rencana' => new \App\Models\Rencana(),
            ])
        </div>
    </div>
</x-personal::layout>