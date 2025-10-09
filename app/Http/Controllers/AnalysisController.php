<?php

namespace App\Http\Controllers;

use App\Models\Catatan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
    public function showAnalysisPage()
    {
        return view('pages.analysis');
    }

    public function getChartData(Request $request)
    {
        $userId = Auth::id();
        $scale = $request->input('scale', 'monthly');

        // =================================================================
        // BAGIAN 1: PERHITUNGAN DATA SEUMUR HIDUP (LIFETIME)
        // =================================================================
        $lifetimePemasukanQuery = Catatan::where('user_id', $userId)
            ->whereHas('category', fn($q) => $q->where('tipe', 'pemasukan'));
        
        $lifetimePengeluaranQuery = Catatan::where('user_id', $userId)
            ->whereHas('category', fn($q) => $q->where('tipe', 'pengeluaran'));

        $totalPemasukanLifetime = (clone $lifetimePemasukanQuery)->sum('jumlah');
        $totalPengeluaranLifetime = (clone $lifetimePengeluaranQuery)->sum('jumlah');
        $saldoLifetime = $totalPemasukanLifetime - $totalPengeluaranLifetime;

        $incomesLifetime = (clone $lifetimePemasukanQuery)->get();
        $expensesLifetime = (clone $lifetimePengeluaranQuery)->get();
        
        $prevMonthStartDate = Carbon::now()->subMonth()->startOfMonth();
        $prevMonthEndDate = Carbon::now()->subMonth()->endOfMonth();
        $lastPeriodIncomes = (clone $lifetimePemasukanQuery)->whereBetween('created_at', [$prevMonthStartDate, $prevMonthEndDate])->sum('jumlah');
        $lastPeriodExpenses = (clone $lifetimePengeluaranQuery)->whereBetween('created_at', [$prevMonthStartDate, $prevMonthEndDate])->sum('jumlah');
        
        $firstTransactionDate = Catatan::where('user_id', $userId)->min('created_at');
        $daysInPeriodLifetime = $firstTransactionDate ? Carbon::parse($firstTransactionDate)->diffInDays(Carbon::now()) + 1 : 1;
        
        $analysisData = $this->calculateAnalysisData($totalPemasukanLifetime, $totalPengeluaranLifetime, $incomesLifetime, $expensesLifetime, $lastPeriodIncomes, $lastPeriodExpenses, $daysInPeriodLifetime);


        // =================================================================
        // BAGIAN 2: PERHITUNGAN DATA LINE CHART
        // =================================================================
        $lineChartData = $this->generateLineChartData($userId, $scale);

        // =================================================================
        // BAGIAN 3: MENGIRIM SEMUA DATA DALAM FORMAT JSON
        // =================================================================
        return response()->json([
            'lifetimeData' => [
                'summary' => [
                    'totalPemasukan' => number_format($totalPemasukanLifetime, 0, ',', '.'),
                    'totalPengeluaran' => number_format($totalPengeluaranLifetime, 0, ',', '.'),
                    'saldo' => number_format($saldoLifetime, 0, ',', '.'),
                ],
                'pieChart' => [
                    'pemasukan' => $totalPemasukanLifetime,
                    'pengeluaran' => $totalPengeluaranLifetime,
                ],
                'analysisData' => $analysisData,
            ],
            // Mengirim data lineChartData
            'lineChartData' => $lineChartData,
        ]);
    }

    private function generateLineChartData($userId, $scale)
    {
        $timezone = config('app.timezone');
        [$startDate, $unit, $format] = $this->getDateConfigForLineChart($scale, $timezone);

        // 1. Hitung saldo awal (logika ini tetap sama)
        $initialBalance = Catatan::where('user_id', $userId)
            ->where('catatans.created_at', '<', $startDate)
            ->select(DB::raw('SUM(CASE WHEN categories.tipe = "pemasukan" THEN catatans.jumlah ELSE -catatans.jumlah END) as balance'))
            ->join('categories', 'catatans.category_id', '=', 'categories.id')
            ->value('balance') ?? 0;

        // 2. Ambil semua transaksi (logika ini tetap sama)
        $transactions = Catatan::where('user_id', $userId)
            ->where('created_at', '>=', $startDate)
            ->with('category')
            ->orderBy('created_at')
            ->get();

        // 3. Kelompokkan transaksi (logika ini tetap sama)
        $groupedTransactions = $transactions->groupBy(function ($item) use ($unit) {
            return Carbon::parse($item->created_at)->startOf($unit)->timestamp;
        });

        $linePoints = [];
        $runningBalance = $initialBalance;
        $endDate = Carbon::now($timezone);
        $currentDate = $startDate->copy();

        // 4. [PERUBAHAN] Loop disederhanakan untuk menghasilkan data {x, y}
        while ($currentDate <= $endDate) {
            $periodTimestamp = $currentDate->copy()->startOf($unit)->timestamp;
            $periodTransactions = $groupedTransactions->get($periodTimestamp, collect());

            $close = $runningBalance; // Saldo awal periode adalah saldo penutup periode sebelumnya

            if ($periodTransactions->isNotEmpty()) {
                foreach ($periodTransactions as $transaction) {
                    if ($transaction->category->tipe === 'pemasukan') {
                        $close += $transaction->jumlah;
                    } else {
                        $close -= $transaction->jumlah;
                    }
                }
            }

            // [PERUBAHAN] Simpan hanya data x (waktu) dan y (saldo akhir)
            $linePoints[] = [
                'x' => $currentDate->valueOf(),
                'y' => (float)$close,
            ];

            $runningBalance = $close; // Update saldo berjalan untuk periode berikutnya
            $currentDate->add(1, $unit);
        }

        return $linePoints;
    }

    private function getDateConfigForLineChart($scale, $timezone)
    {
        $unit = 'day';
        $format = 'd M';
        switch ($scale) {
            case '3days':
                $startDate = Carbon::now($timezone)->subDays(2)->startOfDay();
                break;
            case '5days':
                $startDate = Carbon::now($timezone)->subDays(4)->startOfDay();
                break;
            case 'weekly':
                $startDate = Carbon::now($timezone)->subDays(6)->startOfDay();
                break;
            case 'yearly':
                $startDate = Carbon::now($timezone)->startOfYear();
                $unit = 'month';
                $format = 'MMM YYYY';
                break;
            case 'monthly':
            default:
                $startDate = Carbon::now($timezone)->subDays(29)->startOfDay();
                break;
        }
        return [$startDate, $unit, $format];
    }
    
    // Fungsi calculateAnalysisData dan lainnya tetap sama
    private function calculateAnalysisData($totalPemasukan, $totalPengeluaran, $incomes, $expenses, $lastPeriodIncomes, $lastPeriodExpenses, $daysInPeriod)
    {
        $incomes = collect($incomes);
        $expenses = collect($expenses);
        $selisih = $totalPemasukan - $totalPengeluaran;
        $rasioTabungan = $totalPemasukan > 0 ? ($selisih / $totalPemasukan) * 100 : 0;
        $pertumbuhanPemasukan = $lastPeriodIncomes > 0 ? (($totalPemasukan - $lastPeriodIncomes) / $lastPeriodIncomes) * 100 : ($totalPemasukan > 0 ? 100 : 0);
        $pertumbuhanPengeluaran = $lastPeriodExpenses > 0 ? (($totalPengeluaran - $lastPeriodExpenses) / $lastPeriodExpenses) * 100 : ($totalPengeluaran > 0 ? 100 : 0);
        $pengeluaranTerbesar = $expenses->max('jumlah') ?? 0;
        $persentasePengeluaranTerbesar = $totalPengeluaran > 0 ? ($pengeluaranTerbesar / $totalPengeluaran) * 100 : 0;
        $jumlahTransaksiPemasukan = $incomes->count();
        $jumlahTransaksiPengeluaran = $expenses->count();
        $avgPemasukanPerTransaksi = $jumlahTransaksiPemasukan > 0 ? $totalPemasukan / $jumlahTransaksiPemasukan : 0;
        $avgPengeluaranPerTransaksi = $jumlahTransaksiPengeluaran > 0 ? $totalPengeluaran / $jumlahTransaksiPengeluaran : 0;
        $medianPengeluaran = $expenses->median('jumlah') ?? 0;
        $avgPengeluaranPerHari = $daysInPeriod > 0 ? $totalPengeluaran / $daysInPeriod : 0;
        $frekuensiTransaksiHarian = $daysInPeriod > 0 ? ($jumlahTransaksiPemasukan + $jumlahTransaksiPengeluaran) / $daysInPeriod : 0;
        $dailyExpenses = $expenses->groupBy(fn($date) => Carbon::parse($date->created_at)->format('Y-m-d'))->map(fn($day) => $day->sum('jumlah'));
        $stdDevPengeluaranHarian = $this->calculate_std_dev($dailyExpenses->values()->toArray());
        return [
            'ringkasanUmum' => [['label' => 'Total Pemasukan', 'value' => 'Rp ' . number_format($totalPemasukan, 0, ',', '.')], ['label' => 'Total Pengeluaran', 'value' => 'Rp ' . number_format($totalPengeluaran, 0, ',', '.')], ['label' => 'Selisih', 'value' => 'Rp ' . number_format($selisih, 0, ',', '.')],],
            'rasioPertumbuhan' => [['label' => 'Rasio Tabungan', 'value' => number_format($rasioTabungan, 2) . ' %'], ['label' => 'Pertumbuhan Pemasukan (Bulan)', 'value' => number_format($pertumbuhanPemasukan, 2) . ' %'], ['label' => 'Pertumbuhan Pengeluaran (Bulan)', 'value' => number_format($pertumbuhanPengeluaran, 2) . ' %'], ['label' => 'Persentase Pengeluaran Terbesar', 'value' => number_format($persentasePengeluaranTerbesar, 2) . ' %'],],
            'analisisTransaksi' => [['label' => 'Jumlah Transaksi Pemasukan', 'value' => $jumlahTransaksiPemasukan], ['label' => 'Jumlah Transaksi Pengeluaran', 'value' => $jumlahTransaksiPengeluaran], ['label' => 'Rata-rata Pemasukan per Transaksi', 'value' => 'Rp ' . number_format($avgPemasukanPerTransaksi, 0, ',', '.')], ['label' => 'Rata-rata Pengeluaran per Transaksi', 'value' => 'Rp ' . number_format($avgPengeluaranPerTransaksi, 0, ',', '.')], ['label' => 'Median Transaksi Pengeluaran', 'value' => 'Rp ' . number_format($medianPengeluaran, 0, ',', '.')],],
            'harianKonsistensi' => [['label' => 'Rata-rata Pengeluaran per Hari', 'value' => 'Rp ' . number_format($avgPengeluaranPerHari, 0, ',', '.')], ['label' => 'Frekuensi Transaksi Harian', 'value' => number_format($frekuensiTransaksiHarian, 2) . 'x'], ['label' => 'Standar Deviasi Pengeluaran Harian', 'value' => 'Rp ' . number_format($stdDevPengeluaranHarian, 0, ',', '.')],],
        ];
    }
    private function calculate_std_dev(array $arr) {
        $n = count($arr);
        if ($n === 0) return 0.0;
        $mean = array_sum($arr) / $n;
        $variance = 0.0;
        foreach ($arr as $x) {
            $variance += pow($x - $mean, 2);
        }
        return (float)sqrt($variance / $n);
    }
}