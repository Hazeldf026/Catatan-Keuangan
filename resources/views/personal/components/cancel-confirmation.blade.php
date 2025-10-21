@props(['title' => 'Konfirmasi Pembatalan'])

{{-- 
    Komponen ini memerlukan parent dengan x-data="{ cancelModalOpen: false, cancelAction: '' }"
--}}
<div x-show="cancelModalOpen" x-cloak 
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    aria-labelledby="modal-title-cancel" role="dialog" aria-modal="true"
    style="display: none;" 
    >
    
    {{-- Latar Belakang Overlay --}}
    <div x-show="cancelModalOpen" 
        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-gray-900/60" 
        @click="cancelModalOpen = false"></div>

    {{-- Panel Modal --}}
    <div x-show="cancelModalOpen" 
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative w-full max-w-md p-6 overflow-hidden text-left align-middle bg-white rounded-lg shadow-xl dark:bg-gray-800">
        
        <h3 class="text-lg font-bold leading-6 text-gray-900 dark:text-white" id="modal-title-cancel">
            {{ $title }}
        </h3>
        <div class="mt-2">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ $slot }} 
            </p>
        </div>

        {{-- Tombol Aksi --}}
        <div class="mt-6 flex justify-end space-x-3">
            <button type="button" @click="cancelModalOpen = false"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:bg-gray-600">
                Tidak
            </button>
            {{-- Form untuk mengirimkan request POST ke action cancel --}}
            <form :action="cancelAction" method="POST" class="inline">
                @csrf 
                <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-yellow-600 border border-transparent rounded-lg hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                    Ya, Batalkan Rencana
                </button>
            </form>
        </div>
    </div>
</div>