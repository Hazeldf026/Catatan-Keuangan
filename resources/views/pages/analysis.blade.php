<x-layout>
    <x-slot:title>
        Analisis | Credix
    </x-slot:title>

    <div class="container mx-auto p-4 sm:p-6 lg:p-8">

        {{-- [UI BARU] Bagian 1: 3 Kartu Ringkasan Atas dengan Ikon --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            {{-- Kartu Pemasukan --}}
            <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-md flex items-center space-x-4">
                <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 2a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1M2 5h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Zm8 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pemasukan</h4>
                    <p id="summary-pemasukan" class="text-xl font-bold text-gray-900 dark:text-white">Memuat...</p>
                </div>
            </div>
            {{-- Kartu Pengeluaran --}}
            <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-md flex items-center space-x-4">
                <div class="bg-red-100 dark:bg-red-900 p-3 rounded-full">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pengeluaran</h4>
                    <p id="summary-pengeluaran" class="text-xl font-bold text-gray-900 dark:text-white">Memuat...</p>
                </div>
            </div>
            {{-- Kartu Saldo --}}
            <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-md flex items-center space-x-4">
                <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Saldo Saat Ini</h4>
                    <p id="summary-saldo" class="text-xl font-bold text-gray-900 dark:text-white">Memuat...</p>
                </div>
            </div>
        </div>

        {{-- Bagian 2: Chart Garis dan Pie --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            {{-- Chart Garis (Lebih Lebar) --}}
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Tren Keuangan</h3>
                    
                    <div id="scaleSelector" 
                        x-data="{ activeScale: '3days' }" 
                        class="inline-flex rounded-md shadow-sm" 
                        role="group">

                        {{-- Tombol 3 Hari --}}
                        <button type="button" 
                                @click="activeScale = '3days'; fetchData('3days')"
                                :class="{
                                    'bg-blue-600 text-white border-blue-600 dark:bg-blue-500 dark:border-blue-500': activeScale === '3days',
                                    'bg-white text-gray-900 border-gray-200 hover:bg-gray-100 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600': activeScale !== '3days'
                                }"
                                class="px-4 py-2 text-sm font-medium border rounded-l-lg transition-colors duration-150">
                            3 Hari
                        </button>

                        {{-- Tombol 5 Hari --}}
                        <button type="button" 
                                @click="activeScale = '5days'; fetchData('5days')"
                                :class="{
                                    'bg-blue-600 text-white border-blue-600 dark:bg-blue-500 dark:border-blue-500': activeScale === '5days',
                                    'bg-white text-gray-900 border-gray-200 hover:bg-gray-100 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600': activeScale !== '5days'
                                }"
                                class="px-4 py-2 text-sm font-medium border-t border-b transition-colors duration-150">
                            5 Hari
                        </button>

                        {{-- Tombol Minggu --}}
                        <button type="button" 
                                @click="activeScale = 'weekly'; fetchData('weekly')"
                                :class="{
                                    'bg-blue-600 text-white border-blue-600 dark:bg-blue-500 dark:border-blue-500': activeScale === 'weekly',
                                    'bg-white text-gray-900 border-gray-200 hover:bg-gray-100 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600': activeScale !== 'weekly'
                                }"
                                class="px-4 py-2 text-sm font-medium border transition-colors duration-150">
                            Minggu
                        </button>

                        {{-- Tombol Bulan --}}
                        <button type="button" 
                                @click="activeScale = 'monthly'; fetchData('monthly')"
                                :class="{
                                    'bg-blue-600 text-white border-blue-600 dark:bg-blue-500 dark:border-blue-500': activeScale === 'monthly',
                                    'bg-white text-gray-900 border-gray-200 hover:bg-gray-100 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600': activeScale !== 'monthly'
                                }"
                                class="px-4 py-2 text-sm font-medium border-t border-b transition-colors duration-150">
                            Bulan
                        </button>

                        {{-- Tombol Tahun --}}
                        <button type="button" 
                                @click="activeScale = 'yearly'; fetchData('yearly')"
                                :class="{
                                    'bg-blue-600 text-white border-blue-600 dark:bg-blue-500 dark:border-blue-500': activeScale === 'yearly',
                                    'bg-white text-gray-900 border-gray-200 hover:bg-gray-100 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600': activeScale !== 'yearly'
                                }"
                                class="px-4 py-2 text-sm font-medium border rounded-r-lg transition-colors duration-150">
                            Tahun
                        </button>

                    </div>

                </div>
                <div class="h-64">
                    {{-- [PERUBAHAN] ID Canvas diubah menjadi 'lineChart' --}}
                    <canvas id="lineChart"></canvas>
                </div>
            </div>

            {{-- Chart Pie --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Komposisi Keuangan</h3>
                <div class="h-64">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Bagian 3: Kartu Data Analisis --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Analisis Keuangan</h3>
            <div id="analysis-data-container" class="space-y-8">
                <div class="text-center text-gray-500 dark:text-gray-400">Memuat data analisis...</div>
            </div>
        </div>
    </div>

    {{-- [LOGIKA LAMA] SCRIPT UNTUK LINE CHART YANG SUDAH KITA BUAT --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@^4"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const chartTextColor = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'rgba(255, 255, 255, 0.7)' : 'rgba(0, 0, 0, 0.7)';
        const chartBorderColor = window.matchMedia('(prefers-color-scheme: dark)').matches ? '#1f2937' : '#ffffff';
        const colorUp = 'rgba(34, 197, 94, 0.8)';
        const colorDown = 'rgba(239, 68, 68, 0.8)';

        const lineCtx = document.getElementById('lineChart').getContext('2d');
        const lineChart = new Chart(lineCtx, {
            type: 'line',
            data: {
                datasets: [{
                    label: 'Pergerakan Saldo',
                    data: [],
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    borderWidth: 2,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    fill: true,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'day',
                            tooltipFormat: 'dd MMM yyyy'
                        },
                        ticks: { 
                            color: chartTextColor 
                        }
                    },
                    y: {
                        beginAtZero: false,
                        ticks: { 
                            color: chartTextColor,
                            callback: function(value, index, values) {
                                return new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                },
                plugins: {
                    legend: { labels: { color: chartTextColor } },
                    tooltip: {}
                }
            }
        });

        const pieCtx = document.getElementById('pieChart').getContext('2d');
        const pieChart = new Chart(pieCtx, { type: 'pie', data: { labels: ['Pemasukan', 'Pengeluaran'], datasets: [{ data: [0, 0], backgroundColor: [colorUp, colorDown], borderColor: chartBorderColor, borderWidth: 2 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top', labels: { color: chartTextColor } } } } });

        const analysisContainer = document.getElementById('analysis-data-container');
        let lifetimeDataLoaded = false;

        async function fetchData(scale) {
            try {
                if (!lifetimeDataLoaded) setLoadingState();
                
                const response = await fetch(`{{ route('analysis.data') }}?scale=${scale}`);
                if (!response.ok) throw new Error(`Server error: ${response.status}`);
                const data = await response.json();

                if (!lifetimeDataLoaded) {
                    const lifetime = data.lifetimeData;
                    document.getElementById('summary-pemasukan').textContent = 'Rp ' + lifetime.summary.totalPemasukan;
                    document.getElementById('summary-pengeluaran').textContent = 'Rp ' + lifetime.summary.totalPengeluaran;
                    document.getElementById('summary-saldo').textContent = 'Rp ' + lifetime.summary.saldo;
                    pieChart.data.datasets[0].data = [lifetime.pieChart.pemasukan, lifetime.pieChart.pengeluaran];
                    pieChart.update();
                    renderAnalysisData(lifetime.analysisData);
                    lifetimeDataLoaded = true;
                }

                const lineData = data.lineChartData;

                if (lineData && lineData.length > 0) {
                    lineChart.options.scales.x.bounds = 'data';
                    lineChart.options.scales.x.ticks.source = 'data';
                } else {
                    lineChart.options.scales.x.bounds = 'ticks';
                    lineChart.options.scales.x.ticks.source = 'auto';
                }

                lineChart.data.datasets[0].data = lineData;
                lineChart.options.scales.x.time.unit = (scale === 'yearly') ? 'month' : 'day';
                lineChart.update();

            } catch (error) {
                console.error('Failed to fetch analysis data:', error);
                analysisContainer.innerHTML = `<p class="text-red-500 text-center font-semibold">Gagal memuat data.</p>`;
            }
        }
        
        function setLoadingState() {
            document.getElementById('summary-pemasukan').textContent = 'Memuat...';
            document.getElementById('summary-pengeluaran').textContent = 'Memuat...';
            document.getElementById('summary-saldo').textContent = 'Memuat...';
            analysisContainer.innerHTML = '<div class="text-center text-gray-500 dark:text-gray-400">Memuat data analisis...</div>';
        }

        function renderAnalysisData(analysisData) {
            analysisContainer.innerHTML = '';
            const groups = { ringkasanUmum: 'Ringkasan Umum', rasioPertumbuhan: 'Rasio & Pertumbuhan', analisisTransaksi: 'Analisis Transaksi', harianKonsistensi: 'Analisis Harian & Konsistensi' };
            for (const groupKey in groups) {
                const groupTitle = groups[groupKey];
                const groupData = analysisData[groupKey];
                let groupHtml = `<div><h4 class="text-md font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">${groupTitle}</h4><div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-x-4 gap-y-5">`;
                groupData.forEach(item => {
                    groupHtml += `<div><p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">${item.label}</p><p class="text-sm sm:text-base font-bold text-gray-900 dark:text-white">${item.value}</p></div>`;
                });
                groupHtml += `</div></div>`;
                analysisContainer.innerHTML += groupHtml;
            }
        }

        window.fetchData = fetchData;

        fetchData('3days');
    });
    </script>
</x-layout>