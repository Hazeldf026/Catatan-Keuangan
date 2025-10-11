<x-layout>
    <x-slot:title>
        Analisis | Credix
    </x-slot:title>

    {{-- Komponen utama Alpine.js --}}
    <div 
        x-data="analysisPage(
            '{{ $todayDate }}', 
            '{{ $firstTransactionDate }}'
        )"
        x-init="initCharts()"
        class="container mx-auto p-4 sm:p-6 lg:p-8">

        {{-- Judul Halaman --}}
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-6">Analisis Keuangan</h1>

        {{-- ... HTML untuk Kartu Ringkasan, Pie Chart, dan Analisis tidak berubah ... --}}
        {{-- (Saya persingkat agar tidak terlalu panjang, cukup salin seluruh file dari bawah) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-md flex items-center space-x-4">
                <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full"><svg class="w-6 h-6 text-green-600 dark:text-green-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 2a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1M2 5h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Zm8 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z"/></svg></div>
                <div><h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pemasukan</h4><p id="summary-pemasukan" class="text-xl font-bold text-gray-900 dark:text-white">Memuat...</p></div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-md flex items-center space-x-4">
                <div class="bg-red-100 dark:bg-red-900 p-3 rounded-full"><svg class="w-6 h-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></div>
                <div><h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pengeluaran</h4><p id="summary-pengeluaran" class="text-xl font-bold text-gray-900 dark:text-white">Memuat...</p></div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-md flex items-center space-x-4">
                <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full"><svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg></div>
                <div><h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Saldo Saat Ini</h4><p id="summary-saldo" class="text-xl font-bold text-gray-900 dark:text-white">Memuat...</p></div>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Tren Keuangan</h3>
                    <div class="inline-flex rounded-md shadow-sm mt-2 sm:mt-0" role="group">
                        <button @click="setScale('weekly')" :class="activeScale === 'weekly' ? activeClass : inactiveClass" type="button" class="px-4 py-2 text-sm font-medium border rounded-l-lg transition-colors duration-150">Minggu</button>
                        <button @click="setScale('monthly')" :class="activeScale === 'monthly' ? activeClass : inactiveClass" type="button" class="px-4 py-2 text-sm font-medium border-t border-b transition-colors duration-150">Bulan</button>
                        <button @click="setScale('yearly')" :class="activeScale === 'yearly' ? activeClass : inactiveClass" type="button" class="px-4 py-2 text-sm font-medium border rounded-r-lg transition-colors duration-150">Tahun</button>
                    </div>
                </div>
                <div x-show="activeScale" class="flex items-center justify-center space-x-4 my-4">
                    <button @click="navigate(-1)" :disabled="isPrevDisabled" class="p-2 rounded-full text-gray-600 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-300 dark:hover:bg-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
                    <span x-text="displayLabel" class="text-lg font-semibold text-gray-700 dark:text-gray-300 w-48 text-center"></span>
                    <button @click="navigate(1)" :disabled="isNextDisabled" class="p-2 rounded-full text-gray-600 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-300 dark:hover:bg-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
                </div>
                <div class="h-64"><canvas id="lineChart"></canvas></div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Komposisi Keuangan</h3>
                <div class="h-64"><canvas id="pieChart"></canvas></div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Analisis Keuangan</h3>
            <div id="analysis-data-container" class="space-y-8"></div>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@^4"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/date-fns@3.6.0/cdn.min.js"></script>

    <script>
        // [PERBAIKAN KUNCI] Deklarasikan variabel chart di luar Alpine
        let lineChart = null;
        let pieChart = null;

        function analysisPage(todayDate, firstTransactionDate) {
            return {
                activeScale: 'monthly',
                currentDate: todayDate,
                displayLabel: '',
                today: new Date(todayDate),
                firstDate: firstTransactionDate ? new Date(firstTransactionDate) : new Date(todayDate),
                lifetimeDataLoaded: false,
                activeClass: 'bg-blue-600 text-white border-blue-600 dark:bg-blue-500 dark:border-blue-500',
                inactiveClass: 'bg-white text-gray-900 border-gray-200 hover:bg-gray-100 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600',
                
                // initCharts akan dipanggil oleh x-init
                initCharts() {
                    initLineChart();
                    initPieChart();
                    this.updateLabel();
                    this.fetchData();
                },

                setScale(newScale) {
                    this.activeScale = newScale;
                    this.currentDate = todayDate;
                    this.updateLabel();
                    this.fetchData();
                },

                navigate(direction) {
                    let d = new Date(this.currentDate);
                    if (this.activeScale === 'weekly') d.setDate(d.getDate() + (7 * direction));
                    if (this.activeScale === 'monthly') d.setMonth(d.getMonth() + direction);
                    if (this.activeScale === 'yearly') d.setFullYear(d.getFullYear() + direction);
                    this.currentDate = d.toISOString().split('T')[0];
                    this.updateLabel();
                    this.fetchData();
                },

                updateLabel() {
                    const d = new Date(this.currentDate);
                    if (this.activeScale === 'weekly') {
                        const start = dateFns.startOfWeek(d, { weekStartsOn: 1 });
                        const end = dateFns.endOfWeek(d, { weekStartsOn: 1 });
                        this.displayLabel = `${dateFns.format(start, 'd MMM')} - ${dateFns.format(end, 'd MMM yyyy')}`;
                    }
                    if (this.activeScale === 'monthly') {
                        this.displayLabel = d.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
                    }
                    if (this.activeScale === 'yearly') {
                        this.displayLabel = d.toLocaleDateString('id-ID', { year: 'numeric' });
                    }
                },

                async fetchData() {
                    try {
                        const analysisContainer = document.getElementById('analysis-data-container');
                        if (!this.lifetimeDataLoaded) {
                           document.getElementById('summary-pemasukan').textContent = 'Memuat...';
                           document.getElementById('summary-pengeluaran').textContent = 'Memuat...';
                           document.getElementById('summary-saldo').textContent = 'Memuat...';
                           analysisContainer.innerHTML = '<div class="text-center text-gray-500 dark:text-gray-400">Memuat data analisis...</div>';
                        }
                        
                        const response = await fetch(`{{ route('analysis.data') }}?scale=${this.activeScale}&date=${this.currentDate}`);
                        if (!response.ok) throw new Error('Server error');
                        const data = await response.json();

                        if (!this.lifetimeDataLoaded) {
                            const lifetime = data.lifetimeData;
                            document.getElementById('summary-pemasukan').textContent = 'Rp ' + lifetime.summary.totalPemasukan;
                            document.getElementById('summary-pengeluaran').textContent = 'Rp ' + lifetime.summary.totalPengeluaran;
                            document.getElementById('summary-saldo').textContent = 'Rp ' + lifetime.summary.saldo;
                            pieChart.data.datasets[0].data = [lifetime.pieChart.pemasukan, lifetime.pieChart.pengeluaran];
                            pieChart.update();
                            if (lifetime.analysisData) {
                                renderAnalysisData(lifetime.analysisData, analysisContainer);
                            }
                            this.lifetimeDataLoaded = true;
                        }

                        const lineData = data.lineChartData;
                        lineChart.options.scales.x.time.unit = (this.activeScale === 'yearly') ? 'month' : 'day';
                        lineChart.data.datasets[0].data = lineData;
                        lineChart.update();

                    } catch (error) {
                        console.error('Failed to fetch analysis data:', error);
                        document.getElementById('analysis-data-container').innerHTML = `<p class="text-red-500 text-center font-semibold">Gagal memuat data.</p>`;
                    }
                },
                
                get isPrevDisabled() {
                    if (!this.firstDate) return true;
                    const d = new Date(this.currentDate);
                    if (this.activeScale === 'weekly') return dateFns.isSameWeek(d, this.firstDate, { weekStartsOn: 1 });
                    if (this.activeScale === 'monthly') return dateFns.isSameMonth(d, this.firstDate);
                    if (this.activeScale === 'yearly') return dateFns.isSameYear(d, this.firstDate);
                    return true;
                },

                get isNextDisabled() {
                    const d = new Date(this.currentDate);
                    if (this.activeScale === 'weekly') return dateFns.isSameWeek(d, this.today, { weekStartsOn: 1 });
                    if (this.activeScale === 'monthly') return dateFns.isSameMonth(d, this.today);
                    if (this.activeScale === 'yearly') return dateFns.isSameYear(d, this.today);
                    return true;
                }
            };
        }

        // --- FUNGSI-FUNGSI HELPER DI LUAR ALPINE ---
        const chartTextColor = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'rgba(255, 255, 255, 0.7)' : 'rgba(0, 0, 0, 0.7)';
        const chartBorderColor = window.matchMedia('(prefers-color-scheme: dark)').matches ? '#1f2937' : '#ffffff';

        function initLineChart() {
            const lineCtx = document.getElementById('lineChart').getContext('2d');
            lineChart = new Chart(lineCtx, {
                type: 'line',
                data: { datasets: [{ label: 'Tren Keuangan', data: [], borderColor: 'rgb(59, 130, 246)', backgroundColor: 'rgba(59, 130, 246, 0.2)', borderWidth: 2, pointRadius: 3, pointHoverRadius: 5, fill: true, tension: 0.1 }] },
                options: { responsive: true, maintainAspectRatio: false, scales: { x: { type: 'time', time: { unit: 'day', tooltipFormat: 'dd MMM yyyy' }, ticks: { color: chartTextColor, source: 'auto' }, bounds: 'ticks' }, y: { beginAtZero: false, ticks: { color: chartTextColor, callback: (value) => new Intl.NumberFormat('id-ID').format(value) } } }, plugins: { legend: { labels: { color: chartTextColor } }, tooltip: {} } }
            });
        }

        function initPieChart() {
            const pieCtx = document.getElementById('pieChart').getContext('2d');
            pieChart = new Chart(pieCtx, {
                type: 'pie',
                data: { labels: ['Pemasukan', 'Pengeluaran'], datasets: [{ data: [0, 0], backgroundColor: ['rgba(34, 197, 94, 0.8)', 'rgba(239, 68, 68, 0.8)'], borderColor: chartBorderColor, borderWidth: 2 }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top', labels: { color: chartTextColor } } } }
            });
        }

        function renderAnalysisData(analysisData, container) {
            container.innerHTML = '';
            const groups = { ringkasanUmum: 'Ringkasan Umum', rasioPertumbuhan: 'Rasio & Pertumbuhan', analisisTransaksi: 'Analisis Transaksi', harianKonsistensi: 'Analisis Harian & Konsistensi' };
            for (const groupKey in groups) {
                if (Object.hasOwnProperty.call(analysisData, groupKey)) {
                    const groupTitle = groups[groupKey];
                    const groupData = analysisData[groupKey];
                    let groupHtml = `<div><h4 class="text-md font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">${groupTitle}</h4><div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-x-4 gap-y-5">`;
                    groupData.forEach(item => {
                        groupHtml += `<div><p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">${item.label}</p><p class="text-sm sm:text-base font-bold text-gray-900 dark:text-white">${item.value}</p></div>`;
                    });
                    groupHtml += `</div></div>`;
                    container.innerHTML += groupHtml;
                }
            }
        }
    </script>
</x-layout>