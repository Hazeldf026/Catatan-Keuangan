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
    public function index()
    {
        //data total pemasukan
        $totalPemasukan = Catatan::whereHas('category', function($query){
            $query->where('tipe', 'pemasukan');
        })->sum('jumlah');

        //data total pengeluaran
        $totalPengeluaran = Catatan::whereHas('category', function($query){
            $query->where('tipe', 'pengeluaran');
        })->sum('jumlah');

        //saldo akhir
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        //ambil data transaksi
        $catatans = Catatan::with('category')->where('user_id', Auth::id())->latest()->paginate(10);

        //kirim semua data ke view
        return view('pages.catatan.index', compact('catatans', 'saldoAkhir', 'totalPemasukan', 'totalPengeluaran'));
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

        $lainnya = Category::where('nama', 'Lainnya...')->first();
        if ($lainnya && (int)$categoryId == (int)$lainnya->id) {
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

        $lainnya = Category::where('nama', 'Lainnya...')->first();
        if ($lainnya && $categoryId == $lainnya->id) {
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
