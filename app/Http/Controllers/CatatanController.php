<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Catatan;
use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class CatatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // --- AMBIL SEMUA DATA UNTUK KARTU KANAN (TIDAK BERUBAH) ---
        $totalPemasukan = Catatan::where('user_id', Auth::id())->whereHas('category', function($query){
            $query->where('tipe', 'pemasukan');
        })->sum('jumlah');

        $totalPengeluaran = Catatan::where('user_id', Auth::id())->whereHas('category', function($query){
            $query->where('tipe', 'pengeluaran');
        })->sum('jumlah');

        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        // --- LOGIKA BARU UNTUK FILTER DAN SORTING ---

        // Ambil data kategori untuk dikirim ke view (untuk filter modal)
        $categories = Category::orderBy('nama')->get();

        // Query dasar untuk catatan transaksi pengguna
        $query = Catatan::with('category')->where('user_id', Auth::id());

        // 1. Filter Berdasarkan Rentang Waktu Cepat (3 hari, 5 hari, dll.)
        if ($request->has('range')) {
            switch ($request->range) {
                case '3d': $query->whereDate('created_at', '>=', now()->subDays(3)); break;
                case '5d': $query->whereDate('created_at', '>=', now()->subDays(5)); break;
                case 'week': $query->whereDate('created_at', '>=', now()->startOfWeek()); break;
                case 'month': $query->whereDate('created_at', '>=', now()->startOfMonth()); break;
                case 'year': $query->whereDate('created_at', '>=', now()->startOfYear()); break;
            }
        }

        // 2. Filter dari Modal (Tipe & Kategori)
        if ($request->has('tipe') && in_array($request->tipe, ['pemasukan', 'pengeluaran'])) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('tipe', $request->tipe);
            });
        }

        if ($request->has('kategori') && is_array($request->kategori)) {
            // Ambil ID kategori berdasarkan nama yang dipilih
            $categoryIds = Category::whereIn('nama', $request->kategori)->pluck('id');
            $query->whereIn('category_id', $categoryIds);
        }
        
        // 3. Logika untuk Mengurutkan (Sorting)
        $sortBy = $request->get('sort_by', 'created_at'); // Default urutkan berdasarkan tanggal
        $order = $request->get('order', 'desc'); // Default urutan terbaru/terbesar

        if ($sortBy == 'tanggal') {
            $query->orderBy('created_at', $order);
        } elseif ($sortBy == 'jumlah') {
            $query->orderBy('jumlah', $order);
        }

        // Ambil data transaksi dengan paginasi dan pastikan parameter filter tetap ada saat pindah halaman
        $catatans = $query->latest()->paginate(6)->withQueryString();

        // Kirim semua data ke view
        return view('pages.catatan.index', compact(
            'catatans', 
            'saldoAkhir', 
            'totalPemasukan', 
            'totalPengeluaran',
            'categories' // Kirim data kategori ke view
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('tipe')->get();
        return view('pages.catatan.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'deskripsi' => 'required|string|max:255',
        'tipe' => 'required|in:pemasukan,pengeluaran',
        'jumlah' => 'required|numeric',
        'category_id' => 'nullable|exists:categories,id',
        'custom_category' => 'nullable|string|max:255',
        ]);

        // Handle "Lainnya..." option
        $categoryId = $request->input('category_id');
        $customCategory = null;
        $tipe = $request->input('tipe');

        $lainnyaCategory = Category::where('nama', 'Lainnya...')->where('tipe', $tipe)->first();

        if ($lainnyaCategory && $categoryId == $lainnyaCategory->id) {
            $customCategory = $request->input('custom_category');
        }

        $request->user()->catatan()->create([
        'user_id' => Auth::id(),
        'deskripsi' => $request->input('deskripsi'),
        'jumlah' => $request->input('jumlah'),
        'category_id' => $categoryId,
        'custom_category' => $customCategory,
        ]);

        return redirect()->route('catatan.index')->with('success', 'Catatan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Catatan $catatan)
    {
        return view('pages.catatan.show', compact('catatan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Catatan $catatan)
    {
        $categories = Category::orderBy('tipe')->get();
        return view('pages.catatan.edit', compact('catatan', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Catatan $catatan)
    {
        $request->validate([
        'deskripsi' => 'required|string|max:255',
        'tipe' => 'required|in:pemasukan,pengeluaran',
        'jumlah' => 'required|numeric',
        'category_id' => 'nullable|exists:categories,id',
        'custom_category' => 'nullable|string|max:255',
        ]);

        // Handle "Lainnya..." option
        $categoryId = $request->input('category_id');
        $customCategory = null;
        $tipe = $request->input('tipe');

        $lainnyaCategory = Category::where('nama', 'Lainnya...')->where('tipe', $tipe)->first();

        if ($lainnyaCategory && $categoryId == $lainnyaCategory->id) {
            $customCategory = $request->input('custom_category');
        }

        $catatan->update([
        'user_id' => Auth::id(),
        'deskripsi' => $request->input('deskripsi'),
        'jumlah' => $request->input('jumlah'),
        'category_id' => $categoryId,
        'custom_category' => $customCategory,
        ]);

        return redirect()->route('catatan.index')->with('success', 'Catatan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Catatan $catatan)
    {
        $catatan->delete();
        return redirect()->route('catatan.index')->with('success', 'Catatan berhasil dihapus!');
    }
}
