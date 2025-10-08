<x-layout>
    <x-slot:title>
        Dashboard Catatan Keuangan | Credix
    </x-slot:title>

    <div class="container mx-auto p-4 sm:p-6 lg:p-8">

        {{-- Komponen Alpine.js dimulai di sini --}}
        <div x-data="{ deleteModalOpen: false, deleteAction: '' }" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Kolom Kiri: Kartu Riwayat Transaksi --}}
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-4">Riwayat Transaksi</h1>

                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4">
                    {{-- Filter Cepat (Dengan Fungsi Toggle) --}}
                    <div class="flex items-center overflow-x-auto pb-2">
                        
                        {{-- Tombol 3 Hari --}}
                        <a href="{{ request('range') == '3d' ? route('catatan.index', request()->except('range')) : route('catatan.index', array_merge(request()->except('range'), ['range' => '3d'])) }}" 
                        class="px-4 py-2 text-sm font-medium border rounded-l-lg {{ request('range') == '3d' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                            3 Hari
                        </a>

                        {{-- Tombol 5 Hari --}}
                        <a href="{{ request('range') == '5d' ? route('catatan.index', request()->except('range')) : route('catatan.index', array_merge(request()->except('range'), ['range' => '5d'])) }}"
                        class="px-4 py-2 -ml-px text-sm font-medium border {{ request('range') == '5d' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                            5 Hari
                        </a>

                        {{-- Tombol Minggu --}}
                        <a href="{{ request('range') == 'week' ? route('catatan.index', request()->except('range')) : route('catatan.index', array_merge(request()->except('range'), ['range' => 'week'])) }}" 
                        class="px-4 py-2 -ml-px text-sm font-medium border {{ request('range') == 'week' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                            Minggu
                        </a>
                        
                        {{-- Tombol Bulan --}}
                        <a href="{{ request('range') == 'month' ? route('catatan.index', request()->except('range')) : route('catatan.index', array_merge(request()->except('range'), ['range' => 'month'])) }}" 
                        class="px-4 py-2 -ml-px text-sm font-medium border {{ request('range') == 'month' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                            Bulan
                        </a>

                        {{-- Tombol Tahun --}}
                        <a href="{{ request('range') == 'year' ? route('catatan.index', request()->except('range')) : route('catatan.index', array_merge(request()->except('range'), ['range' => 'year'])) }}" 
                        class="px-4 py-2 -ml-px text-sm font-medium border rounded-r-lg {{ request('range') == 'year' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                            Tahun
                        </a>
                    </div>

                    <a href="{{ route('catatan.create') }}" class="mt-4 sm:mt-0 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 whitespace-nowrap">
                        + Tambah
                    </a>
                </div>

                {{-- Kontainer BARU untuk menyejajarkan Tabel dan Tombol Filter --}}
                <div class="flex items-start space-x-4">

                    {{-- Tabel Riwayat Transaksi (lebarnya dikurangi sedikit) --}}
                    <div class="overflow-x-auto flex-grow">
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
                                    <th scope="col" class="flex pl-15 py-3 text-center" style="width: 25%;">
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
                                                        {{ $catatan->category->nama === 'Lainnya...' ? ($catatan->custom_category ?? 'Lainnya') : ($catatan->category->nama ?? '-') }}
                                                        @if ($catatan->deskripsi)
                                                            <span class="block text-xs font-normal text-gray-500">{{ $catatan->deskripsi }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="px-6 py-4" style="width: 24%;">{{ $catatan->created_at->format('d M Y') }}</div>
                                                    <div class="px-6 py-4 text-right font-semibold {{ $catatan->category->tipe == 'pemasukan' ? 'text-green-600' : 'text-red-600' }}" style="width: 24%;">{{ $catatan->category->tipe == 'pemasukan' ? '+' : '-' }} Rp {{ number_format($catatan->jumlah, 0, ',', '.') }}</div>
                                                    
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

                    {{-- Tombol Filter (di luar tabel) --}}
                    <div>
                        <button type="button" data-modal-target="filter-modal" data-modal-toggle="filter-modal" 
                                class="flex items-center space-x-2 px-4 py-2 text-sm font-medium border rounded-lg bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"></path></svg>
                        </button>
                    </div>
                </div>
                <div class="pt-4"> {{ $catatans->links() }} </div>
            </div>
            
            {{-- Kolom Kanan: Kartu Info Saldo & Rencana --}}
            <div class="lg:col-span-1 space-y-8 flex flex-col">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md"> <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Ringkasan Keuangan</h2> <div class="space-y-4"> <div> <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400">Total Pemasukan</h3> <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p> </div> <div> <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400">Total Pengeluaran</h3> <p class="text-2xl font-bold text-red-600">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p> </div> <div class="border-t border-gray-200 dark:border-gray-700 pt-4"> <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400">Saldo Akhir</h3> <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</p> </div> </div> </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md"> <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Rencana Prioritas</h2> <div class="text-center text-gray-400 dark:text-gray-500 py-8"> <p class="font-semibold">Fitur Rencana akan segera hadir.</p> <p class="text-sm">Anda akan dapat melihat progres rencana yang diprioritaskan di sini.</p> </div> </div>
            </div>

            {{-- ======================================================= --}}
            {{-- LOKASI BARU: Modal Konfirmasi Hapus ada DI DALAM x-data --}}
            {{-- ======================================================= --}}
            <div x-show="deleteModalOpen" x-cloak 
                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                aria-labelledby="modal-title" role="dialog" aria-modal="true">
                
                {{-- Overlay --}}
                <div x-show="deleteModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="absolute inset-0 bg-gray-900/60" @click="deleteModalOpen = false"></div>

                {{-- Panel Modal --}}
                <div x-show="deleteModalOpen" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="relative w-full max-w-md p-6 overflow-hidden text-left align-middle bg-white rounded-lg shadow-xl dark:bg-gray-800">
                    
                    <h3 class="text-lg font-bold leading-6 text-gray-900 dark:text-white" id="modal-title">
                        Konfirmasi Penghapusan
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Apakah Anda yakin ingin menghapus catatan ini? Tindakan ini tidak dapat diurungkan.
                        </p>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="deleteModalOpen = false"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:bg-gray-600">
                            Batal
                        </button>
                        <form :action="deleteAction" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700">
                                Ya, Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div> {{-- Penutup komponen Alpine.js --}}

    </div>
    
    {{-- MODAL FILTER --}}
    <div id="filter-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full"> <div class="relative p-4 w-full max-w-md h-full md:h-auto"> <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5"> <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600"> <h3 class="text-lg font-semibold text-gray-900 dark:text-white"> Filter Transaksi </h3> <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="filter-modal"> <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg> <span class="sr-only">Close modal</span> </button> </div> <form action="{{ route('catatan.index') }}" method="GET"> @if(request('sort_by')) <input type="hidden" name="sort_by" value="{{ request('sort_by') }}"> <input type="hidden" name="order" value="{{ request('order') }}"> @endif <h4 class="mb-2 font-semibold text-gray-900 dark:text-white">Tipe Transaksi</h4> <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg sm:flex dark:bg-gray-700 dark:border-gray-600 dark:text-white mb-4"> <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600"><div class="flex items-center ps-3"><input id="all-type" type="radio" value="" name="tipe" class="w-4 h-4 text-blue-600" {{ !request('tipe') ? 'checked' : '' }}><label for="all-type" class="w-full py-3 ms-2">Semua</label></div></li> <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600"><div class="flex items-center ps-3"><input id="pemasukan" type="radio" value="pemasukan" name="tipe" class="w-4 h-4 text-blue-600" {{ request('tipe') == 'pemasukan' ? 'checked' : '' }}><label for="pemasukan" class="w-full py-3 ms-2">Pemasukan</label></div></li> <li class="w-full dark:border-gray-600"><div class="flex items-center ps-3"><input id="pengeluaran" type="radio" value="pengeluaran" name="tipe" class="w-4 h-4 text-blue-600" {{ request('tipe') == 'pengeluaran' ? 'checked' : '' }}><label for="pengeluaran" class="w-full py-3 ms-2">Pengeluaran</label></div></li> </ul> <h4 class="mb-2 font-semibold text-gray-900 dark:text-white">Kategori</h4> <div class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto p-2 border rounded-lg dark:border-gray-600"> @foreach ($categories as $category) <div class="flex items-center"> <input id="cat-{{ $category->id }}" name="kategori[]" type="checkbox" value="{{ $category->nama }}" class="w-4 h-4 text-blue-600 rounded" {{ in_array($category->nama, request('kategori', [])) ? 'checked' : '' }}> <label for="cat-{{ $category->id }}" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $category->nama }}</label> </div> @endforeach </div> <div class="flex items-center justify-end space-x-4 mt-6"> <a href="{{ route('catatan.index') }}" class="text-red-600 inline-flex items-center hover:text-white border border-red-600 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"> Reset Filter </a> <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"> Terapkan Filter </button> </div> </form> </div> </div> </div>

</x-layout>