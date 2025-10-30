<x-group::layout :title="'Rencana Grup'">

    {{-- Root AlpineJS untuk modal --}}
    <div x-data="{
            createModalOpen: false,
            editModalOpen: false,
            showModalOpen: false,
            deleteModalOpen: false,
            selectedRencana: null
        }">

        {{-- Header & Tombol Tambah (hanya admin) --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <h1 class="text-3xl font-bold text-gray-800">Rencana Keuangan</h1>
            @if ($userRole === 'admin') {{-- <-- IF #1 --}}
                <button @click="createModalOpen = true; selectedRencana = null;"
                    class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Buat Rencana Baru
                </button>
            @endif {{-- <-- ENDIF #1 --}}
        </div>

        {{-- "Kartu Besar" --}}
        <div class="bg-white rounded-lg shadow-lg p-6">
            
            {{-- Daftar Rencana --}}
            @if ($rencanas->isEmpty()) {{-- <-- IF #2 --}}
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">Belum ada rencana</h3>
                    <p class="mt-1 text-sm text-gray-500">Admin grup dapat membuat rencana keuangan baru.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($rencanas as $rencana)
                        <div class="bg-white rounded-lg shadow overflow-hidden flex flex-col">
                            <div class="p-6 flex-grow">
                                <div class="flex justify-between items-start mb-2">
                                    <h2 class="text-lg font-bold text-gray-800 truncate flex-1 mr-2" title="{{ $rencana->nama }}">
                                        {{ $rencana->nama }}
                                    </h2>
                                    {{-- Status Badge --}}
                                    <span class="text-xs font-medium px-2.5 py-0.5 rounded flex-shrink-0
                                        @if($rencana->status == 'selesai') bg-green-100 text-green-800 {{-- <-- IF #3 --}}
                                        @elseif($rencana->status == 'dibatalkan') bg-red-100 text-red-800
                                        @else bg-blue-100 text-blue-800 @endif"> {{-- <-- ENDIF #3 --}}
                                        {{ ucfirst($rencana->status) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                                    {{ $rencana->deskripsi ?: 'Tidak ada deskripsi.' }}
                                </p>
                                {{-- Progress Bar --}}
                                <div class="w-full bg-gray-200 rounded-full h-2.5 mb-1">
                                    <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" style="width: {{ min($rencana->progress, 100) }}%"></div>
                                </div>
                                <div class="flex justify-between text-xs text-gray-600 mb-1">
                                    <span class="font-medium">{{ number_format($rencana->progress, 1) }}%</span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600 mb-4">
                                    <span>Rp {{ number_format($rencana->jumlah_terkumpul, 0, ',', '.') }}</span>
                                    <span class="text-right">Target: Rp {{ number_format($rencana->target_jumlah, 0, ',', '.') }}</span>
                                </div>
                                <p class="text-xs text-gray-500">Dibuat oleh: {{ $rencana->user->name ?? 'N/A' }}</p>
                            </div>
                            {{-- Tombol Aksi --}}
                            <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 flex justify-end items-center gap-2">
                                <button @click="selectedRencana = @js($rencana->toArray()); showModalOpen = true"
                                        class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                    Lihat
                                </button>
                                @if ($userRole === 'admin') {{-- <-- IF #4 --}}
                                    <button @click="selectedRencana = @js($rencana->toArray()); editModalOpen = true; $dispatch('fill-form', { data: @js($rencana->toArray()) });"
                                            class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                        Edit
                                    </button>
                                    <button @click="selectedRencana = @js($rencana->toArray()); deleteModalOpen = true"
                                            class="text-sm text-red-600 hover:text-red-800 font-medium">
                                        Hapus
                                    </button>
                                @endif {{-- <-- ENDIF #4 --}}
                            </div>
                        </div>
                    @endforeach
                </div>
                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $rencanas->links() }}
                </div>
            @endif {{-- <-- ENDIF #2 --}}
        </div> {{-- AKHIR DARI "KARTU BESAR" --}}

        {{-- Modal Components --}}
        <x-group::rencana.create-modal :grup="$grup"/>
        <x-group::rencana.edit-modal :grup="$grup"/>
        <x-group::rencana.show-modal />
        {{-- <x-group::rencana.delete-modal :grup="$grup"/> --}}

    </div> {{-- Akhir dari x-data scope --}}

</x-group::layout>