{{-- resources/views/personal/components/settings-layout.blade.php --}}
@props(['title'])

{{-- Gunakan layout personal utama --}}
<x-personal::layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="container mx-auto p-4 sm:p-6 lg:p-8">
        <div class="flex flex-col md:flex-row gap-6">
            {{-- Kolom Sidebar --}}
            <div class="w-full md:w-64 flex-shrink-0">
                <x-personal::settings-sidebar />
            </div>

            {{-- Kolom Konten Utama --}}
            <div class="flex-grow">
                {{ $slot }} {{-- Konten halaman (profil/akun/tampilan) akan masuk di sini --}}
            </div>
        </div>
    </div>

</x-personal::layout>