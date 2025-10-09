<x-layout>
    <x-slot:title>
        Halaman Analisis | Credix
    </x-slot:title>

    <div class="container mx-auto p-4 sm:p-6 lg:p-8">

        {{-- Judul Halaman --}}
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-6">
            Analisis Keuangan
        </h1>

        {{-- Bagian 1: 3 Kartu Ringkasan Atas (Data Lifetime) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-md flex items-center space-x-4">
                <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01"></path></svg>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pemasukan</h4>
                    <p id="summary-pemasukan" class="text-xl font-bold text-gray-900 dark:text-white">Memuat...</p>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-md flex items-center space-x-4">
                <div class="bg-red-100 dark:bg-red-900 p-3 rounded-full">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pengeluaran</h4>
                    <p id="summary-pengeluaran" class="text-xl font-bold text-gray-900 dark:text-white">Memuat...</p>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-md flex items-center space-x-4">
                <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0 0l-3 4m6 2l3 9"></path></svg>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Saldo Saat Ini</h4>
                    <p id="summary-saldo" class="text-xl font-bold text-gray-900 dark:text-white">Memuat...</p>
                </div>
            </div>
        </div>

        {{-- Bagian 2: Chart Batang (Dinamis) dan Pie (Lifetime) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Tren Keuangan</h3>
                    <div id="scaleSelector" class="inline-flex rounded-md shadow-sm mt-2 sm:mt-0" role="group">
                        <button type="button" data-scale="3days" class="scale-button px-4 py-2 text-sm font-medium border rounded-l-lg">3 Hari</button>
                        <button type="button" data-scale="5days" class="scale-button px-4 py-2 text-sm font-medium border-t border-b">5 Hari</button>
                        <button type="button" data-scale="weekly" class="scale-button px-4 py-2 text-sm font-medium border">Minggu</button>
                        <button type="button" data-scale="monthly" class="scale-button px-4 py-2 text-sm font-medium border-t border-b">Bulan</button>
                        <button type="button" data-scale="yearly" class="scale-button px-4 py-2 text-sm font-medium border rounded-r-lg">Tahun</button>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="barChart"></canvas>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Komposisi Keuangan (Total)</h3>
                <div class="h-64">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Bagian 3: Kartu Data Analisis (Lifetime) --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Data Analisis (Semua Waktu)</h3>
            <div id="analysis-data-container" class="space-y-8">
                <div class="text-center text-gray-500 dark:text-gray-400">Memuat data analisis...</div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartTextColor = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'rgba(255, 255, 255, 0.7)' : 'rgba(0, 0, 0, 0.7)';
            const chartBorderColor = window.matchMedia('(prefers-color-scheme: dark)').matches ? '#1f2937' : '#ffffff';

            const barCtx = document.getElementById('barChart').getContext('2d');
            const barChart = new Chart(barCtx, { type: 'bar', data: { labels: [], datasets: [{ label: 'Selisih Harian', data: [] }] }, options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { color: chartTextColor } }, x: { ticks: { color: chartTextColor } } }, plugins: { legend: { labels: { color: chartTextColor } } } } });
            
            const pieCtx = document.getElementById('pieChart').getContext('2d');
            const pieChart = new Chart(pieCtx, { type: 'pie', data: { labels: ['Pemasukan', 'Pengeluaran'], datasets: [{ data: [0, 0], backgroundColor: ['rgba(34, 197, 94, 0.8)', 'rgba(239, 68, 68, 0.8)'], borderColor: chartBorderColor, borderWidth: 2 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top', labels: { color: chartTextColor } } } } });

            const scaleSelectorContainer = document.getElementById('scaleSelector');
            const scaleButtons = scaleSelectorContainer.querySelectorAll('.scale-button');
            const analysisContainer = document.getElementById('analysis-data-container');
            
            let lifetimeDataLoaded = false;

            function setActiveButton(activeButton) {
                scaleButtons.forEach(button => { button.classList.remove('bg-blue-600', 'text-white', 'dark:bg-blue-500'); button.classList.add('bg-white', 'text-gray-900', 'dark:bg-gray-700', 'dark:text-white', 'border-gray-200', 'dark:border-gray-600', 'hover:bg-gray-100', 'dark:hover:bg-gray-600'); });
                activeButton.classList.remove('bg-white', 'text-gray-900', 'dark:bg-gray-700', 'dark:text-white', 'border-gray-200', 'dark:border-gray-600', 'hover:bg-gray-100', 'dark:hover:bg-gray-600');
                activeButton.classList.add('bg-blue-600', 'text-white', 'dark:bg-blue-500');
            }

            async function fetchData(scale) {
                try {
                    if(!lifetimeDataLoaded) {
                        setLoadingState();
                    } else {
                        barChart.data.datasets[0].label = 'Memuat...';
                        barChart.update();
                    }

                    const response = await fetch(`{{ route('analysis.data') }}?scale=${scale}`);
                    if (!response.ok) throw new Error(`Server error: ${response.status}`);
                    const data = await response.json();

                    // Update data LIFETIME (hanya sekali)
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

                    // Update data BAR CHART (setiap kali)
                    const barData = data.barChartData;
                    barChart.data.labels = barData.labels;
                    barChart.data.datasets[0].data = barData.data;
                    barChart.data.datasets[0].label = 'Selisih Harian';
                    barChart.data.datasets[0].backgroundColor = barData.data.map(v => v >= 0 ? 'rgba(34, 197, 94, 0.8)' : 'rgba(239, 68, 68, 0.8)');
                    barChart.update();

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

            scaleSelectorContainer.addEventListener('click', (event) => {
                const button = event.target.closest('.scale-button');
                if (button) {
                    setActiveButton(button);
                    fetchData(button.dataset.scale);
                }
            });

            function initializePage() {
                const initialScale = '3days';
                const initialActiveButton = scaleSelectorContainer.querySelector(`[data-scale="${initialScale}"]`);
                if (initialActiveButton) {
                    setActiveButton(initialActiveButton);
                    fetchData(initialScale);
                }
            }

            initializePage();
        });
    </script>
</x-layout>