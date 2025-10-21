<x-group::layout :title="'Rencana Grup'"> {{-- Memanggil layout grup --}}

    {{-- Root AlpineJS untuk modal --}}
    <div x-data="{
            createModalOpen: false,
            editModalOpen: false,
            showModalOpen: false,
            deleteModalOpen: false,
            selectedRencana: null // Untuk menyimpan data rencana yg dipilih
         }">

        {{-- Header & Tombol Tambah (hanya admin) --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <h1 class="text-3xl font-bold text-gray-800">Rencana Keuangan Grup: {{ $grup->nama }}</h1>
            {{-- Cek role user yg didapat dari controller --}}
            @if ($userRole === 'admin')
                <button @click="createModalOpen = true; selectedRencana = null;" {{-- Reset selectedRencana saat buka modal create --}}
                    class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Buat Rencana Baru
                </button>
            @endif
        </div>

        {{-- Daftar Rencana --}}
        @if ($rencanas->isEmpty())
            <div class="text-center py-12 bg-white rounded-lg shadow">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L3 10.25l1.06-1.06L9.75 14.88l8.19-8.19L19 7.75 9.75 17z"/> </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">Belum ada rencana</h3>
                <p class="mt-1 text-sm text-gray-500">Admin grup dapat membuat rencana keuangan baru.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($rencanas as $rencana)
                    <div class="bg-white rounded-lg shadow overflow-hidden flex flex-col">
                        <div class="p-6 flex-grow">
                            <div class="flex justify-between items-start mb-2">
                                <h2 class="text-lg font-bold text-gray-800 truncate" title="{{ $rencana->nama }}">
                                    {{ $rencana->nama }}
                                </h2>
                                {{-- Status Badge --}}
                                 <span class="text-xs font-medium px-2.5 py-0.5 rounded
                                    @if($rencana->status == 'selesai') bg-green-100 text-green-800
                                    @elseif($rencana->status == 'dibatalkan') bg-red-100 text-red-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ ucfirst($rencana->status) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-4 h-12 overflow-hidden">
                                {{ $rencana->deskripsi ?: 'Tidak ada deskripsi.' }}
                            </p>
                            {{-- Progress Bar --}}
                            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-1">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $rencana->progress }}%"></div>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600 mb-4">
                                <span>Rp {{ number_format($rencana->jumlah_terkumpul, 0, ',', '.') }}</span>
                                <span>Target: Rp {{ number_format($rencana->target_jumlah, 0, ',', '.') }}</span>
                            </div>
                             <p class="text-xs text-gray-500">Dibuat oleh: {{ $rencana->user->name ?? 'N/A' }}</p> {{-- Asumsi relasi user sudah ada di model GrupRencana --}}
                        </div>
                        {{-- Tombol Aksi (Show, Edit, Delete) --}}
                        <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 flex justify-end items-center gap-2">
                            <button @click="selectedRencana = {{ $rencana->toJson() }}; showModalOpen = true"
                                    class="text-sm text-blue-600 hover:text-blue-800">
                                Lihat
                            </button>
                            {{-- Hanya Admin yg bisa Edit/Hapus? Sesuaikan logic ini jika perlu --}}
                            @if ($userRole === 'admin')
                                <button @click="selectedRencana = {{ $rencana->toJson() }}; fillRencanaForm(selectedRencana); editModalOpen = true;" {{-- Panggil JS isi form --}}
                                        class="text-sm text-indigo-600 hover:text-indigo-800">
                                    Edit
                                </button>
                                <button @click="selectedRencana = {{ $rencana->toJson() }}; deleteModalOpen = true"
                                        class="text-sm text-red-600 hover:text-red-800">
                                    Hapus
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            {{-- Pagination --}}
            <div class="mt-6">
                {{ $rencanas->links() }}
            </div>
        @endif

        {{-- ====================================================== --}}
        {{-- MODAL SECTION --}}
        {{-- ====================================================== --}}

        {{-- Modal Tambah Rencana --}}
        <div x-show="createModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title-create" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                {{-- Background overlay --}}
                <div x-show="createModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="createModalOpen = false"></div>

                {{-- Modal panel --}}
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="createModalOpen"
                    x-cloak
                    {{-- [PERBAIKAN] Tambahkan transisi ke overlay dan panel --}}
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 z-50 flex items-center justify-center p-4" {{-- Flexbox untuk centering --}}
                    aria-labelledby="modal-title-create"
                    role="dialog"
                    aria-modal="true"
                    @keydown.escape.window="createModalOpen = false"> {{-- Tutup dengan Esc --}}

                    {{-- Background overlay --}}
                    {{-- [PERBAIKAN] Ganti bg-gray dengan backdrop-blur --}}
                    <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" @click="createModalOpen = false"></div>

                    {{-- Modal panel --}}
                    {{-- [PERBAIKAN] Transisi untuk panel modal --}}
                    <div x-show="createModalOpen"
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        {{-- Hentikan event click agar tidak menutup modal saat klik di dalam panel --}}
                        @click.stop
                        class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg w-full">
                        {{-- Hapus sm:my-8, sm:align-middle karena centering sudah dihandle flex parent --}}

                        {{-- Konten Modal --}}
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start w-full">
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title-create">
                                        Buat Rencana Grup Baru
                                    </h3>
                                    <div class="mt-4">
                                        {{-- FORM CREATE --}}
                                        <form action="{{ route('group.rencana.store', $grup) }}" method="POST">
                                            @csrf
                                            {{-- Gunakan form asli, bukan yang disederhanakan --}}
                                            @include('group::rencana._form', ['rencana' => new \App\Models\GrupRencana()])

                                            <div class="mt-5 sm:mt-6 sm:flex sm:flex-row-reverse">
                                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                                    Simpan Rencana
                                                </button>
                                                <button @click="createModalOpen = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                                    Batal
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Tombol close (opsional, karena ada tombol Batal & klik luar) --}}
                        {{-- <button @click="createModalOpen = false" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button> --}}
                    </div>  
                </div>
            </div>
        </div>

        {{-- Modal Edit Rencana --}}
        <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title-edit" role="dialog" aria-modal="true">
             <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                {{-- Background overlay --}}
                <div x-show="editModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="editModalOpen = false; selectedRencana = null;"></div>

                {{-- Modal panel --}}
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="editModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                     id="edit-rencana-modal-content"> {{-- Tambah ID untuk target JS --}}
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start w-full">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title-edit">
                                    Edit Rencana Grup
                                </h3>
                                <div class="mt-4">
                                    {{-- FORM EDIT --}}
                                    <form x-show="selectedRencana" :action="`/grup/{{ $grup->id }}/rencana/${selectedRencana.id}`" method="POST">
                                        @csrf
                                        @method('PUT')
                                        {{-- Include form partial (akan diisi oleh JS fillRencanaForm) --}}
                                        @include('group::rencana._form', ['rencana' => new \App\Models\GrupRencana()])
                                        <div class="mt-5 sm:mt-6 sm:flex sm:flex-row-reverse">
                                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                                Simpan Perubahan
                                            </button>
                                            <button @click="editModalOpen = false; selectedRencana = null" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                                Batal
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Lihat Detail Rencana --}}
        <div x-show="showModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title-show" role="dialog" aria-modal="true">
             <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                 {{-- Background overlay --}}
                <div x-show="showModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showModalOpen = false; selectedRencana = null;"></div>

                {{-- Modal panel --}}
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title-show" x-text="selectedRencana?.nama || 'Detail Rencana'">
                                </h3>
                                <div class="mt-4 space-y-2 text-sm text-gray-600" x-show="selectedRencana">
                                    <p><strong>Target:</strong> Rp <span x-text="selectedRencana ? Number(selectedRencana.target_jumlah).toLocaleString('id-ID') : ''"></span></p>
                                    <p><strong>Terkumpul:</strong> Rp <span x-text="selectedRencana ? Number(selectedRencana.jumlah_terkumpul).toLocaleString('id-ID') : ''"></span></p>
                                    <p><strong>Status:</strong> <span x-text="selectedRencana?.status" :class="{
                                        'text-green-700': selectedRencana?.status === 'selesai',
                                        'text-red-700': selectedRencana?.status === 'dibatalkan',
                                        'text-blue-700': selectedRencana?.status === 'berjalan',
                                    }"></span></p>
                                    <p><strong>Deskripsi:</strong></p>
                                    <p class="whitespace-pre-wrap" x-text="selectedRencana?.deskripsi || '-'"></p> {{-- Tampilkan deskripsi --}}
                                     <p class="mt-3 text-xs text-gray-500"><strong>Dibuat oleh:</strong> <span x-text="selectedRencana?.user?.name || 'N/A'"></span></p> {{-- Asumsi relasi user diload atau tersedia --}}
                                     <p class="text-xs text-gray-500"><strong>Tanggal Dibuat:</strong> <span x-text="selectedRencana ? new Date(selectedRencana.created_at).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short'}) : ''"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button @click="showModalOpen = false; selectedRencana = null" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Konfirmasi Hapus Rencana --}}
        <div x-show="deleteModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title-delete" role="dialog" aria-modal="true">
             <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                 {{-- Background overlay --}}
                <div x-show="deleteModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="deleteModalOpen = false; selectedRencana = null;"></div>

                {{-- Modal panel --}}
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="deleteModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                     <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                             <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /> </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title-delete">
                                    Hapus Rencana Grup?
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">Anda yakin ingin menghapus rencana <strong x-text="selectedRencana?.nama" class="font-semibold"></strong>? Tindakan ini tidak dapat diurungkan.</p>
                                </div>
                            </div>
                        </div>
                     </div>
                      <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                          {{-- Form Hapus --}}
                          <form x-show="selectedRencana" :action="`/grup/{{ $grup->id }}/rencana/${selectedRencana.id}`" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Ya, Hapus
                                </button>
                          </form>
                          <button @click="deleteModalOpen = false; selectedRencana = null" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                          </button>
                      </div>
                 </div>
            </div>
        </div>

    </div> {{-- Penutup div x-data --}}

    {{-- Script untuk mengisi form edit --}}
    @push('script')
    <script>
        function fillRencanaForm(rencanaData) {
            if (!rencanaData) return;
            // Dapatkan elemen form di dalam modal edit
            const modal = document.getElementById('edit-rencana-modal-content');
            if (!modal) return;

            const form = modal.querySelector('form');
            if (!form) return;

            // Isi nilai input berdasarkan data rencanaData
            form.querySelector('input[name="nama"]').value = rencanaData.nama || '';
            form.querySelector('input[name="target_jumlah"]').value = rencanaData.target_jumlah || '';
            form.querySelector('textarea[name="deskripsi"]').value = rencanaData.deskripsi || '';
            // Anda mungkin perlu mengisi input lainnya jika ada
        }
    </script>
    @endpush

</x-group::layout>