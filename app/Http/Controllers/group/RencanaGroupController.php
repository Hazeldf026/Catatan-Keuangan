<?php

namespace App\Http\Controllers\group;

use App\Http\Controllers\Controller;
use App\Models\Grup;
use App\Models\GrupRencana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RencanaGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Grup $grup)
    {
        $rencanas = $grup->grupRencanas()->latest()->paginate(10); // Atau sesuai kebutuhan

        // Cek role user saat ini di grup ini
        $userRole = Auth::user()->getRoleInGroup($grup); // Gunakan helper model

        return view('group::rencana.index', compact('grup', 'rencanas', 'userRole'));
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
    public function store(Request $request, Grup $grup)
    {
        if (!Auth::user()->isAdminInGroup($grup)) {
            return back()->with('error', 'Hanya admin grup yang dapat membuat rencana.');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'target_jumlah' => 'required|numeric|min:1',
            'target_tanggal' => 'nullable|date|after_or_equal:today', // <-- VALIDASI BARU
        ]);

        $grup->grupRencanas()->create([
            'user_id' => Auth::id(),
            'nama' => $validated['nama'],
            'deskripsi' => $validated['deskripsi'],
            'target_jumlah' => $validated['target_jumlah'],
            'target_tanggal' => $validated['target_tanggal'], // <-- SIMPAN TANGGAL
            'status' => 'berjalan',
        ]);

        return back()->with('success', 'Rencana grup berhasil ditambahkan.');
    }
    /**
     * Display the specified resource.
     */
    public function show(Grup $grup, GrupRencana $rencana) // Route model binding
    {
        // Pastikan rencana ini milik grup yang benar (opsional jika route sudah nested)
        if ($rencana->grup_id !== $grup->id) {
            abort(404);
        }
        // Cukup return JSON data rencana
        return response()->json($rencana);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GrupRencana $grupRencana)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grup $grup, GrupRencana $rencana)
    {
        if (!Auth::user()->isAdminInGroup($grup)) {
            return back()->with('error', 'Hanya admin grup yang dapat mengedit rencana.');
        }
        if ($rencana->grup_id !== $grup->id) {
            abort(404);
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'target_jumlah' => 'required|numeric|min:1',
            'target_tanggal' => 'nullable|date|after_or_equal:today', // <-- VALIDASI BARU
        ]);

        // Hapus target_tanggal dari array jika kosong agar tidak menimpa jadi null jika tidak diisi ulang
        if (empty($validated['target_tanggal'])) {
            unset($validated['target_tanggal']);
            // Atau jika ingin bisa menghapus tanggal, biarkan saja
            // $validated['target_tanggal'] = null; // Ini akan set jadi NULL
        }


        $rencana->update($validated);

        return back()->with('success', 'Rencana grup berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grup $grup, GrupRencana $rencana)
    {
        // Otorisasi (Contoh: Hanya admin)
        if (!Auth::user()->isAdminInGroup($grup)) {
            return back()->with('error', 'Hanya admin grup yang dapat menghapus rencana.');
        }
        // Pastikan rencana ini milik grup yang benar
        if ($rencana->grup_id !== $grup->id) {
            abort(404);
        }

        // TODO: Pertimbangkan apa yg terjadi jika ada catatan yg terhubung ke rencana ini?
        $rencana->delete();

        return back()->with('success', 'Rencana grup berhasil dihapus.');
    }
}
