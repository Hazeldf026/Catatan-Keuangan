<x-group::layout :title="'Catatan Grup'"> {{-- Layout group otomatis dapat $grup --}}

    {{-- Root AlpineJS untuk modal --}}
    <div x-data="{
            createModalOpen: false,
            editModalOpen: false,
            showModalOpen: false,
            filterModalOpen: false,
            selectedCatatan: null // Untuk menyimpan data catatan yg diedit/dilihat
        }">

        {{-- Judul Halaman --}}
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard Catatan: {{ $grup->nama }}</h1>

        {{-- Baris 1: Kartu Media --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            {{-- Wallet --}}
            <div class="bg-white p-5 rounded-lg shadow flex items-center space-x-4">
                <div class="bg-orange-100 p-3 rounded-full"> <svg class="w-6 h-6 text-orange-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0A2.25 2.25 0 0018.75 9.75h-1.5a3 3 0 10-6 0H5.25A2.25 2.25 0 003 12m18 0v-6A2.25 2.25 0 0018.75 3.75h-1.5a3 3 0 10-6 0H5.25A2.25 2.25 0 003 6v6" /></svg> </div>
                <div> <h4 class="text-sm font-medium text-gray-500">Wallet</h4> <p class="text-xl font-bold text-gray-900">Rp {{ number_format($totalWallet, 0, ',', '.') }}</p> </div>
            </div>
            {{-- Bank --}}
            <div class="bg-white p-5 rounded-lg shadow flex items-center space-x-4">
                <div class="bg-violet-100 p-3 rounded-full"> <svg class="w-6 h-6 text-violet-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21l-8.5-5.5V9l8.5-5.5L20.5 9v6.5L12 21z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 21V3m0 18L3.5 14.5M20.5 14.5L12 21m-8.5-6.5L12 3m0 0l8.5 5.5M3.5 9l8.5 5.5m0 0l8.5-5.5" /></svg> </div>
                <div> <h4 class="text-sm font-medium text-gray-500">Bank</h4> <p class="text-xl font-bold text-gray-900">Rp {{ number_format($totalBank, 0, ',', '.') }}</p> </div>
            </div>
            {{-- E-Wallet --}}
            <div class="bg-white p-5 rounded-lg shadow flex items-center space-x-4">
                <div class="bg-sky-100 p-3 rounded-full"> <svg class="w-6 h-6 text-sky-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75A2.25 2.25 0 0015.75 1.5m-7.5 0v-1.5m7.5 1.5v-1.5m-7.5 0h7.5" /></svg> </div>
                <div> <h4 class="text-sm font-medium text-gray-500">E-Wallet</h4> <p class="text-xl font-bold text-gray-900">Rp {{ number_format($totalEWallet, 0, ',', '.') }}</p> </div>
            </div>
            {{-- Tabungan --}}
            <div class="bg-white p-5 rounded-lg shadow flex items-center space-x-4">
                <div class="bg-yellow-100 p-3 rounded-full"> <svg class="w-6 h-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375" /></svg> </div>
                <div> <h4 class="text-sm font-medium text-gray-500">Tabungan</h4> <p class="text-xl font-bold text-gray-900">Rp {{ number_format($totalTabungan, 0, ',', '.') }}</p> </div>
            </div>
        </div>

        {{-- Baris 2: Kartu Rencana --}}
        <div class="grid grid-cols-1 gap-6 mb-6">
            <div class="bg-white p-5 rounded-lg shadow flex items-center space-x-4">
                <div class="bg-emerald-100 p-3 rounded-full"> <svg class="w-6 h-6 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> </div>
                <div> <h4 class="text-sm font-medium text-gray-500">Total Dana di Rencana Grup</h4> <p class="text-xl font-bold text-gray-900">Rp {{ number_format($totalRencana, 0, ',', '.') }}</p> </div>
            </div>
        </div>

        {{-- Baris 3: Kartu Total Grup --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            {{-- Pemasukan Grup --}}
            <div class="bg-white p-5 rounded-lg shadow flex items-center space-x-4">
                <div class="bg-green-100 p-3 rounded-full"> <svg class="w-6 h-6 text-green-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 2a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1M2 5h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Zm8 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z"/></svg> </div>
                <div> <h4 class="text-sm font-medium text-gray-500">Total Pemasukan Grup</h4> <p class="text-xl font-bold text-gray-900">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p> </div>
            </div>
            {{-- Pengeluaran Grup --}}
            <div class="bg-white p-5 rounded-lg shadow flex items-center space-x-4">
                <div class="bg-red-100 p-3 rounded-full"> <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> </div>
                <div> <h4 class="text-sm font-medium text-gray-500">Total Pengeluaran Grup</h4> <p class="text-xl font-bold text-gray-900">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p> </div>
            </div>
            {{-- Saldo Grup --}}
            <div class="bg-white p-5 rounded-lg shadow flex items-center space-x-4">
                <div class="bg-blue-100 p-3 rounded-full"> <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg> </div>
                <div> <h4 class="text-sm font-medium text-gray-500">Saldo Grup Saat Ini</h4> <p class="text-xl font-bold text-gray-900">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</p> </div>
            </div>
        </div>

        {{-- Kartu Tabel Catatan --}}
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
                <h2 class="text-xl font-bold text-gray-800">Riwayat Catatan Grup</h2>
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    {{-- Tombol Tambah Catatan (Buka Modal Create) --}}
                    <button @click="createModalOpen = true"
                        class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        Tambah Catatan
                    </button>
                    {{-- Tombol Filter (Buka Modal Filter) --}}
                    <button @click="filterModalOpen = true"
                        class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.572a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" /></svg>
                        Filter
                    </button>
                    {{-- Filter Tanggal (Dropdown Sederhana) --}}
                    {{-- Form ini akan submit GET request untuk filter --}}
                    <form method="GET" action="{{ route('group.catatan.index', $grup) }}" class="w-full sm:w-auto">
                        <select name="range" onchange="this.form.submit()" class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Waktu</option>
                            <option value="7d" {{ request('range') == '7d' ? 'selected' : '' }}>7 Hari Terakhir</option>
                            <option value="30d" {{ request('range') == '30d' ? 'selected' : '' }}>30 Hari Terakhir</option>
                            {{-- Tambah opsi lain jika perlu --}}
                        </select>
                    </form>
                </div>
            </div>

            {{-- Tabel Catatan --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Media</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Oleh</th> {{-- Siapa yg mencatat --}}
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($catatans as $catatan)
                            <tr class="hover:bg-gray-50 cursor-pointer" @click="selectedCatatan = {{ $catatan->toJson() }}; showModalOpen = true"> {{-- Buka modal show saat diklik --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $catatan->created_at->format('d M Y, H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $catatan->deskripsi }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $catatan->category_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm {{ $catatan->tipe === 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $catatan->tipe === 'pemasukan' ? '+' : '-' }} Rp {{ number_format($catatan->jumlah, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $catatan->media ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $catatan->user->name ?? 'N/A' }}</td> {{-- Tampilkan nama user pencatat --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    {{-- Tombol Edit (Buka Modal Edit) --}}
                                    <button @click.stop="selectedCatatan = {{ $catatan->toJson() }}; editModalOpen = true" {{-- .stop agar tidak trigger klik row --}}
                                        class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                    {{-- Tombol Hapus (Form Submit Langsung) --}}
                                    {{-- Pastikan route destroy sudah ada --}}
                                    {{-- Ganti 'group.catatan.destroy' jika nama route berbeda --}}
                                    <form action="#" {{-- action="{{ route('group.catatan.destroy', ['grup' => $grup->id, 'catatan' => $catatan->id]) }}" --}} method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus catatan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                                    Belum ada catatan keuangan di grup ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Pagination Links --}}
            <div class="mt-4">
                {{ $catatans->links() }} {{-- Pastikan view pagination default atau custom sudah ada --}}
            </div>
        </div>

        {{-- ====================================================== --}}
        {{-- MODAL SECTION --}}
        {{-- ====================================================== --}}

        {{-- Modal Tambah Catatan --}}
        <div x-show="createModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                {{-- Background overlay --}}
                <div x-show="createModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="createModalOpen = false"></div>

                {{-- Modal panel --}}
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="createModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Tambah Catatan Grup Baru
                                </h3>
                                <div class="mt-4">
                                    {{-- FORM CREATE --}}
                                    {{-- Pastikan route store sudah ada --}}
                                    {{-- Ganti 'group.catatan.store' jika nama route berbeda --}}
                                    <form action="{{ route('group.catatan.store', $grup) }}" method="POST">
                                        @csrf
                                        {{-- Include partial form, kirim objek Catatan KOSONG dan data $categories --}}
                                        @include('group::catatan._form', [
                                            'catatan' => new \App\Models\GrupCatatan(), 
                                            'categories' => $categories,
                                            // 'rencanaGroups' => $rencanaGroups ?? collect() // Kirim jika ada
                                        ])

                                        <div class="mt-5 sm:mt-6 sm:flex sm:flex-row-reverse">
                                            {{-- Tombol Simpan & Batal --}}
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Edit Catatan --}}
        <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title-edit" role="dialog" aria-modal="true">
            {{-- Sama seperti modal create, tapi action form ke route update --}}
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="editModalOpen" x-transition...></div> {{-- Background overlay --}}
                <span ...></span> {{-- Trick align vertical --}}
                <div x-show="editModalOpen" x-transition...> {{-- Modal panel --}}
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 ...>Edit Catatan Grup</h3>
                        <div class="mt-4">
                            {{-- FORM EDIT --}}
                            {{-- Ganti 'group.catatan.update' jika nama route berbeda --}}
                            {{-- Bind :action agar dinamis berdasarkan selectedCatatan --}}
                            <<form x-show="selectedCatatan" :action="`/grup/{{ $grup->id }}/catatan/${selectedCatatan.id}`" method="POST">
                                @csrf
                                @method('PUT')
                                {{-- Include partial form, perlu cara pass data selectedCatatan --}}
                                {{-- INI BAGIAN YANG RUMIT dengan Blade @include murni --}}
                                {{-- Kamu perlu mengisi nilai input menggunakan JavaScript (fungsi fillFormData) --}}
                                {{-- Daripada mencoba pass `selectedCatatan` ke @include --}}
                                @include('group::catatan._form', [
                                    'catatan' => new \App\Models\GrupCatatan(), // Kirim objek KOSONG, biarkan JS yg mengisi
                                    'categories' => $categories,
                                    // 'rencanaGroups' => $rencanaGroups ?? collect()
                                ])

                                <div class="mt-5 sm:mt-6 sm:flex sm:flex-row-reverse">
                                {{-- Tombol Simpan Perubahan & Batal --}}
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Lihat Detail Catatan --}}
        <div x-show="showModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title-show" role="dialog" aria-modal="true">
            {{-- Mirip modal edit, tapi hanya menampilkan detail (read-only) --}}
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showModalOpen" x-transition...></div> {{-- Background overlay --}}
                <span ...></span> {{-- Trick align vertical --}}
                <div x-show="showModalOpen" x-transition...> {{-- Modal panel --}}
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 ...>Detail Catatan Grup</h3>
                        <div class="mt-4 space-y-2 text-sm" x-show="selectedCatatan">
                            <p><strong>Deskripsi:</strong> <span x-text="selectedCatatan?.deskripsi"></span></p>
                            <p><strong>Jumlah:</strong> <span x-text="selectedCatatan ? (selectedCatatan.category?.tipe === 'pemasukan' ? '+' : '-') + ' Rp ' + Number(selectedCatatan.jumlah).toLocaleString('id-ID') : ''"></span></p>
                            <p><strong>Kategori:</strong> <span x-text="selectedCatatan?.custom_category || selectedCatatan?.category?.nama || '-'"></span></p>
                            <p><strong>Media:</strong> <span x-text="selectedCatatan?.media || '-'"></span></p>
                            <p><strong>Dicatat oleh:</strong> <span x-text="selectedCatatan?.user?.name || 'N/A'"></span></p>
                            <p><strong>Tanggal:</strong> <span x-text="selectedCatatan ? new Date(selectedCatatan.created_at).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short'}) : ''"></span></p>
                            {{-- Tambah detail lain jika perlu --}}
                        </div>
                        <div class="mt-5 sm:mt-6">
                            <button @click="showModalOpen = false; selectedCatatan = null" type="button" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Filter (Placeholder) --}}
        <div x-show="filterModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title-filter" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="filterModalOpen" x-transition...></div> {{-- Background overlay --}}
                <span ...></span> {{-- Trick align vertical --}}
                <div x-show="filterModalOpen" x-transition...> {{-- Modal panel --}}
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 ...>Filter Catatan Grup</h3>
                        <div class="mt-4">
                            {{-- FORM FILTER --}}
                            <form action="{{ route('group.catatan.index', $grup) }}" method="GET">
                                {{-- Tambahkan input filter di sini (Tipe, Kategori, Rentang Tanggal Custom) --}}
                                <p class="text-gray-600 text-sm">Form filter (tipe, kategori, dll) akan ada di sini.</p>

                                <div class="mt-5 sm:mt-6 sm:flex sm:flex-row-reverse">
                                    <button type="submit" ...>Terapkan Filter</button>
                                    <button @click="filterModalOpen = false" type="button" ...>Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div> {{-- Penutup div x-data --}}

</x-group::layout>