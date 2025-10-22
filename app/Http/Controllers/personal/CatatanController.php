<?php

namespace App\Http\Controllers\personal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Catatan;
use App\Models\Category;
use App\Models\Rencana;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
class CatatanController extends Controller
{

    public function index(Request $request)
    {
        $userId = Auth::id();

        // --- KALKULASI TOTAL PEMASUKAN & PENGELUARAN  ---
        $totalPemasukan = Catatan::where('user_id', $userId)->whereHas('category', function($query){
            $query->where('tipe', 'pemasukan');
        })->sum('jumlah');

        $totalPengeluaran = Catatan::where('user_id', $userId)->whereHas('category', function($query){
            $query->where('tipe', 'pengeluaran');
        })->sum('jumlah');

        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        // --- KALKULASI UNTUK SETIAP MEDIA ---
        $calculateMediaBalance = function($media) use ($userId) {
            $pemasukan = Catatan::where('user_id', $userId)
                                ->where('media', $media)
                                ->whereHas('category', function($q) {
                                    $q->where('tipe', 'pemasukan');
                                })->sum('jumlah');
            $pengeluaran = Catatan::where('user_id', $userId)
                                ->where('media', $media)
                                ->whereHas('category', function($q) {
                                    $q->where('tipe', 'pengeluaran');
                                })->sum('jumlah');
            return $pemasukan - $pengeluaran;
        };

        $totalWallet = $calculateMediaBalance('wallet');
        $totalBank = $calculateMediaBalance('bank');
        $totalEWallet = $calculateMediaBalance('e-wallet');
        $totalTabungan = $calculateMediaBalance('tabungan');

        // --- KALKULASI UNTUK TOTAL RENCANA ---
        $totalRencana = Rencana::where('user_id', $userId)->sum('jumlah_terkumpul');

        $pinnedRencanas = Rencana::where('user_id', $userId)
                            ->where('is_pinned', true)
                            ->where('status', 'berjalan') 
                            ->orderBy('created_at', 'desc')
                            ->limit(3) 
                            ->get();

        // --- LOGIKA FILTER & SORTING ---
        $categories = Category::orderBy('nama')->get();
        $query = Catatan::with('category')->where('user_id', $userId);
        if ($request->has('range')) {
            switch ($request->range) {
                case '3d': $query->whereDate('created_at', '>=', now()->subDays(3)); break;
                case '5d': $query->whereDate('created_at', '>=', now()->subDays(5)); break;
                case 'week': $query->whereDate('created_at', '>=', now()->subDays(7)); break;
                case 'month': $query->whereDate('created_at', '>=', now()->subDays(30)); break;
                case 'year': $query->whereDate('created_at', '>=', now()->startOfYear()); break;
            }
        }
        if ($request->has('tipe') && in_array($request->tipe, ['pemasukan', 'pengeluaran'])) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('tipe', $request->tipe);
            });
        }
        if ($request->has('kategori') && is_array($request->kategori)) {
            $categoryIds = Category::whereIn('nama', $request->kategori)->pluck('id');
            $query->whereIn('category_id', $categoryIds);
        }
        if ($request->filled('media') && is_array($request->media)) {
            $query->whereIn('media', $request->media);
        }
        if ($request->input('alokasi_rencana') == 'true') {
            $query->whereNotNull('rencana_id'); 
        }
        $sortBy = $request->get('sort_by', 'created_at');
        $order = $request->get('order', 'desc');
        if ($sortBy == 'tanggal') {
            $query->orderBy('created_at', $order);
        } elseif ($sortBy == 'jumlah') {
            $query->orderBy('jumlah', $order);
        }
        $catatans = $query->latest()->paginate(10)->onEachSide(1)->withQueryString();


        // --- KIRIM SEMUA DATA BARU KE VIEW ---
        return view('personal::catatan.index', compact(
            'catatans', 
            'saldoAkhir', 
            'totalPemasukan', 
            'totalPengeluaran',
            'categories',
            'totalWallet',
            'totalBank',
            'totalEWallet',
            'totalTabungan',
            'totalRencana',
            'pinnedRencanas',
        ));
    }

    public function create()
    {
        $categories = Category::orderBy('tipe')->get();
        $rencanas = Rencana::where('user_id', Auth::id())->where('status', 'berjalan')->get();
        return view('personal::catatan.create', compact('categories', 'rencanas'));
    }
    
    public function show(Catatan $catatan)
    {
        return view('personal::catatan.show', compact('catatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'deskripsi' => 'required|string|max:255',
            'tipe' => 'required|in:pemasukan,pengeluaran',
            'jumlah' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'custom_category' => 'nullable|string|max:255',
            'alokasi' => [
                'nullable',
                Rule::in(['rencana', 'media']),
            ],
            'rencana_id' => 'required_if:alokasi,rencana|nullable|exists:rencanas,id',
            'media' => [
                Rule::requiredIf(function () use ($request) {
                    return $request->input('tipe') === 'pengeluaran' || $request->input('alokasi') === 'media';
                }),
                'nullable',
                Rule::in(['wallet', 'bank', 'e-wallet', 'tabungan']),
            ],
        ]);

        DB::transaction(function () use ($request) {
            $categoryId = $request->input('category_id');
            $customCategory = null;
            $tipe = $request->input('tipe');
            $lainnyaCategory = Category::where('nama', 'Lainnya...')->where('tipe', $tipe)->first();
            if ($lainnyaCategory && $categoryId == $lainnyaCategory->id) {
                $customCategory = $request->input('custom_category');
            }
            
            $catatan = $request->user()->catatan()->create([
                'deskripsi' => $request->input('deskripsi'),
                'jumlah' => $request->input('jumlah'),
                'category_id' => $categoryId,
                'custom_category' => $customCategory,
                'alokasi' => $request->input('alokasi'),
                'rencana_id' => $request->input('alokasi') === 'rencana' ? $request->input('rencana_id') : null,
                'media' => $request->input('media'),
            ]);

            if ($catatan->alokasi === 'rencana' && $catatan->rencana_id) {
                $rencana = Rencana::find($catatan->rencana_id);
                if ($rencana && $rencana->user_id == Auth::id()) {
                    $newTotal = $rencana->jumlah_terkumpul + $catatan->jumlah;
                    $rencana->increment('jumlah_terkumpul', $catatan->jumlah);
                    if ($newTotal >= $rencana->target_jumlah) {
                        $rencana->update(['status' => 'selesai']);
                    }
                }
            }
        });

        return redirect()->route('catatan.index')->with('success', 'Catatan berhasil ditambahkan!');
    }

    public function edit(Catatan $catatan)
    {
        abort_if($catatan->user_id !== Auth::id(), 403);
        
        $categories = Category::orderBy('tipe')->get();
        $rencanas = Rencana::where('user_id', Auth::id())->whereIn('status', ['berjalan', 'selesai'])
                        ->orWhere('id', $catatan->rencana_id)
                        ->get();
                        
        return view('personal::catatan.edit', compact('catatan', 'categories', 'rencanas'));
    }

    public function update(Request $request, Catatan $catatan)
    {
        $request->validate([
            'deskripsi' => 'required|string|max:255',
            'tipe' => 'required|in:pemasukan,pengeluaran',
            'jumlah' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'custom_category' => 'nullable|string|max:255',
            'alokasi' => [
                'nullable',
                Rule::in(['rencana', 'media']),
            ],
            'rencana_id' => 'required_if:alokasi,rencana|nullable|exists:rencanas,id',
            'media' => [
                Rule::requiredIf(function () use ($request) {
                    return $request->input('tipe') === 'pengeluaran' || $request->input('alokasi') === 'media';
                }),
                'nullable',
                Rule::in(['wallet', 'bank', 'e-wallet', 'tabungan']),
            ],
        ]);
        
        DB::transaction(function () use ($request, $catatan) {
            $oldAmount = $catatan->jumlah;
            $oldRencanaId = $catatan->rencana_id;

            // Logika "Lainnya..."
            $categoryId = $request->input('category_id');
            $customCategory = null;
            $tipe = $request->input('tipe');
            $lainnyaCategory = Category::where('nama', 'Lainnya...')->where('tipe', $tipe)->first();
            if ($lainnyaCategory && $categoryId == $lainnyaCategory->id) {
                $customCategory = $request->input('custom_category');
            }
            
            $catatan->update([
                'deskripsi' => $request->input('deskripsi'),
                'jumlah' => $request->input('jumlah'),
                'category_id' => $categoryId,
                'custom_category' => $customCategory,
                'alokasi' => $request->input('alokasi'),
                'rencana_id' => $request->input('alokasi') === 'rencana' ? $request->input('rencana_id') : null,
                'media' => $request->input('media'),
            ]);

            $newRencanaId = $catatan->rencana_id;
            $newAmount = $catatan->jumlah;

            if ($oldRencanaId) {
                $oldRencana = Rencana::find($oldRencanaId);
                if ($oldRencana) {
                    $newTotal = $oldRencana->jumlah_terkumpul - $oldAmount;
                    $oldRencana->decrement('jumlah_terkumpul', $oldAmount);
                    if ($newTotal < $oldRencana->target_jumlah) {
                        $oldRencana->update(['status' => 'berjalan']);
                    }
                }
            }

            if ($newRencanaId) {
                $newRencana = Rencana::find($newRencanaId);
                if ($newRencana) {
                    $newTotal = $newRencana->jumlah_terkumpul + $newAmount;
                    $newRencana->increment('jumlah_terkumpul', $newAmount);
                    if ($newTotal >= $newRencana->target_jumlah) {
                        $newRencana->update(['status' => 'selesai']);
                    }
                }
            }
        });

        return redirect()->route('catatan.index')->with('success', 'Catatan berhasil diperbarui!');
    }

    public function destroy(Catatan $catatan)
    {
        DB::transaction(function () use ($catatan) {
        $amount = $catatan->jumlah;
        $rencanaId = $catatan->rencana_id;
    
        $catatan->delete();
    
        if ($rencanaId) {
            $rencana = Rencana::find($rencanaId);
            if ($rencana) {
                $newTotal = $rencana->jumlah_terkumpul - $amount;
                $rencana->decrement('jumlah_terkumpul', $amount);
                if ($newTotal < $rencana->target_jumlah) {
                    $rencana->update(['status' => 'berjalan']);
                }
            }
        }
    });
    
    return redirect()->route('catatan.index')->with('success', 'Catatan berhasil dihapus!');
    }
}