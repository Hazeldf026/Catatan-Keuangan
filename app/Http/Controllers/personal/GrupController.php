<?php

namespace App\Http\Controllers\personal;

use App\Http\Controllers\Controller;
use App\Models\Grup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GrupController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $grups = $user->grups()->with('users')->get();
        
        return view('personal::grup.index', compact('grups'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();

        // Buat grup baru
        $grup = Grup::create([
            'user_id' => $user->id,
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'grup_code' => $this->generateUniqueCode(), 
        ]);

        $grup->users()->attach($user->id, ['role' => 'admin']);

        return redirect()->route('grup.index')->with('success', 'Grup berhasil dibuat!');
    }

    public function findGrupByCode(Request $request)
    {
        $request->validate(['grup_code' => 'required|string|size:8']); // Kode harus 8 digit

        $user = Auth::user();
        $grupCode = strtoupper($request->grup_code); // Pastikan uppercase

        $grup = Grup::where('grup_code', $grupCode)->withCount('users')->first();

        // Kasus 1: Kode tidak ditemukan
        if (!$grup) {
            return response()->json(['error' => 'Kode grup tidak ditemukan.'], 404);
        }

        // Kasus 2: User sudah jadi anggota
        if ($grup->users()->where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'Kamu sudah bergabung dengan grup ini.'], 409); // 409 Conflict
        }

        // Kasus 3: Grup ditemukan & user belum join
        return response()->json([
            'id' => $grup->id,
            'nama' => $grup->nama,
            'deskripsi' => $grup->deskripsi,
            'grup_code' => $grup->grup_code,
            'users_count' => $grup->users_count,
            'owner_name' => $grup->owner->name ?? 'N/A', // Ambil nama pemilik jika relasi 'owner' ada
        ]);
    }

    /**
     * Memasukkan user ke grup menggunakan kode.
     */
    public function join(Request $request)
    {
        // Sekarang validasi ID grup, bukan kode
        $request->validate([
            'grup_id' => [
                'required',
                Rule::exists('grups', 'id'), // Pastikan ID grup valid
            ],
        ]);

        $user = Auth::user();
        $grup = Grup::find($request->grup_id);

        // Double check (seharusnya tidak terjadi jika findGrupByCode benar)
        if (!$grup) {
            return redirect()->route('grup.index')->with('error', 'Grup tidak ditemukan.');
        }
        if ($grup->users()->where('user_id', $user->id)->exists()) {
            return redirect()->route('grup.index')->with('error', 'Kamu sudah bergabung dengan grup ini!');
        }

        // Masukkan user ke grup
        $grup->users()->attach($user->id);

        // [PENTING] Redirect ke halaman catatan grup (buat route placeholder nanti)
        // Gunakan ID grup dalam route
        return redirect()->route('group.catatan.index', ['grup' => $grup->id])
                        ->with('success', 'Berhasil bergabung dengan grup ' . $grup->nama . '!');
    }
    /**
     * Helper untuk membuat kode unik.
     */
    private function generateUniqueCode()
    {
        do {
            $code = Str::upper(Str::random(8));
        } while (Grup::where('grup_code', $code)->exists());

        return $code;
    }
}