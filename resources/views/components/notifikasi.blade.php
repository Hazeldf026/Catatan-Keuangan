@php
    // Logika untuk menentukan tipe dan pesan notifikasi tetap sama
    $type = null;
    $message = null;
    if (session('success')) {
        $type = 'success';
        $message = session('success');
    } elseif (session('error')) {
        $type = 'error';
        $message = session('error');
    } elseif (session('warning')) {
        $type = 'warning';
        $message = session('warning');
    } elseif (session('info')) {
        $type = 'info';
        $message = session('info');
    }

    // PERUBAHAN DESAIN: Menentukan kelas CSS untuk background solid
    $notificationClasses = '';

    if ($type === 'success') {
        $notificationClasses = 'bg-green-500 text-white';
    } elseif ($type === 'error') {
        $notificationClasses = 'bg-red-600 text-white';
    } elseif ($type === 'warning') {
        $notificationClasses = 'bg-yellow-500 text-white';
    } elseif ($type === 'info') {
        $notificationClasses = 'bg-blue-500 text-white';
    }
@endphp

{{-- Hanya proses jika ada pesan notifikasi --}}
@if ($message)
    {{-- 
        PERUBAHAN POSISI:
        - Dulu: fixed top-5 right-5
        - Sekarang: fixed top-5 left-1/2 -translate-x-1/2 (untuk di tengah atas)
    --}}
    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 5000)" {{-- Tetap hilang setelah 5 detik --}}
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform -translate-y-8"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-8"
        class="fixed top-5 left-1/2 -translate-x-1/2 z-50"
        role="alert"
    >
        {{-- DESAIN BARU: Background solid dan ikon yang lebih terintegrasi --}}
        <div class="flex items-center p-4 rounded-md shadow-lg w-full max-w-sm sm:max-w-md {{ $notificationClasses }}">
            <div class="flex-shrink-0">
                {{-- Ikon --}}
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3 font-medium text-sm">
                {{ $message }}
            </div>
            <button @click.prevent="show = false" class="ml-auto -mx-1.5 -my-1.5 p-1.5 rounded-md inline-flex items-center justify-center hover:bg-white/20 focus:outline-none">
                <span class="sr-only">Close</span>
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    </div>
@endif