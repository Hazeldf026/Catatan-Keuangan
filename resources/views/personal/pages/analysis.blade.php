<x-personal::layout>
    <x-slot:title>
        Analisis | Credix
    </x-slot:title>

    <div 
        x-data="analysisPage(
            '{{ $todayDate }}', 
            '{{ $firstTransactionDate }}'
        )"
        x-init="initCharts()"
        class="container mx-auto p-4 sm:p-6 lg:p-8">

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            {{-- Wallet --}}
            <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-md flex items-center space-x-4">
                <div class="bg-orange-100 dark:bg-orange-900 p-3 rounded-full">
                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
                <div><h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Wallet</h4><p id="summary-wallet" class="text-xl font-bold text-gray-900 dark:text-white">Memuat...</p></div>
            </div>
            {{-- Bank --}}
            <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-md flex items-center space-x-4">
                <div class="bg-indigo-100 dark:bg-indigo-900 p-3 rounded-full"><svg class="w-6 h-6 text-indigo-600 dark:text-indigo-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg></div>
                <div><h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Bank</h4><p id="summary-bank" class="text-xl font-bold text-gray-900 dark:text-white">Memuat...</p></div>
            </div>
            {{-- E-Wallet --}}
            <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-md flex items-center space-x-4">
                <div class="bg-sky-100 dark:bg-sky-900 p-3 rounded-full"><svg class="w-6 h-6 text-sky-600 dark:text-sky-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg></div>
                <div><h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">E-Wallet</h4><p id="summary-e-wallet" class="text-xl font-bold text-gray-900 dark:text-white">Memuat...</p></div>
            </div>
            {{-- Tabungan --}}
            <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-md flex items-center space-x-4">
                <div class="bg-amber-100 dark:bg-amber-900 p-3 rounded-full"><svg class="w-6 h-6 text-amber-600 dark:text-amber-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h12a2 2 0 012 2v6z"></path></svg></div>
                <div><h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tabungan</h4><p id="summary-tabungan" class="text-xl font-bold text-gray-900 dark:text-white">Memuat...</p></div>
            </div>
        </div>

        {{-- Kartu Ringkasan --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-md flex items-center space-x-4">
                <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full"><svg class="w-6 h-6 text-green-600 dark:text-green-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 2a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1M2 5h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Zm8 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z"/></svg></div>
                <div><h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pemasukan</h4><p id="summary-pemasukan" class="text-xl font-bold text-gray-900 dark:text-white">Memuat...</p></div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-md flex items-center space-x-4">
                <div class="bg-red-100 dark:bg-red-900 p-3 rounded-full"><svg class="w-6 h-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></div>
                <div><h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pengeluaran</h4><p id="summary-pengeluaran" class="text-xl font-bold text-gray-900 dark:text-white">Memuat...</p></div>
            </div>
        </div>

        {{-- Kartu Total Rencana --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-md flex items-center space-x-4">
                <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full"><svg class="w-6 h-6 text-purple-600 dark:text-purple-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg></div>
                <div><h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Dana Rencana</h4><p id="summary-rencana" class="text-xl font-bold text-gray-900 dark:text-white">Memuat...</p></div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-md flex items-center space-x-4">
                <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full"><svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg></div>
                <div><h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Saldo</h4><p id="summary-saldo" class="text-xl font-bold text-gray-900 dark:text-white">Memuat...</p></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            {{-- Line Chart --}}
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

            {{-- Kartu Pie Chart Carousel --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md relative overflow-hidden">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                        <span x-show="activeSlide === 1" x-transition>Komposisi Keuangan</span>
                        <span x-show="activeSlide === 2" x-transition x-cloak>Komposisi Media</span>
                    </h3>
                </div>

                <div class="relative h-64">
                    <div x-show="activeSlide === 1" x-transition class="absolute inset-0 h-64">
                        <canvas id="pieChart"></canvas>
                    </div>
                    
                    <div x-show="activeSlide === 2" x-transition x-cloak class="absolute inset-0 h-64">
                        <canvas id="mediaPieChart"></canvas>
                    </div>
                </div>

                <button @click="activeSlide = (activeSlide === 1) ? totalSlides : activeSlide - 1" 
                        class="absolute left-2 top-1/2 -translate-y-1/2 p-2 rounded-full bg-gray-800/50 text-white hover:bg-gray-800/70 z-10 opacity-70 hover:opacity-100 transition-opacity">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <button @click="activeSlide = (activeSlide === totalSlides) ? 1 : activeSlide + 1" 
                        class="absolute right-2 top-1/2 -translate-y-1/2 p-2 rounded-full bg-gray-800/50 text-white hover:bg-gray-800/70 z-10 opacity-70 hover:opacity-100 transition-opacity">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>

                <div class="absolute bottom-4 left-0 right-0 flex justify-center space-x-2 z-10">
                    <template x-for="i in totalSlides" :key="i">
                        <button 
                            @click="activeSlide = i" 
                            :class="activeSlide === i ? 'bg-blue-600 dark:bg-blue-500' : 'bg-gray-300 dark:bg-gray-600'" 
                            class="w-3 h-3 rounded-full transition-colors"></button>
                    </template>
                </div>
            </div>
        </div>

        {{-- Bagian Analisis Keuangan --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Analisis Keuangan</h3>
            <div id="analysis-data-container" class="space-y-8">
                {{-- Konten dimuat oleh JavaScript --}}
            </div>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@^4"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/date-fns@3.6.0/cdn.min.js"></script>

    <script>
        // Deklarasikan variabel chart di luar Alpine
        let lineChart = null;
        let pieChart = null;
        let mediaPieChart = null; 

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
                
                activeSlide: 1,
                totalSlides: 2, 
                
                initCharts() {
                    initLineChart();
                    initPieChart();
                    initMediaPieChart(); 
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
                            document.getElementById('summary-wallet').textContent = 'Memuat...';
                            document.getElementById('summary-bank').textContent = 'Memuat...';
                            document.getElementById('summary-e-wallet').textContent = 'Memuat...';
                            document.getElementById('summary-tabungan').textContent = 'Memuat...';
                            document.getElementById('summary-rencana').textContent = 'Memuat...';
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
                            document.getElementById('summary-wallet').textContent = 'Rp ' + lifetime.summary.wallet;
                            document.getElementById('summary-bank').textContent = 'Rp ' + lifetime.summary.bank;
                            document.getElementById('summary-e-wallet').textContent = 'Rp ' + lifetime.summary.ewallet;
                            document.getElementById('summary-tabungan').textContent = 'Rp ' + lifetime.summary.tabungan;
                            document.getElementById('summary-rencana').textContent = 'Rp ' + lifetime.summary.totalRencana;

                            
                            pieChart.data.datasets[0].data = [lifetime.pieChart.pemasukan, lifetime.pieChart.pengeluaran];
                            pieChart.update();

                            if (lifetime.mediaPieChart && lifetime.mediaPieChart.data.length > 0) {
                                mediaPieChart.data.labels = lifetime.mediaPieChart.labels;
                                mediaPieChart.data.datasets[0].data = lifetime.mediaPieChart.data;
                                mediaPieChart.data.datasets[0].backgroundColor = lifetime.mediaPieChart.colors; 
                                mediaPieChart.options.plugins.tooltip.enabled = true;
                                mediaPieChart.update();
                            } else {
                                mediaPieChart.data.labels = ['Tidak ada data media'];
                                mediaPieChart.data.datasets[0].data = [1];
                                mediaPieChart.data.datasets[0].backgroundColor = ['rgba(200, 200, 200, 0.8)'];
                                mediaPieChart.options.plugins.tooltip.enabled = false;
                                mediaPieChart.update();
                            }

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
        const chartTextColor = document.documentElement.classList.contains('dark') ? 'rgba(255, 255, 255, 0.7)' : 'rgba(0, 0, 0, 0.7)'; 
        const chartBorderColor = document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff';

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

        function initMediaPieChart() {
            const mediaPieCtx = document.getElementById('mediaPieChart').getContext('2d');
            mediaPieChart = new Chart(mediaPieCtx, {
                type: 'pie',
                data: { 
                    labels: [],
                    datasets: [{ 
                        data: [], 
                        backgroundColor: [], 
                        borderColor: chartBorderColor, 
                        borderWidth: 2 
                    }] 
                },
                options: { 
                    responsive: true, 
                    maintainAspectRatio: false, 
                    plugins: { 
                        legend: { position: 'top', labels: { color: chartTextColor } },
                        tooltip: { enabled: true }
                    } 
                }
            });
        }

        // Fungsi renderAnalysisData
        function renderAnalysisData(analysisData, container) {
            container.innerHTML = ''; 
            const groups = { 
                ringkasanUmum: 'Ringkasan Umum', 
                rasioPertumbuhan: 'Rasio & Pertumbuhan', 
                analisisTransaksi: 'Analisis Transaksi', 
                harianKonsistensi: 'Analisis Harian & Konsistensi' 
            };
            
            for (const groupKey in groups) {
                if (Object.hasOwnProperty.call(analysisData, groupKey)) {
                    const groupTitle = groups[groupKey];
                    const groupData = analysisData[groupKey];
                    
                    let groupHtml = `<div>
                                        <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-4">${groupTitle}</h4>
                                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">`; 

                    groupData.forEach(item => {
                        groupHtml += `
                        <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 truncate" title="${item.label}">${item.label}</p>
                            <p class="text-base sm:text-lg font-bold text-gray-900 dark:text-white">${item.value}</p>
                        </div>
                        `;
                    });

                    groupHtml += `</div></div>`; 
                    container.innerHTML += groupHtml; 
                }
            }
        }
    </script>
</x-personal::layout>