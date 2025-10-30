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