<?php

namespace App\Http\Controllers\group;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Grup;
use App\Models\GrupCatatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatatanGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Grup $grup) // Terima $grup dari route model binding
    {
        // Middleware seharusnya sudah cek user anggota, tapi cek lagi untuk keamanan
        if (!$grup->users()->where('user_id', Auth::id())->exists()) {
            abort(403, 'Anda bukan anggota grup ini.');
        }

        // --- Kalkulasi untuk Kartu Ringkasan ---

        // 1. Saldo Media Grup
        $calculateMediaBalance = function($media) use ($grup) {
            $pemasukan = GrupCatatan::where('grup_id', $grup->id)
                                ->where('media', $media)
                                ->whereHas('category', fn($q) => $q->where('tipe', 'pemasukan'))
                                ->sum('jumlah');
            $pengeluaran = GrupCatatan::where('grup_id', $grup->id)
                                ->where('media', $media)
                                ->whereHas('category', fn($q) => $q->where('tipe', 'pengeluaran'))
                                ->sum('jumlah');
            return $pemasukan - $pengeluaran;
        };
        $totalWallet = $calculateMediaBalance('wallet');
        $totalBank = $calculateMediaBalance('bank');
        $totalEWallet = $calculateMediaBalance('e-wallet');
        $totalTabungan = $calculateMediaBalance('tabungan');

        // 2. Total Dana Rencana Grup (Placeholder, implementasi nanti)
        // $totalRencana = GrupRencana::where('grup_id', $grup->id)->sum('jumlah_terkumpul');
        $totalRencana = 0; // Ganti ini nanti

        // 3. Total Pemasukan/Pengeluaran Grup
        $totalPemasukan = GrupCatatan::where('grup_id', $grup->id)
                                    ->whereHas('category', fn($q) => $q->where('tipe', 'pemasukan'))
                                    ->sum('jumlah');
        $totalPengeluaran = GrupCatatan::where('grup_id', $grup->id)
                                    ->whereHas('category', fn($q) => $q->where('tipe', 'pengeluaran'))
                                    ->sum('jumlah');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        // --- Query untuk Tabel Catatan Grup ---
        $query = GrupCatatan::where('grup_id', $grup->id)
                            ->with(['user', 'category']); // Eager load relasi

        // TODO: Implementasi filter (tanggal, tipe, kategori - mirip CatatanController personal)
        // Contoh filter tanggal (jika ada input 'range')
        if ($request->has('range')) {
            switch ($request->range) {
                case '7d': $query->whereDate('created_at', '>=', now()->subDays(7)); break;
                case '30d': $query->whereDate('created_at', '>=', now()->subDays(30)); break;
                // Tambahkan range lain jika perlu
            }
        }

        $catatans = $query->latest()->paginate(10)->withQueryString(); // Ambil data & paginasi

        // Ambil data lain yang mungkin dibutuhkan (misal untuk filter modal)
        $categories = Category::orderBy('nama')->get();

        return view('group::catatan.index', compact(
            'grup', // WAJIB ada untuk layout
            'catatans',
            'totalWallet',
            'totalBank',
            'totalEWallet',
            'totalTabungan',
            'totalRencana',
            'totalPemasukan',
            'totalPengeluaran',
            'saldoAkhir',
            'categories' // Untuk modal filter nanti
            // Kirim data filter aktif jika ada (misal $request->range)
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
