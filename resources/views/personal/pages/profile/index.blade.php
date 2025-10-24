{{-- resources/views/personal/pages/profile/index.blade.php --}}

<x-personal::settings-layout title="Profil Saya">

    {{-- [DIUBAH] x-data sekarang hanya memanggil fungsi tanpa parameter --}}
    <div x-data="profilePage()"
         x-init="initChart()">

        {{-- Kartu Profil Utama (tidak berubah) --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mb-6">
             <div class="flex items-center space-x-4">
                <img class="w-16 h-16 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&color=fff&size=128" alt="Foto profil {{ $user->name }}">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Bergabung pada: {{ $user->created_at->isoFormat('D MMMM YYYY') }}</p>
                </div>
            </div>
        </div>

        {{-- Kartu Statistik (tidak berubah) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-md text-center">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Catatan</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ $catatanCount }}</dd>
            </div>
            <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-md text-center">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Rencana</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ $rencanaCount }}</dd>
            </div>
            <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-md text-center">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Hari Aktif</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ $totalHariAktif }}</dd>
                 <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">hari</p>
            </div>
        </div>

        {{-- Chart Aktivitas Tahunan --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mb-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Aktivitas Tahun <span x-text="currentYear"></span></h3>
                {{-- Navigasi Tahun (Tombol) --}}
                <div class="flex items-center space-x-2">
                    <button @click="changeYear(-1)" title="Tahun Sebelumnya" class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 disabled:text-gray-300 disabled:cursor-not-allowed" :disabled="loading">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                    </button>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300" x-text="currentYear"></span>
                    <button @click="changeYear(1)" title="Tahun Selanjutnya" class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 disabled:text-gray-300 disabled:cursor-not-allowed" :disabled="loading || currentYear >= new Date().getFullYear()">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
            </div>
            {{-- Container Chart --}}
            <div id="activity-chart" x-ref="activityChartContainer" class="min-h-[300px]"> {{-- Tambah min-h --}}
                 {{-- Indikator Loading Chart --}}
                <div x-show="loading" class="flex justify-center items-center h-[300px] text-gray-500 dark:text-gray-400">Memuat data chart...</div>
            </div>
        </div>

        {{-- Kalender Aktivitas Bulanan --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white" x-text="calendarInfo.monthName || 'Memuat...'"></h3>
                {{-- Navigasi Bulan (Tombol) --}}
                <div class="flex items-center space-x-2">
                    <button @click="changeMonth(calendarInfo.prevMonthUrlParam)" title="Bulan Sebelumnya" class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 disabled:text-gray-300 disabled:cursor-not-allowed" :disabled="loading">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                    </button>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300" x-text="calendarInfo.monthName || '...'"></span>
                    <button @click="changeMonth(calendarInfo.nextMonthUrlParam)" title="Bulan Selanjutnya" class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 disabled:text-gray-300 disabled:cursor-not-allowed" :disabled="loading || !calendarInfo.canGoNextMonth">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
            </div>

            {{-- Container Kalender --}}
            <div id="calendar-grid" class="relative min-h-[250px]"> {{-- Tambah min-h --}}
                 {{-- Indikator Loading Kalender --}}
                <div x-show="loading" class="absolute inset-0 bg-white/50 dark:bg-gray-800/50 flex items-center justify-center z-10 rounded-lg">
                    <span class="text-gray-500 dark:text-gray-400">Memuat kalender...</span>
                </div>
                {{-- Grid Kalender akan dirender oleh JS di sini --}}
                {{-- Pastikan ada elemen ini agar tidak error saat render awal --}}
                <div class="grid grid-cols-7 gap-1 text-center text-sm">
                    {{-- Header Hari --}}
                    @foreach(['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $day)
                        <div class="font-medium text-gray-600 dark:text-gray-400 py-1">{{ $day }}</div>
                    @endforeach
                    {{-- Placeholder --}}
                    @for($i=0; $i<35; $i++) <div>&nbsp;</div> @endfor
                </div>
            </div>
        </div>

    </div> {{-- Penutup x-data --}}

    {{-- SCRIPT ALPINE.JS DAN APEXCHARTS --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        // Definisikan data awal di scope global (window)
        window.initialProfileData = {
            year: {!! json_encode($initialData['selectedYear']) !!},
            month: '{!! $initialData['selectedMonth'] !!}',
            chartData: {!! json_encode($initialData['activityChartData']) !!},
            activeDates: {!! json_encode($initialData['activeDates']) !!},
            calendarInfo: {!! json_encode($initialData['calendarInfo']) !!}
        };

        function profilePage() {
            return {
                currentYear: window.initialProfileData.year,
                currentMonth: window.initialProfileData.month,
                activityChartData: window.initialProfileData.chartData,
                activeDates: new Set(window.initialProfileData.activeDates),
                calendarInfo: window.initialProfileData.calendarInfo,
                loading: false,
                activityChart: null,
                // [BARU] Simpan instance ResizeObserver
                resizeObserver: null,

                initChart() {
                    // Render Kalender awal
                    this.$nextTick(() => {
                        this.renderCalendar();
                        this.renderOrUpdateChart(); // Render chart awal

                        // [BARU] Tambah listener resize window
                        // Debounce: Tunda eksekusi resize handler agar tidak terlalu sering jalan
                        let resizeTimeout;
                        window.addEventListener('resize', () => {
                            clearTimeout(resizeTimeout);
                            resizeTimeout = setTimeout(() => {
                                if (this.activityChart) {
                                     // Cara paling ampuh: destroy dan render ulang chart saat resize
                                     this.renderOrUpdateChart(true); // true = force re-render
                                    console.log('Chart resized on window resize');
                                }
                            }, 250); // Tunggu 250ms setelah resize berhenti
                        });
                    });
                },

                // [DIUBAH] Tambah parameter forceRender
                renderOrUpdateChart(forceRender = false) {
                     if (!this.$refs.activityChartContainer) {
                        console.error("Chart container not found");
                        return;
                    }
                    if (forceRender && this.activityChart) {
                        this.activityChart.destroy();
                        this.activityChart = null;
                        console.log('Forcing chart re-render');
                    }

                    const isDarkMode = localStorage.getItem('color-theme') === 'dark' ||
                                       (!('color-theme' in localStorage) &&
                                       window.matchMedia('(prefers-color-scheme: dark)').matches);
                    const chartTheme = isDarkMode ? 'dark' : 'light';

                    // --- [DIUBAH] Opsi Y-Axis ---
                    const optionsActivity = {
                        series: [{
                            name: 'Hari Aktif',
                            data: this.activityChartData.map(item => item.total)
                        }],
                        chart: {
                            type: 'bar',
                            height: 300, // Pertahankan tinggi eksplisit
                            toolbar: { show: false },
                            foreColor: isDarkMode ? '#A0AEC0' : '#4A5568',
                            background: isDarkMode ? '#1F2937' : '#FFFFFF'
                        },
                        grid: {
                            padding: {
                                top: 20, // Tambahkan padding 20px di atas area chart
                                right: 10,
                                bottom: 0,
                                left: 10
                            }
                        },
                        plotOptions: { bar: { horizontal: false, columnWidth: '65%', borderRadius: 4 } },
                        dataLabels: { enabled: false },
                        stroke: { show: true, width: 2, colors: ['transparent'] },
                        xaxis: { 
                            categories: this.activityChartData.map(item => item.month),
                            crosshairs: {
                                show: false // Menonaktifkan garis/kotak vertikal saat hover
                            },
                            tooltip: {
                                enabled: false // Nonaktifkan tooltip default X-axis jika ada
                            }
                        },
                        yaxis: {
                            title: { text: 'Jumlah Hari Aktif' },
                            labels: {
                                formatter: (val) => {
                                    if (Math.floor(val) === val) {
                                        return val.toFixed(0);
                                    }
                                    return ''; 
                                }
                            },
                            crosshairs: {
                                show: false
                            },
                            tickAmount: 6
                        },
                        fill: { opacity: 1 },
                        tooltip: {
                            theme: chartTheme,
                            intersect: true,
                            shared: false,
                            y: { formatter: (val) => val + " hari aktif" }
                        },
                        states: {
                            hover: {
                                filter: {
                                    type: 'lighten', // Efek: buat bar lebih terang
                                    value: 0.85      // Tingkat kecerahan (0.0 - 1.0, 1=normal)
                                }
                            },
                        },
                        theme: { mode: chartTheme },
                        colors: ['#3B82F6']
                    };
                    // --- Akhir Perubahan Opsi ---


                    if (this.activityChart && !forceRender) {
                        this.activityChart.updateOptions(optionsActivity);
                         console.log('Chart options updated');
                    }
                    else if (!this.activityChart || forceRender) {
                        this.$refs.activityChartContainer.innerHTML = '';
                        this.activityChart = new ApexCharts(this.$refs.activityChartContainer, optionsActivity);
                        this.activityChart.render();
                        console.log('New chart rendered');
                    }
                },


                fetchData() {
                    this.loading = true;
                    // Tampilkan loading di chart & kalender
                     if (this.activityChart) { this.activityChart.destroy(); this.activityChart = null; } // Hapus chart saat loading
                     this.$refs.activityChartContainer.innerHTML = '<div class="flex justify-center items-center h-[300px] text-gray-500 dark:text-gray-400">Memuat data chart...</div>';
                     document.getElementById('calendar-grid').innerHTML = '<div class="absolute inset-0 bg-white/50 dark:bg-gray-800/50 flex items-center justify-center z-10 rounded-lg"><span class="text-gray-500 dark:text-gray-400">Memuat kalender...</span></div>' + document.getElementById('calendar-grid').innerHTML; // Overlay loading

                    fetch(`{{ route('profile.data') }}?year=${this.currentYear}&month=${this.currentMonth}`)
                        .then(response => {
                            if (!response.ok) throw new Error('Network response was not ok: ' + response.statusText);
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                this.activityChartData = data.activityChartData;
                                this.activeDates = new Set(data.activeDates);
                                this.calendarInfo = data.calendarInfo;
                                this.currentYear = data.selectedYear;
                                this.currentMonth = data.selectedMonth;

                                // Tunggu DOM update, lalu render chart & kalender
                                this.$nextTick(() => {
                                    this.renderOrUpdateChart(); // Render/Update Chart
                                    this.renderCalendar();      // Render Kalender
                                });
                            } else {
                                console.error('Gagal mengambil data profil (API Error):', data);
                                this.$refs.activityChartContainer.innerHTML = '<p class="text-red-500 text-center py-10">Gagal memuat data chart.</p>';
                                document.getElementById('calendar-grid').innerHTML = '<p class="text-red-500">Gagal memuat kalender.</p>';
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching profile data:', error);
                            this.$refs.activityChartContainer.innerHTML = '<p class="text-red-500 text-center py-10">Terjadi kesalahan saat mengambil data chart.</p>';
                            document.getElementById('calendar-grid').innerHTML = '<p class="text-red-500">Terjadi kesalahan saat mengambil data kalender.</p>';
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                },

                changeYear(offset) {
                    const newYear = parseInt(this.currentYear) + offset;
                    if (newYear > new Date().getFullYear() || newYear < 2000) return;
                    if (newYear === this.currentYear) return;
                    this.currentYear = newYear;
                    // Saat ganti tahun via chart, reset bulan ke bulan saat ini di tahun baru jika memungkinkan,
                    // atau ke Januari jika bulan saat ini belum ada di tahun baru (misal ganti ke tahun depan)
                    const currentMonthNum = parseInt(this.currentMonth.split('-')[1]);
                    const targetMonth = `${newYear}-${String(currentMonthNum).padStart(2, '0')}`;
                    // Cek apakah target bulan ada di masa depan
                     if (targetMonth > new Date().toISOString().slice(0, 7)) {
                         this.currentMonth = `${newYear}-01`; // Default ke Januari jika target bulan di masa depan
                     } else {
                         this.currentMonth = targetMonth; // Tetap pakai bulan yang sama di tahun baru
                     }

                    this.fetchData();
                },

                changeMonth(newMonth) { // newMonth format YYYY-MM
                    if (!newMonth || newMonth === this.currentMonth || this.loading) return;

                    // Cek validitas bulan depan (gunakan info dari server)
                     if (newMonth > this.currentMonth && !this.calendarInfo.canGoNextMonth) {
                        // Jika server bilang tidak bisa ke bulan depan, jangan lakukan fetch
                        // Cek tambahan, jangan sampai melebihi bulan saat ini
                        if (newMonth > new Date().toISOString().slice(0, 7)) {
                             console.warn('Attempted to navigate to future month:', newMonth);
                             return; // Hentikan navigasi
                        }
                    }


                    this.currentMonth = newMonth;
                    const newYearFromMonth = parseInt(newMonth.split('-')[0]);
                    if (newYearFromMonth !== this.currentYear) {
                        this.currentYear = newYearFromMonth;
                    }
                    this.fetchData();
                },

                renderCalendar() {
                    // ... (Fungsi renderCalendar tetap sama seperti sebelumnya) ...
                     const calendarGrid = document.getElementById('calendar-grid');
                    if (!calendarGrid) {
                        console.error('Calendar grid container not found');
                        return;
                    }
                    if (!this.currentMonth || !/^\d{4}-\d{2}$/.test(this.currentMonth)) {
                         console.error('Invalid currentMonth format:', this.currentMonth);
                         calendarGrid.innerHTML = '<p class="text-red-500">Error: Bulan tidak valid.</p>';
                         return;
                    }
                    try {
                        const year = parseInt(this.currentMonth.split('-')[0]);
                        const month = parseInt(this.currentMonth.split('-')[1]);
                        const daysInMonth = new Date(Date.UTC(year, month, 0)).getUTCDate();
                        const firstDayOfMonth = new Date(Date.UTC(year, month - 1, 1));
                        let firstDayOfWeekIso = firstDayOfMonth.getUTCDay();
                        if (firstDayOfWeekIso === 0) firstDayOfWeekIso = 7;
                        const today = new Date().toISOString().split('T')[0];
                        let html = `<div class="grid grid-cols-7 gap-1 text-center text-sm">`;
                        ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'].forEach(day => {
                            html += `<div class="font-medium text-gray-600 dark:text-gray-400 py-1">${day}</div>`;
                        });
                        for (let i = 1; i < firstDayOfWeekIso; i++) { html += `<div></div>`; }
                        for (let day = 1; day <= daysInMonth; day++) {
                            const currentDate = `${String(year)}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                            const isActive = this.activeDates.has(currentDate);
                            const isToday = currentDate === today;
                            let classes = 'py-1 px-1 flex justify-center items-center h-8 w-8 mx-auto rounded-full transition-colors duration-150 ';
                            if (isActive) { classes += 'bg-blue-500 text-white font-semibold'; }
                            else if (isToday) { classes += 'ring-2 ring-blue-500 ring-offset-1 dark:ring-offset-gray-800 text-gray-700 dark:text-gray-300'; }
                            else { classes += 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'; }
                            html += `<div class="${classes}">${day}</div>`;
                        }
                        html += `</div>`;
                        calendarGrid.innerHTML = html;
                    } catch (e) {
                         console.error('Error rendering calendar:', e);
                         calendarGrid.innerHTML = '<p class="text-red-500">Gagal memuat kalender.</p>';
                    }
                }
            }
        }
    </script>

</x-personal::settings-layout>