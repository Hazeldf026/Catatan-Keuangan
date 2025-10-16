<?php

namespace App\Http\Controllers;

use App\Models\Rencana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RencanaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         // Query dasar untuk mengambil rencana milik user
        $query = Rencana::where('user_id', Auth::id());

        // 1. Logika untuk FILTER berdasarkan status
        if ($request->has('status') && in_array($request->status, ['berjalan', 'selesai', 'dibatalkan'])) {
            $query->where('status', $request->status);
        }

        // 2. Logika untuk SORTING (Pengurutan)
        $sortBy = $request->get('sort_by', 'created_at'); // Default: urutkan berdasarkan terbaru
        $order = $request->get('order', 'desc');

        switch ($sortBy) {
            case 'target':
                $query->orderBy('target_jumlah', $order);
                break;
            case 'progress':
                // Mengurutkan berdasarkan persentase progress (perhitungan matematis)
                // Pastikan tidak ada pembagian dengan nol jika target_jumlah adalah 0
                $query->orderBy(DB::raw('CASE WHEN target_jumlah > 0 THEN (jumlah_terkumpul / target_jumlah) ELSE 0 END'), $order);
                break;
            case 'date':
                // Mengurutkan berdasarkan tanggal, null akan ditaruh di akhir
                $query->orderByRaw('target_tanggal IS NULL, target_tanggal ' . $order);
                break;
            default:
                $query->orderBy('created_at', $order);
                break;
        }

        // Ambil data dengan paginasi dan pertahankan parameter query string saat pindah halaman
        $rencanas = $query->paginate(9)->withQueryString();

        // Kirim data ke view
        return view('pages.rencana.index', compact('rencanas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Tampilkan halaman form untuk membuat rencana baru
        return view('pages.rencana.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data yang masuk dari form
        $request->validate([
            'nama' => 'required|string|max:255',
            'target_jumlah' => 'required|numeric|min:0',
            'target_tanggal' => 'nullable|date',
            'deskripsi' => 'nullable|string',
        ]);

        // Simpan data ke database
        Auth::user()->rencanas()->create($request->all());

        // Redirect kembali ke halaman index rencana dengan notifikasi sukses
        return redirect()->route('rencana.index')->with('success', 'Rencana baru berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rencana $rencana)
    {
        // Pastikan pengguna hanya bisa melihat rencananya sendiri
        abort_if($rencana->user_id !== Auth::id(), 403);

        // Ambil riwayat catatan/transaksi yang terkait dengan rencana ini
        $catatans = $rencana->catatans()->latest()->paginate(10);

        // Tampilkan halaman detail rencana
        return view('pages.rencana.show', compact('rencana', 'catatans'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rencana $rencana)
    {
        // Pastikan pengguna hanya bisa mengedit rencananya sendiri
        abort_if($rencana->user_id !== Auth::id(), 403);

        // Tampilkan halaman form edit
        return view('pages.rencana.edit', compact('rencana'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rencana $rencana)
    {
        // Pastikan pengguna hanya bisa mengupdate rencananya sendiri
        abort_if($rencana->user_id !== Auth::id(), 403);

        // Validasi data
        $request->validate([
            'nama' => 'required|string|max:255',
            'target_jumlah' => 'required|numeric|min:0',
            'target_tanggal' => 'nullable|date',
            'deskripsi' => 'nullable|string',
        ]);

        // Update data di database
        $rencana->update($request->all());

        return redirect()->route('rencana.index')->with('success', 'Rencana berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rencana $rencana)
    {
        // Pastikan pengguna hanya bisa menghapus rencananya sendiri
        abort_if($rencana->user_id !== Auth::id(), 403);

        // Hapus rencana dari database
        $rencana->delete();

        return redirect()->route('rencana.index')->with('success', 'Rencana berhasil dihapus!');
    }

    public function cancel(Rencana $rencana)
    {
        // Pastikan pengguna hanya bisa membatalkan rencananya sendiri
        abort_if($rencana->user_id !== Auth::id(), 403);

        // Hanya batalkan jika statusnya masih 'berjalan'
        if ($rencana->status === 'berjalan') {
            $rencana->update(['status' => 'dibatalkan']);
        }

        return redirect()->route('rencana.show', $rencana)->with('success', 'Rencana telah dibatalkan.');
    }
}
