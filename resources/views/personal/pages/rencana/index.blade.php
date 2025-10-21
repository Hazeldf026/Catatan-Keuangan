<x-layout>
    <x-slot:title>
        Rencana Keuangan | Credix
    </x-slot:title>

    <div class="container mx-auto p-4 sm:p-6 lg:p-8">
        {{-- KARTU UTAMA DENGAN BACKGROUND PUTIH SEPERTI HALAMAN LAIN --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">

            {{-- HEADER: Judul, Tombol Tambah, DAN GARIS PEMBATAS --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 border-b border-gray-200 dark:border-gray-700 pb-4">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-4 sm:mb-0">Rencana Keuangan Saya</h1>
                <a href="{{ route('rencana.create') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 whitespace-nowrap">
                    + Tambah Rencana
                </a>
            </div>

            {{-- KONTROL FILTER DAN SORTING --}}
            <div class="flex justify-between items-center mb-6">
                
                {{-- Filter Status dengan background abu-abu seperti di gambar --}}
                <div class="flex items-center rounded-lg shadow-sm">
                    <a href="{{ route('rencana.index', array_merge(request()->except('page', 'status'))) }}" 
                       class="px-4 py-2 text-sm font-medium border rounded-l-lg {{ !request('status') ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-100 dark:bg-gray-700 border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        Semua
                    </a>
                    <a href="{{ route('rencana.index', array_merge(request()->except('page'), ['status' => 'berjalan'])) }}" 
                       class="px-4 py-2 -ml-px text-sm font-medium border {{ request('status') == 'berjalan' ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-100 dark:bg-gray-700 border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        Berjalan
                    </a>
                    <a href="{{ route('rencana.index', array_merge(request()->except('page'), ['status' => 'selesai'])) }}" 
                       class="px-4 py-2 -ml-px text-sm font-medium border {{ request('status') == 'selesai' ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-100 dark:bg-gray-700 border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        Selesai
                    </a>
                    <a href="{{ route('rencana.index', array_merge(request()->except('page'), ['status' => 'dibatalkan'])) }}" 
                       class="px-4 py-2 -ml-px text-sm font-medium border rounded-r-lg {{ request('status') == 'dibatalkan' ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-100 dark:bg-gray-700 border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        Dibatalkan
                    </a>
                </div>

                {{-- Dropdown Urutkan HANYA DENGAN IKON --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="p-2 text-sm font-medium border rounded-lg bg-gray-100 dark:bg-gray-700 border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-gray-800 focus:ring-blue-500">
                        <span class="sr-only">Buka menu urutkan</span>
                        {{-- IKON FILTER --}}
                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"></path></svg>
                    </button>
                    <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-700 rounded-md shadow-lg z-20 ring-1 ring-black ring-opacity-5" x-cloak>
                        <div class="py-1">
                            <a href="{{ route('rencana.index', array_merge(request()->query(), ['sort_by' => 'target', 'order' => 'desc'])) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Target Tertinggi</a>
                            <a href="{{ route('rencana.index', array_merge(request()->query(), ['sort_by' => 'target', 'order' => 'asc'])) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Target Terendah</a>
                            <a href="{{ route('rencana.index', array_merge(request()->query(), ['sort_by' => 'progress', 'order' => 'desc'])) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Progres Tertinggi</a>
                            <a href="{{ route('rencana.index', array_merge(request()->query(), ['sort_by' => 'progress', 'order' => 'asc'])) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Progres Terendah</a>
                            <a href="{{ route('rencana.index', array_merge(request()->query(), ['sort_by' => 'date', 'order' => 'asc'])) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Tanggal Terdekat</a>
                            <a href="{{ route('rencana.index', array_merge(request()->query(), ['sort_by' => 'date', 'order' => 'desc'])) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Tanggal Terjauh</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- AREA KARTU RENCANA --}}
            @if ($rencanas->isEmpty())
                <div class="text-center text-gray-500 dark:text-gray-400 py-20">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                    </svg>
                    <p class="mt-4 text-lg font-semibold">Tidak ada rencana ditemukan</p>
                    <p class="mt-1 text-sm">Coba ubah filter atau buat rencana keuangan pertamamu!</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($rencanas as $rencana)
                        {{-- Kartu Rencana Individual dengan background abu-abu --}}
                        <a href="{{ route('rencana.show', $rencana) }}" 
                            class="relative group block p-5 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm hover:ring-2 hover:ring-blue-500 transition-all duration-200 {{ $rencana->status === 'dibatalkan' ? 'opacity-60' : '' }}">
                            
                            @if($rencana->is_pinned)
                            <div class="absolute top-2 left-2 flex items-center text-xs text-blue-800 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/50 px-2 py-0.5 rounded-full z-10">
                                {{-- Ikon Pin Kecil --}}
                                <svg class="w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 3.75a.75.75 0 01.75.75v5.5a.75.75 0 01-1.5 0v-5.5a.75.75 0 01.75-.75z" />
                                <path fill-rule="evenodd" d="M8.01 6.33a.75.75 0 01.75-.75h2.48a.75.75 0 01.75.75v.005l.001.002.007.005.011.008a6.002 6.002 0 013.987 5.093l.002.012.002.016.002.019v2.234a.75.75 0 11-1.5 0v-2.18a4.502 4.502 0 00-4.01-4.474l-.011-.003-.01-.002-.014-.003h-1.954a4.502 4.502 0 00-4.01 4.474l-.011.003-.01.002-.014.003v2.18a.75.75 0 11-1.5 0V11.75l.002-.02.002-.016.002-.011a6.001 6.001 0 013.987-5.093l.011-.008.007-.005.001-.002V6.33z" clip-rule="evenodd" />
                                </svg>
                                <span>Di Pin</span>
                            </div>
                            @endif

                            <div class="flex justify-between items-start mb-3 {{ $rencana->is_pinned ? 'pt-5' : '' }}">
                                <h5 class="text-lg font-bold tracking-tight text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400">{{ $rencana->nama }}</h5>
                                @php
                                    $statusClass = match($rencana->status) {
                                        'berjalan' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                        'selesai' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                        'dibatalkan' => 'bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-300',
                                    };
                                @endphp
                                <span class="text-xs font-medium px-2.5 py-0.5 rounded-full {{ $statusClass }}">{{ ucfirst($rencana->status) }}</span>
                            </div>
                            
                            <div class="space-y-2">
                                <div class="flex justify-between items-baseline text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Terkumpul</span>
                                    <span class="font-semibold text-gray-800 dark:text-gray-200">Rp {{ number_format($rencana->jumlah_terkumpul, 0, ',', '.') }}</span>
                                </div>
                                
                                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-600">
                                    @php
                                        $raw_progress = ($rencana->target_jumlah > 0) ? ($rencana->jumlah_terkumpul / $rencana->target_jumlah) * 100 : 0;
                                        // Gunakan min() untuk membatasi nilai yang akan ditampilkan
                                        $progress_display = min($raw_progress, 100);
                                    @endphp
                                    <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $progress_display }}%"></div>
                                </div>

                                <div class="flex justify-between items-baseline text-xs">
                                    <span class="text-gray-500 dark:text-gray-400">Target: Rp {{ number_format($rencana->target_jumlah, 0, ',', '.') }}</span>
                                    {{-- FINAL: Tampilkan nilai yang sudah dibatasi --}}
                                    <span class="font-bold text-gray-800 dark:text-gray-200">{{ round($progress_display) }}%</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Paginasi --}}
            <div class="pt-8">
                {{ $rencanas->links() }}
            </div>
        </div>
    </div>
</x-layout>
