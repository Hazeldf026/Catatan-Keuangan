{{-- Modal Edit Rencana --}}
<div x-show="editModalOpen" 
    x-cloak 
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    {{-- Reset data saat modal ditutup dengan Escape --}}
    @keydown.escape.window="editModalOpen = false; selectedRencana = null;">
    
    {{-- Background overlay --}}
    <div x-show="editModalOpen" 
        x-transition:enter="ease-out duration-300" 
        x-transition:enter-start="opacity-0" 
        x-transition:enter-end="opacity-100" 
        x-transition:leave="ease-in duration-200" 
        x-transition:leave-start="opacity-100" 
        x-transition:leave-end="opacity-0" 
        class="fixed inset-0 bg-black/30 backdrop-blur-sm" 
        {{-- Reset data saat klik di luar modal --}}
        @click="editModalOpen = false; selectedRencana = null;">
    </div>

    {{-- Modal panel --}}
    <div x-show="editModalOpen"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        @click.stop
        {{-- Ukuran disamakan dengan create modal (max-w-xl) --}}
        class="relative bg-white rounded-lg shadow-xl w-full max-w-xl z-10"
        {{-- ID ini SANGAT PENTING untuk fungsi fillRencanaForm di index.blade.php --}}
        id="edit-rencana-modal-content">
        
        {{-- Header Modal (Sama seperti create-modal) --}}
        <div class="flex justify-between items-center px-6 py-4 border-b rounded-t">
            <h3 class="text-lg font-medium text-gray-900" id="modal-title-edit">
                Edit Rencana Grup
            </h3>
            <button type="button" @click="editModalOpen = false; selectedRencana = null;" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
        </div>

        {{-- FORM EDIT --}}
        {{-- 
        PENJELASAN: 
        - Kita menggunakan x-show="selectedRencana" agar form tidak error sebelum datanya siap.
        - :action="..." adalah sintaks Alpine untuk binding action form secara dinamis.
        --}}
        <form x-show="selectedRencana" :action="`/grup/{{ $grup->id }}/rencana/${selectedRencana.id}`" method="POST" id="edit-rencana-form">
            @csrf
            @method('PUT')
            
            {{-- Konten Modal (Form) --}}
            <div class="p-6">
                @include('group::rencana._form', [
                'mode' => 'edit', 
                'rencana' => new \App\Models\GrupRencana()
            ])
            </div>

            {{-- Footer Modal (Tombol Aksi) (Sama seperti create-modal) --}}
            <div class="px-6 py-4 bg-gray-50 border-t rounded-b flex items-center justify-end space-x-3">
                <button @click="editModalOpen = false; selectedRencana = null;" 
                        type="button" 
                        class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:w-auto sm:text-sm">
                    Batal
                </button>
                <button type="submit" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:w-auto sm:text-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>