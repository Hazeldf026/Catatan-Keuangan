<?php

namespace App\Http\Controllers\personal;

use App\Http\Controllers\Controller;
use App\Models\Catatan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class AnalysisController extends Controller
{
    public function showAnalysisPage()
    {
        $userId = Auth::id();
        $firstTransactionDate = Catatan::where('user_id', $userId)->min('created_at');

        return view('personal::analysis', [
            'todayDate' => Carbon::now()->toDateString(),
            'firstTransactionDate' => $firstTransactionDate ? Carbon::parse($firstTransactionDate)->toDateString() : null,
        ]);
    }

    public function getChartData(Request $request)
    {
        $userId = Auth::id();
        $scale = $request->input('scale', 'monthly');
        $date = $request->input('date', Carbon::now()->toDateString());
        $targetDate = Carbon::parse($date);

        // Kueri Pemasukan/Pengeluaran 
        $lifetimePemasukanQuery = Catatan::where('user_id', $userId)
            ->whereHas('category', fn ($q) => $q->where('tipe', 'pemasukan'));
        $lifetimePengeluaranQuery = Catatan::where('user_id', $userId)
            ->whereHas('category', fn ($q) => $q->where('tipe', 'pengeluaran'));
        $totalPemasukanLifetime = (clone $lifetimePemasukanQuery)->sum('jumlah');
        $totalPengeluaranLifetime = (clone $lifetimePengeluaranQuery)->sum('jumlah');

        $mediaColorMap = [
            'wallet'     => 'rgba(249, 115, 22, 0.8)',  // orange-500
            'bank'       => 'rgba(139, 92, 246, 0.8)',  // violet-500 (ungu)
            'e-wallet'   => 'rgba(14, 165, 233, 0.8)',  // sky-500 (biru muda)
            'tabungan'   => 'rgba(234, 179, 8, 0.8)',   // yellow-500 (kuning)
        ];
        
        // data SALDO per media dari database
        $mediaBalances = Catatan::where('catatans.user_id', $userId)
            ->whereNotNull('media')
            ->join('categories', 'catatans.category_id', '=', 'categories.id')
            ->groupBy(DB::raw('LOWER(media)')) 
            ->select(
                DB::raw('LOWER(media) as media'),
                DB::raw('SUM(CASE WHEN categories.tipe = "pemasukan" THEN catatans.jumlah ELSE -catatans.jumlah END) as total')
            )
            ->orderBy('total', 'desc')
            ->get();
        
        $mediaPieChartLabels = $mediaBalances->pluck('media');
        
        $mediaPieChartData = $mediaBalances->map(fn ($item) => $item->total > 0 ? $item->total : 0);
        
        $mediaPieChartColors = $mediaBalances->map(fn ($item) =>
            $mediaColorMap[$item->media] ?? 'rgba(156, 163, 175, 0.8)'
        );
        

        // Kueri sisa untuk data analisis 
        $incomesLifetime = (clone $lifetimePemasukanQuery)->get();
        $expensesLifetime = (clone $lifetimePengeluaranQuery)->get();
        $prevMonthStartDate = Carbon::now()->subMonth()->startOfMonth();
        $prevMonthEndDate = Carbon::now()->subMonth()->endOfMonth();
        $lastPeriodIncomes = (clone $lifetimePemasukanQuery)->whereBetween('created_at', [$prevMonthStartDate, $prevMonthEndDate])->sum('jumlah');
        $lastPeriodExpenses = (clone $lifetimePengeluaranQuery)->whereBetween('created_at', [$prevMonthStartDate, $prevMonthEndDate])->sum('jumlah');
        $firstTransactionDate = Catatan::where('user_id', $userId)->min('created_at');
        $daysInPeriodLifetime = $firstTransactionDate ? Carbon::parse($firstTransactionDate)->diffInDays(Carbon::now()) + 1 : 1;
        
        $analysisData = $this->calculateAnalysisData($totalPemasukanLifetime, $totalPengeluaranLifetime, $incomesLifetime, $expensesLifetime, $lastPeriodIncomes, $lastPeriodExpenses, $daysInPeriodLifetime);
        $lineChartData = $this->generateLineChartData($userId, $scale, $targetDate);

        return response()->json([
            'lifetimeData' => [
                'summary' => [
                    'totalPemasukan' => number_format($totalPemasukanLifetime, 0, ',', '.'),
                    'totalPengeluaran' => number_format($totalPengeluaranLifetime, 0, ',', '.'),
                    'saldo' => number_format($totalPemasukanLifetime - $totalPengeluaranLifetime, 0, ',', '.'),
                ],
                'pieChart' => [
                    'pemasukan' => $totalPemasukanLifetime,
                    'pengeluaran' => $totalPengeluaranLifetime,
                ],
                'mediaPieChart' => [
                    'labels' => $mediaPieChartLabels,
                    'data' => $mediaPieChartData,
                    'colors' => $mediaPieChartColors,
                ],
                'analysisData' => $analysisData,
            ],
            'lineChartData' => $lineChartData,
        ]);
    }

    private function generateLineChartData($userId, $scale, Carbon $targetDate)
    {
        [$startDate, $endDate, $unit] = $this->getDateBounds($scale, $targetDate);

        $initialBalance = Catatan::where('user_id', $userId)
            ->where('catatans.created_at', '<', $startDate)
            ->join('categories', 'catatans.category_id', '=', 'categories.id')
            ->select(DB::raw('SUM(CASE WHEN categories.tipe = "pemasukan" THEN catatans.jumlah ELSE -catatans.jumlah END) as balance'))
            ->value('balance') ?? 0;

        $transactions = Catatan::where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with('category')
            ->orderBy('created_at')
            ->get();

        $groupedTransactions = $transactions->groupBy(function ($item) use ($unit) {
            return Carbon::parse($item->created_at)->startOf($unit)->timestamp;
        });

        $linePoints = [];
        $runningBalance = $initialBalance;

        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {

            if ($currentDate->isFuture()) {
                break;
            }

            $periodTimestamp = $currentDate->copy()->startOf($unit)->timestamp;
            $periodTransactions = $groupedTransactions->get($periodTimestamp, collect());

            $close = $runningBalance;
            if ($periodTransactions->isNotEmpty()) {
                foreach ($periodTransactions as $transaction) {
                    $close += ($transaction->category->tipe === 'pemasukan' ? $transaction->jumlah : -$transaction->jumlah);
                }
            }

            $linePoints[] = [
                'x' => $currentDate->valueOf(),
                'y' => (float)$close,
            ];

            $runningBalance = $close;
            $currentDate->add(1, $unit);
        }

        return $linePoints;
    }

    private function getDateBounds($scale, Carbon $targetDate)
    {
        $unit = 'day';
        switch ($scale) {
            case 'weekly':
                $startDate = $targetDate->copy()->startOfWeek();
                $endDate = $targetDate->copy()->endOfWeek();
                break;
            case 'yearly':
                $startDate = $targetDate->copy()->startOfYear();
                $endDate = $targetDate->copy()->endOfYear();
                $unit = 'month';
                break;
            case 'monthly':
            default:
                $startDate = $targetDate->copy()->startOfMonth();
                $endDate = $targetDate->copy()->endOfMonth();
                break;
        }
        return [$startDate, $endDate, $unit];
    }

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
        $dailyExpenses = $expenses->groupBy(fn ($date) => Carbon::parse($date->created_at)->format('Y-m-d'))->map(fn ($day) => $day->sum('jumlah'));
        $stdDevPengeluaranHarian = $this->calculate_std_dev($dailyExpenses->values()->toArray());
        return [
            'ringkasanUmum' => [['label' => 'Total Pemasukan', 'value' => 'Rp ' . number_format($totalPemasukan, 0, ',', '.')], ['label' => 'Total Pengeluaran', 'value' => 'Rp ' . number_format($totalPengeluaran, 0, ',', '.')], ['label' => 'Selisih', 'value' => 'Rp ' . number_format($selisih, 0, ',', '.')],],
            'rasioPertumbuhan' => [['label' => 'Rasio Tabungan', 'value' => number_format($rasioTabungan, 2) . ' %'], ['label' => 'Pertumbuhan Pemasukan (Bulan)', 'value' => number_format($pertumbuhanPemasukan, 2) . ' %'], ['label' => 'Pertumbuhan Pengeluaran (Bulan)', 'value' => number_format($pertumbuhanPengeluaran, 2) . ' %'], ['label' => 'Persentase Pengeluaran Terbesar', 'value' => number_format($persentasePengeluaranTerbesar, 2) . ' %'],],
            'analisisTransaksi' => [['label' => 'Jumlah Transaksi Pemasukan', 'value' => $jumlahTransaksiPemasukan], ['label' => 'Jumlah Transaksi Pengeluaran', 'value' => $jumlahTransaksiPengeluaran], ['label' => 'Rata-rata Pemasukan per Transaksi', 'value' => 'Rp ' . number_format($avgPemasukanPerTransaksi, 0, ',', '.')], ['label' => 'Rata-rata Pengeluaran per Transaksi', 'value' => 'Rp ' . number_format($avgPengeluaranPerTransaksi, 0, ',', '.')], ['label' => 'Median Transaksi Pengeluaran', 'value' => 'Rp ' . number_format($medianPengeluaran, 0, ',', '.')],],
            'harianKonsistensi' => [['label' => 'Rata-rata Pengeluaran per Hari', 'value' => 'Rp ' . number_format($avgPengeluaranPerHari, 0, ',', '.')], ['label' => 'Frekuensi Transaksi Harian', 'value' => number_format($frekuensiTransaksiHarian, 2) . 'x'], ['label' => 'Standar Deviasi Pengeluaran Harian', 'value' => 'Rp ' . number_format($stdDevPengeluaranHarian, 0, ',', '.')],],
        ];
    }
    
    private function calculate_std_dev(array $arr)
    {
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