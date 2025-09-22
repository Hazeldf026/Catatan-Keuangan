<x-layout>
    <x-slot:title>
        Dashboard Catatan Keuangan
    </x-slot:title>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white"> Dashboard</h1>
        <a href="{{ route('catatan.create') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            + Tambah Catatan
        </a>
    </div>

    @if (session('success'))
        <div id="alert-3" class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <span class="sr-only">Info</span>
            <div class="ms-3 text-sm font-medium">{{ session('success') }}</div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#alert-3" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
            </button>
        </div>
    @endif
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-blue-500 text-white p-6 rounded-lg shadow-md flex justify-between items-center">
            <div>
                <h3 class="text-xl font-bold">Saldo Akhir</h3>
                <p class="text-4xl font-extrabold mt-2">Rp {{ number_format($saldoAkhir, 2, ',', '.') }}</p>
            </div>
            <svg class="w-12 h-12 text-blue-100 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM5.5 8a.5.5 0 01.5-.5h2a.5.5 0 010 1h-2a.5.5 0 01-.5-.5zM10 10.5a.5.5 0 01.5-.5h2a.5.5 0 010 1h-2a.5.5 0 01-.5-.5zM14.5 13a.5.5 0 01.5-.5h-2a.5.5 0 010 1h2a.5.5 0 01-.5-.5z"/>
            </svg>
        </div>
        <div class="bg-green-500 text-white p-6 rounded-lg shadow-md flex justify-between items-center">
            <div>
                <h3 class="text-xl font-bold">Total Pemasukan</h3>
                <p class="text-4xl font-extrabold mt-2">Rp {{ number_format($totalPemasukan, 2, ',', '.') }}</p>
            </div>
            <svg class="w-12 h-12 text-green-100 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM5 9a1 1 0 00-1 1v2a1 1 0 001 1h10a1 1 0 001-1v-2a1 1 0 00-1-1H5z"/>
            </svg>
        </div>
        <div class="bg-red-500 text-white p-6 rounded-lg shadow-md flex justify-between items-center">
            <div>
                <h3 class="text-xl font-bold">Total Pengeluaran</h3>
                <p class="text-4xl font-extrabold mt-2">Rp {{ number_format($totalPengeluaran, 2, ',', '.') }}</p>
            </div>
            <svg class="w-12 h-12 text-red-100 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM5 9a1 1 0 00-1 1v2a1 1 0 001 1h10a1 1 0 001-1v-2a1 1 0 00-1-1H5z"/>
            </svg>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="md:col-span-1 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md flex items-center justify-center">
            <canvas id="balanceChart" class="max-w-xs"></canvas>
        </div>
        <div class="md:col-span-2 relative overflow-x-auto shadow-md sm:rounded-lg">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Riwayat Transaksi</h2>
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Detail</th>
                        <th scope="col" class="px-6 py-3">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-right">Jumlah</th>
                        <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($catatans as $catatan)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 cursor-pointer" onclick="window.location='{{ route('catatan.show', $catatan) }}'">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                @if ($catatan->category && $catatan->category->nama === 'Lainnya...')
                                    {{ $catatan->custom_category ?? 'Tidak ada kategori' }}
                                @else
                                    {{ $catatan->category->nama ?? '-' }}
                                @endif
                                @if ($catatan->deskripsi)
                                    <span class="block text-xs font-normal text-gray-500">{{ $catatan->deskripsi }}</span>
                                @endif
                            </th>
                            <td class="px-6 py-4">{{ $catatan->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-right font-semibold {{ $catatan->category->tipe == 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $catatan->category->tipe == 'pemasukan' ? '+' : '-' }} Rp {{ number_format($catatan->jumlah, 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('catatan.edit', $catatan) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                    <form action="{{ route('catatan.destroy', $catatan) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="bg-white border-b dark:bg-gray-800">
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                Belum ada data.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">
                {{ $catatans->links() }}
            </div>
        </div>
    </div>

    <form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Logout</button>
    </form>
</x-layout>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('balanceChart');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Pemasukan', 'Pengeluaran'],
            datasets: [{
                label: 'Perbandingan',
                data: [{{ $totalPemasukan }}, {{ $totalPengeluaran }}],
                backgroundColor: [
                    'rgb(59, 130, 246)',
                    'rgb(239, 68, 68)'
                ],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': Rp ' + new Intl.NumberFormat('id-ID', { maximumFractionDigits: 2 }).format(tooltipItem.raw);
                        }
                    }
                }
            }
        }
    });
</script>