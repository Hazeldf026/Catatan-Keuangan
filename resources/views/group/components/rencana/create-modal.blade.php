{{-- Modal Tambah Rencana --}}
<div x-show="createModalOpen" 
    x-cloak 
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    @keydown.escape.window="createModalOpen = false">
    
    {{-- Background overlay --}}
    {{-- PENJELASAN: Kode di bawah ini sudah benar untuk efek "mengelap" --}}
    {{-- bg-black/30 memberi latar hitam transparan --}}
    {{-- backdrop-blur-sm memberi efek blur pada konten di belakangnya --}}
    <div x-show="createModalOpen" 
        x-transition:enter="ease-out duration-300" 
        x-transition:enter-start="opacity-0" 
        x-transition:enter-end="opacity-100" 
        x-transition:leave="ease-in duration-200" 
        x-transition:leave-start="opacity-100" 
        x-transition:leave-end="opacity-0" 
        class="fixed inset-0 bg-black/30 backdrop-blur-sm" 
        @click="createModalOpen = false">
    </div>

    {{-- Modal panel --}}
    <div x-show="createModalOpen"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        @click.stop
        {{-- PERUBAHAN: Ganti max-w-lg menjadi max-w-xl agar form tidak terlalu sempit --}}
        class="relative bg-white rounded-lg shadow-xl w-full max-w-xl z-10">
        
        {{-- Header Modal --}}
        <div class="flex justify-between items-center px-6 py-4 border-b rounded-t">
            <h3 class="text-lg font-medium text-gray-900" id="modal-title-create">
                Buat Rencana Grup Baru
            </h3>
            <button type="button" @click="createModalOpen = false" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
        </div>

        {{-- FORM CREATE --}}
        {{-- PENJELASAN: Tag <form> diletakkan di sini, bukan di dalam _form.blade.php --}}
        <form action="{{ route('group.rencana.store', $grup) }}" method="POST" id="create-rencana-form">
            @csrf
            
            {{-- Konten Modal (Form) --}}
            <div class="p-6">
                @include('group::rencana._form', [
                    'mode' => 'create', 
                    'rencana' => new \App\Models\GrupRencana()
                ])
            </div>

            {{-- Footer Modal (Tombol Aksi) --}}
            <div class="px-6 py-4 bg-gray-50 border-t rounded-b flex items-center justify-end space-x-3">
                <button @click="createModalOpen = false" 
                        type="button" 
                        class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:w-auto sm:text-sm">
                    Batal
                </button>
                <button type="submit" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:w-auto sm:text-sm">
                    Simpan Rencana
                </button>
            </div>
        </form>
    </div>
</div>