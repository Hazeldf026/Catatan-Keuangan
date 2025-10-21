<x-layout>
    <x-slot:title>
        Detail Rencana: {{ $rencana->nama }}
    </x-slot:title>

    <div x-data="{ deleteModalOpen: false, deleteAction: '', cancelModalOpen: false, cancelAction: '' }" 
        class="container mx-auto p-4 sm:p-6 lg:p-8">
        {{-- Wrapper untuk Efek Pudar/Non-aktif saat Dibatalkan --}}
        <div class="{{ $rencana->status === 'dibatalkan' ? 'opacity-60' : '' }}">

            {{-- KARTU UTAMA DETAIL RENCANA --}}
            <div class="w-full max-w-4xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mb-8">
                
                {{-- BAGIAN 1: TOMBOL AKSI SEJAJAR --}}
                <div class="flex flex-col sm:flex-row items-start justify-between mb-4 border-b border-gray-200 dark:border-gray-700 pb-4">
                    {{-- Tombol Kembali --}}
                    <a href="{{ route('rencana.index') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:bg-gray-600 mb-4 sm:mb-0">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        Kembali
                    </a>
                    
                    {{-- Grup Tombol Aksi Kanan --}}
                    <div class="flex space-x-2">
                        {{-- Tombol Edit & Batalkan hanya muncul jika status BUKAN 'dibatalkan' --}}
                        @if ($rencana->status !== 'dibatalkan')
                            {{-- Tombol Pin/Unpin --}}
                            <form action="{{ route('rencana.togglePin', $rencana) }}" method="POST">
                                @csrf
                                {{-- PERBAIKAN: Ganti teks dengan ikon, warna tetap abu-abu --}}
                                <button type="submit" 
                                        class="p-2 text-sm font-medium rounded-lg 
                                            bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 
                                            text-gray-700 dark:text-gray-200"
                                        title="{{ $rencana->is_pinned ? 'Lepas Pin' : 'Pin Rencana' }}">
                                    @if ($rencana->is_pinned)
                                        {{-- Ikon Unpin (Outline) --}}
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.25 4.75v5.5m1.5 0v-5.5M8.01 6.33h3.98M4.75 11.75h10.5M6.25 14.75h7.5M8 17.75h4M5.75 11.75a4.5 4.5 0 00-1.037 3.394v2.606a.75.75 0 00.75.75h9.074a.75.75 0 00.75-.75v-2.606a4.5 4.5 0 00-1.037-3.394" />
                                        </svg> 
                                    @else
                                        {{-- Ikon Pin (Solid) --}}
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 3.75a.75.75 0 01.75.75v5.5a.75.75 0 01-1.5 0v-5.5a.75.75 0 01.75-.75z" />
                                        <path fill-rule="evenodd" d="M8.01 6.33a.75.75 0 01.75-.75h2.48a.75.75 0 01.75.75v.005l.001.002.007.005.011.008a6.002 6.002 0 013.987 5.093l.002.012.002.016.002.019v2.234a.75.75 0 11-1.5 0v-2.18a4.502 4.502 0 00-4.01-4.474l-.011-.003-.01-.002-.014-.003h-1.954a4.502 4.502 0 00-4.01 4.474l-.011.003-.01.002-.014.003v2.18a.75.75 0 11-1.5 0V11.75l.002-.02.002-.016.002-.011a6.001 6.001 0 013.987-5.093l.011-.008.007-.005.001-.002V6.33z" clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                </button>
                            </form>

                            <a href="{{ route('rencana.edit', $rencana) }}" class="px-4 py-2 text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 rounded-lg">Edit</a>
                            
                            @if ($rencana->status === 'berjalan')
                            {{-- Ganti <form> dengan <button> pemicu Alpine --}}
                            <button type="button" 
                                    @click="cancelAction = '{{ route('rencana.cancel', $rencana) }}'; cancelModalOpen = true" 
                                    class="px-4 py-2 text-sm font-medium text-white bg-gray-500 hover:bg-gray-600 rounded-lg">
                                Batalkan
                            </button>
                            @endif
                        @endif

                        {{-- Tombol Hapus SELALU Muncul --}}
                        <button type="button" 
                                @click="deleteAction = '{{ route('rencana.destroy', $rencana) }}'; deleteModalOpen = true"
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg">
                            Hapus
                        </button>
                    </div>
                </div>

                {{-- BAGIAN 2: DETAIL RENCANA --}}
                <div class="space-y-5">
                    {{-- Nama Rencana dan Status --}}
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $rencana->nama }}</h1>
                            @if ($rencana->target_tanggal)
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Target: {{ \Carbon\Carbon::parse($rencana->target_tanggal)->format('d F Y') }}</p>
                            @endif
                        </div>
                        @php
                            $statusClass = match($rencana->status) {
                                'berjalan' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                'selesai' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                'dibatalkan' => 'bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-300',
                            };
                        @endphp
                        {{-- Status dengan font besar --}}
                        <span class="text-xl font-bold px-4 py-1 rounded-lg {{ $statusClass }}">{{ ucfirst($rencana->status) }}</span>
                    </div>

                    {{-- Deskripsi --}}
                    @if ($rencana->deskripsi)
                        <p class="text-gray-600 dark:text-gray-300 pt-2">{{ $rencana->deskripsi }}</p>
                    @endif
                
                    {{-- Progress Bar dan Angka --}}
                    <div class="pt-2 space-y-3">
                        <div class="flex justify-between items-baseline">
                            <span class="font-bold text-2xl text-gray-800 dark:text-gray-100">Rp {{ number_format($rencana->jumlah_terkumpul, 0, ',', '.') }}</span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">dari target Rp {{ number_format($rencana->target_jumlah, 0, ',', '.') }}</span>
                        </div>
                        @php
                            $raw_progress = ($rencana->target_jumlah > 0) ? ($rencana->jumlah_terkumpul / $rencana->target_jumlah) * 100 : 0;
                            $progress_display = min($raw_progress, 100);
                        @endphp
                        <div class="w-full bg-gray-200 rounded-full h-4 dark:bg-gray-700">
                            <div class="bg-gradient-to-r from-cyan-500 to-blue-500 h-4 rounded-full text-center text-white text-xs font-bold" style="width: {{ $progress_display }}%">{{ round($progress_display) }}%</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TABEL RIWAYAT TRANSAKSI (Tidak Berubah) --}}
            <div class="w-full max-w-4xl mx-auto bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Riwayat Tabungan</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Tanggal</th>
                                <th scope="col" class="px-6 py-3">Deskripsi</th>
                                <th scope="col" class="px-6 py-3 text-right">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($catatans as $catatan)
                                <tr class="border-b dark:border-gray-600">
                                    <td class="px-6 py-4">{{ $catatan->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $catatan->deskripsi }}</td>
                                    <td class="px-6 py-4 text-right font-semibold text-green-600">+ Rp {{ number_format($catatan->jumlah, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">Belum ada dana yang ditambahkan ke rencana ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="pt-4"> {{ $catatans->links() }} </div>
            </div>

        </div>

        <x-delete-confirmation title="Konfirmasi Hapus Rencana">
            Apakah Anda yakin ingin menghapus rencana ini secara permanen? Semua catatan terkait tidak akan ikut terhapus.
        </x-delete-confirmation>

        <x-cancel-confirmation>
            Apakah Anda yakin ingin membatalkan rencana ini? Status akan diubah menjadi "Dibatalkan" dan tidak bisa diubah kembali. Dana yang terkumpul tidak akan dikembalikan.
        </x-cancel-confirmation>

    </div>
</x-layout>