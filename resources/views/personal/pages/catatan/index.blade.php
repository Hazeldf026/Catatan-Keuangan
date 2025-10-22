<x-personal::layout>
    <x-slot:title>
        Dashboard | Credix
    </x-slot:title>

    <div class="container mx-auto p-4 sm:p-6 lg:p-8">

        <div x-data="{ deleteModalOpen: false, deleteAction: '' }" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Kolom Kiri: Kartu Riwayat Transaksi --}}
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                
                {{-- Judul dan Tombol Tambah --}}
                <div class="flex items-center justify-between pb-4 border-b border-gray-300 dark:border-gray-700">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Riwayat Transaksi</h1>
                    <a href="{{ route('catatan.create') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 whitespace-nowrap">
                        + Tambah
                    </a>
                </div>

                {{-- Filter Tanggal dan Tombol Filter Modal  --}}
                <div class="flex items-center justify-between my-6">
                    {{-- Filter Tanggal --}}
                    <div class="flex items-center rounded-lg shadow-sm">
                        <a href="{{ request('range') == '3d' ? route('catatan.index', request()->except('range')) : route('catatan.index', array_merge(request()->except('range', 'page'), ['range' => '3d'])) }}" 
                        class="px-4 py-2 text-sm font-medium border rounded-l-lg {{ request('range') == '3d' ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-100 dark:bg-gray-700 border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                            3 Hari
                        </a>
                        <a href="{{ request('range') == '5d' ? route('catatan.index', request()->except('range')) : route('catatan.index', array_merge(request()->except('range', 'page'), ['range' => '5d'])) }}"
                        class="px-4 py-2 -ml-px text-sm font-medium border {{ request('range') == '5d' ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-100 dark:bg-gray-700 border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                            5 Hari
                        </a>
                        <a href="{{ request('range') == 'week' ? route('catatan.index', request()->except('range')) : route('catatan.index', array_merge(request()->except('range', 'page'), ['range' => 'week'])) }}" 
                        class="px-4 py-2 -ml-px text-sm font-medium border {{ request('range') == 'week' ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-100 dark:bg-gray-700 border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                            Minggu
                        </a>
                        <a href="{{ request('range') == 'month' ? route('catatan.index', request()->except('range')) : route('catatan.index', array_merge(request()->except('range', 'page'), ['range' => 'month'])) }}" 
                        class="px-4 py-2 -ml-px text-sm font-medium border {{ request('range') == 'month' ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-100 dark:bg-gray-700 border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                            Bulan
                        </a>
                        <a href="{{ request('range') == 'year' ? route('catatan.index', request()->except('range')) : route('catatan.index', array_merge(request()->except('range', 'page'), ['range' => 'year'])) }}" 
                        class="px-4 py-2 -ml-px text-sm font-medium border rounded-r-lg {{ request('range') == 'year' ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-100 dark:bg-gray-700 border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                            Tahun
                        </a>
                    </div>
                    
                    {{-- Tombol Filter Modal --}}
                    <button type="button" data-modal-target="filter-modal" data-modal-toggle="filter-modal" 
                            class="p-2 text-sm font-medium border rounded-lg bg-gray-100 dark:bg-gray-700 border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-gray-800 focus:ring-blue-500">
                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>  

                {{-- Tabel --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3" style="width: 50%;">Detail</th>
                                <th scope="col" class="px-6 py-3" style="width: 25%;">
                                    <a href="{{ route('catatan.index', array_merge(request()->query(), ['sort_by' => 'tanggal', 'order' => request('order') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                        Tanggal
                                        @if (request('sort_by') == 'tanggal')
                                            <svg class="w-3 h-3 ml-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="{{ request('order') == 'asc' ? 'M8.574 14.828a.5.5 0 0 1-.707 0l-4-4a.5.5 0 0 1 .707-.707L8 13.414l3.293-3.293a.5.5 0 1 1 .707 .707l-4 4Z' : 'M15.426 9.172a.5.5 0 0 1 .707 0l4 4a.5.5 0 0 1-.707.707L16 10.586l-3.293 3.293a.5.5 0 1 1-.707-.707l4-4Z' }}" />
                                            </svg>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col" class="px-6 py-3 text-right" style="width: 25%;">
                                    <a href="{{ route('catatan.index', array_merge(request()->query(), ['sort_by' => 'jumlah', 'order' => request('order') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center justify-end">
                                        Jumlah
                                        @if (request('sort_by') == 'jumlah')
                                            <svg class="w-3 h-3 ml-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="{{ request('order') == 'asc' ? 'M8.574 14.828a.5.5 0 0 1-.707 0l-4-4a.5.5 0 0 1 .707-.707L8 13.414l3.293-3.293a.5.5 0 1 1 .707 .707l-4 4Z' : 'M15.426 9.172a.5.5 0 0 1 .707 0l4 4a.5.5 0 0 1-.707.707L16 10.586l-3.293 3.293a.5.5 0 1 1-.707-.707l4-4Z' }}" />
                                            </svg>
                                        @endif
                                    </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($catatans as $catatan)
                                <tr 
                                    x-data="{ hovered: false }" 
                                    class="group border-b dark:border-gray-700 cursor-pointer" 
                                    onclick="window.location='{{ route('catatan.show', $catatan) }}'">

                                    <td colspan="3" class="p-0 overflow-hidden">
                                        <div class="relative w-full" @mouseleave="hovered = false">
                                            
                                            <div @click.stop class="absolute top-0 right-0 z-0 flex items-center h-full px-4 space-x-2 bg-gray-50 dark:bg-gray-700" style="width: 120px;">
                                                <a href="{{ route('catatan.edit', $catatan) }}" class="p-2 text-white bg-yellow-500 hover:bg-yellow-600 rounded-lg">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg>
                                                </a>
                                                <button type="button" 
                                                        @click="deleteAction = '{{ route('catatan.destroy', $catatan) }}'; deleteModalOpen = true"
                                                        class="p-2 text-white bg-red-600 hover:bg-red-700 rounded-lg">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                                </button>
                                            </div>

                                            <div 
                                                :class="hovered ? '-translate-x-[120px]' : ''" 
                                                class="relative z-10 flex w-full items-center transition-transform duration-300 ease-in-out bg-white dark:bg-gray-800 group-hover:bg-gray-50 dark:group-hover:bg-gray-700">
                                                
                                                <div class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" style="width: 48%;">
                                                    {{-- LOGIKA JUDUL ASLI DIKEMBALIKAN --}}
                                                    {{ $catatan->category->nama === 'Lainnya...' ? ($catatan->custom_category ?? 'Lainnya') : ($catatan->category->nama ?? '-') }}
                                                    
                                                    @if ($catatan->deskripsi)
                                                        {{-- PERBAIKAN FINAL: Menambahkan class truncate dan max-w-* --}}
                                                        <span class="block text-xs font-normal text-gray-500 truncate max-w-sm">{{ $catatan->deskripsi }}</span>
                                                    @endif
                                                </div>
                                                <div class="px-6 py-4" style="width: 24%;">{{ $catatan->created_at->format('d M Y') }}</div>
                                                <div class="px-6 py-4 text-right font-semibold {{ $catatan->category->tipe == 'pemasukan' ? 'text-green-600' : 'text-red-600' }}" style="width: 24%;">
                                                    {{ $catatan->category->tipe == 'pemasukan' ? '+' : '-' }} Rp {{ number_format($catatan->jumlah, 0, ',', '.') }}
                                                </div>
                                                
                                                <div @mouseenter="hovered = true" @click.stop class="flex items-center justify-center h-full" style="width: 4%;">
                                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" /></svg>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr class="border-b dark:border-gray-700">
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">Tidak ada data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- Pagination Kustom --}}
                <div class="pt-6">
                    {{ $catatans->links() }}
                </div>
            </div>
            
            {{-- Kolom Kanan --}}
            <div class="lg:col-span-1 space-y-8 flex flex-col">
                {{-- KARTU RINGKASAN KEUANGAN--}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Ringkasan Keuangan</h2>
                    
                    <div class="grid grid-cols-2 gap-4">
                        {{-- Total Wallet --}}
                        <div class="bg-orange-50 dark:bg-orange-900/50 p-4 rounded-lg">
                            <div class="flex items-center text-orange-600 dark:text-orange-400 mb-1">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                <h3 class="text-sm font-semibold">Wallet</h3>
                            </div>
                            <p class="text-lg font-bold text-orange-600 dark:text-orange-400">Rp {{ number_format($totalWallet, 0, ',', '.') }}</p>
                        </div>

                        {{-- Total Bank --}}
                        <div class="bg-indigo-50 dark:bg-indigo-900/50 p-4 rounded-lg">
                            <div class="flex items-center text-indigo-600 dark:text-indigo-400 mb-1">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <h3 class="text-sm font-semibold">Bank</h3>
                            </div>
                            <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400">Rp {{ number_format($totalBank, 0, ',', '.') }}</p>
                        </div>

                        {{-- Total E-Wallet --}}
                        <div class="bg-sky-50 dark:bg-sky-900/50 p-4 rounded-lg">
                            <div class="flex items-center text-sky-600 dark:text-sky-400 mb-1">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                <h3 class="text-sm font-semibold">E-Wallet</h3>
                            </div>
                            <p class="text-lg font-bold text-sky-600 dark:text-sky-400">Rp {{ number_format($totalEWallet, 0, ',', '.') }}</p>
                        </div>

                        {{-- Total Tabungan --}}
                        <div class="bg-amber-50 dark:bg-amber-900/50 p-4 rounded-lg">
                            <div class="flex items-center text-amber-600 dark:text-amber-400 mb-1">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h12a2 2 0 012 2v6z"></path></svg>
                                <h3 class="text-sm font-semibold">Tabungan</h3>
                            </div>
                            <p class="text-lg font-bold text-amber-600 dark:text-amber-400">Rp {{ number_format($totalTabungan, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4 grid grid-cols-2 gap-4">
                        {{-- Total Rencana --}}
                        <div class="bg-purple-50 dark:bg-purple-900/50 p-4 rounded-lg col-span-2">
                            <div class="flex items-center text-purple-600 dark:text-purple-400 mb-1">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                <h3 class="text-sm font-semibold">Total Dana Rencana</h3>
                            </div>
                            <p class="text-lg font-bold text-purple-600 dark:text-purple-400">Rp {{ number_format($totalRencana, 0, ',', '.') }}</p>
                        </div>

                        {{-- Total Pemasukan --}}
                        <div class="bg-green-50 dark:bg-green-900/50 p-4 rounded-lg">
                            <div class="flex items-center text-green-600 dark:text-green-400 mb-1">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01M12 6v-1m0-1V4m0 2.01M12 16v1m0 1v1m0-2.01V16m0-8V6m-6 6h.01M6 12H5m1.01 0H6m6 0h.01M12 12h-1m1.01 0H12m6 0h.01M18 12h-1m1.01 0H18M5 12a7 7 0 1114 0 7 7 0 01-14 0z"></path></svg>
                                <h3 class="text-xs font-semibold">Total Pemasukan</h3>
                            </div>
                            <p class="text-lg font-bold text-green-600 dark:text-green-400">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
                        </div>

                        {{-- Total Pengeluaran --}}
                        <div class="bg-red-50 dark:bg-red-900/50 p-4 rounded-lg">
                            <div class="flex items-center text-red-600 dark:text-red-400 mb-1">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                                <h3 class="text-xs font-semibold">Total Pengeluaran</h3>
                            </div>
                            <p class="text-lg font-bold text-red-600 dark:text-red-400">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                        </div>

                        {{-- Total Saldo --}}
                        <div class="bg-blue-50 dark:bg-blue-900/50 p-4 rounded-lg col-span-2">
                            <div class="flex items-center text-blue-600 dark:text-blue-400 mb-1">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2h10a2 2 0 002-2v-1a2 2 0 012-2h1.945M7.737 11l-.262-2.839a2 2 0 012.24-2.135h4.572a2 2 0 012.24 2.135L16.263 11M9 11V5.5A2.5 2.5 0 0111.5 3h1A2.5 2.5 0 0115 5.5V11"></path></svg>
                                <h3 class="text-sm font-semibold">Total Saldo</h3>
                            </div>
                            <p class="text-xl font-bold text-blue-600 dark:text-blue-400">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                
                {{-- KARTU RENCANA PRIORITAS --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Rencana Prioritas</h2>
                    
                    @if($pinnedRencanas->isEmpty())
                        <div class="text-center text-gray-400 dark:text-gray-500 py-8">
                            <p class="font-semibold">Tidak ada rencana yang di-pin.</p>
                            <p class="text-sm">Pin rencana penting Anda untuk melihatnya di sini.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($pinnedRencanas as $rencana)
                            <a href="{{ route('rencana.show', $rencana) }}" class="block p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-400">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm font-semibold text-gray-800 dark:text-white">{{ $rencana->nama }}</span>
                                    @php
                                        $progress = ($rencana->target_jumlah > 0) ? min(($rencana->jumlah_terkumpul / $rencana->target_jumlah) * 100, 100) : 0;
                                    @endphp
                                    <span class="text-xs font-bold text-gray-600 dark:text-gray-300">{{ round($progress) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-600">
                                    <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $progress }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Rp {{ number_format($rencana->jumlah_terkumpul, 0, ',', '.') }} / {{ number_format($rencana->target_jumlah, 0, ',', '.') }}
                                </p>
                            </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Modal Konfirmasi Hapus --}}
            <x-delete-confirmation>
                Apakah Anda yakin ingin menghapus catatan ini? Tindakan ini tidak dapat diurungkan.
            </x-delete-confirmation>

        </div>
    </div>
    
    {{-- Modal Filter --}}
    <div id="filter-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full">
        <div class="relative p-4 w-full max-w-md h-full md:h-auto">
            <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white"> Filter Transaksi </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="filter-modal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <form action="{{ route('catatan.index') }}" method="GET">
                    @if(request('sort_by'))
                        <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                        <input type="hidden" name="order" value="{{ request('order') }}">
                    @endif

                    <h4 class="mb-2 font-semibold text-gray-900 dark:text-white">Tipe Transaksi</h4>
                    <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg sm:flex dark:bg-gray-700 dark:border-gray-600 dark:text-white mb-4">
                        <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600"><div class="flex items-center ps-3"><input id="all-type" type="radio" value="" name="tipe" class="w-4 h-4 text-blue-600" {{ !request('tipe') ? 'checked' : '' }}><label for="all-type" class="w-full py-3 ms-2">Semua</label></div></li>
                        <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600"><div class="flex items-center ps-3"><input id="pemasukan" type="radio" value="pemasukan" name="tipe" class="w-4 h-4 text-blue-600" {{ request('tipe') == 'pemasukan' ? 'checked' : '' }}><label for="pemasukan" class="w-full py-3 ms-2">Pemasukan</label></div></li>
                        <li class="w-full dark:border-gray-600"><div class="flex items-center ps-3"><input id="pengeluaran" type="radio" value="pengeluaran" name="tipe" class="w-4 h-4 text-blue-600" {{ request('tipe') == 'pengeluaran' ? 'checked' : '' }}><label for="pengeluaran" class="w-full py-3 ms-2">Pengeluaran</label></div></li>
                    </ul>

                    <h4 class="mb-2 font-semibold text-gray-900 dark:text-white">Kategori</h4>
                    <div class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto p-2 border rounded-lg dark:border-gray-600">
                        @foreach ($categories as $category)
                            <div class="flex items-center">
                                <input id="cat-{{ $category->id }}" name="kategori[]" type="checkbox" value="{{ $category->nama }}" class="w-4 h-4 text-blue-600 rounded" {{ in_array($category->nama, request('kategori', [])) ? 'checked' : '' }}>
                                <label for="cat-{{ $category->id }}" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $category->nama }}</label>
                            </div>
                        @endforeach
                    </div>

                    <h4 class="mt-4 mb-2 font-semibold text-gray-900 dark:text-white">Alokasi</h4>
                    <div class="p-3 border rounded-lg dark:border-gray-600 space-y-3">
                        
                        <label class="text-sm font-medium text-gray-900 dark:text-gray-300">Media</label>
                        
                        <div class="grid grid-cols-2 gap-2">
                            @php $mediaOptions = ['wallet', 'bank', 'e-wallet', 'tabungan']; @endphp
                            @foreach ($mediaOptions as $media)
                                <div class="flex items-center">
                                    <input id="media-{{ $media }}" name="media[]" type="checkbox" value="{{ $media }}" class="w-4 h-4 text-blue-600 rounded" {{ in_array($media, request('media', [])) ? 'checked' : '' }}>
                                    <label for="media-{{ $media }}" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ ucfirst($media) }}</label>
                                </div>
                            @endforeach
                        </div>

                        <hr class="border-gray-200 dark:border-gray-500 my-2">

                        <label class="text-sm font-medium text-gray-900 dark:text-gray-300">Rencana</label>
                        
                        <div class="flex items-center pt-1">
                            <input id="alokasi_rencana" name="alokasi_rencana" type="checkbox" value="true" class="w-4 h-4 text-blue-600 rounded" {{ request('alokasi_rencana') == 'true' ? 'checked' : '' }}>
                            <label for="alokasi_rencana" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Rencana</label>
                        </div>

                    </div>
                    <div class="flex items-center justify-end space-x-4 mt-6">
                        <a href="{{ route('catatan.index') }}" class="text-red-600 inline-flex items-center hover:text-white border border-red-600 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"> Reset Filter </a>
                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"> Terapkan Filter </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-personal::layout>