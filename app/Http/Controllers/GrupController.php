<?php

namespace App\Http\Controllers;

use App\Models\Grup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GrupController extends Controller
{
    /**
     * Menampilkan halaman daftar grup yang diikuti user.
     */
    public function index()
    {
        $user = Auth::user();
        // Ambil grup yang diikuti user, dan eager load data 'users' untuk menghitung anggota
        $grups = $user->grups()->with('users')->get();
        
        return view('pages.grup.index', compact('grups'));
    }

    /**
     * Menyimpan grup baru yang dibuat.
     */
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
            'grup_code' => $this->generateUniqueCode(), // Panggil fungsi helper
        ]);

        // Secara otomatis masukkan pembuat grup sebagai anggota pertama
        $grup->users()->attach($user->id);

        return redirect()->route('grup.index')->with('success', 'Grup berhasil dibuat!');
    }

    /**
     * Memasukkan user ke grup menggunakan kode.
     */
    public function join(Request $request)
    {
        $request->validate([
            // Validasi: kode harus ada, dan harus ada di tabel 'grups'
            'grup_code' => [
                'required',
                'string',
                Rule::exists('grups', 'grup_code'),
            ],
        ]);

        $user = Auth::user();
        $grup = Grup::where('grup_code', $request->grup_code)->first();

        // Cek apakah user sudah ada di grup
        if ($grup->users()->where('user_id', $user->id)->exists()) {
            return redirect()->route('grup.index')->with('error', 'Kamu sudah bergabung dengan grup ini!');
        }

        // Jika belum, masukkan user ke grup
        $grup->users()->attach($user->id);

        return redirect()->route('grup.index')->with('success', 'Berhasil bergabung dengan grup ' . $grup->nama . '!');
    }

    /**
     * Helper untuk membuat kode unik.
     */
    private function generateUniqueCode()
    {
        do {
            $code = Str::upper(Str::random(8));
        } while (Grup::where('grup_code', $code)->exists()); // Pastikan kode belum dipakai

        return $code;
    }
}