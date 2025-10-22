<?php

namespace App\Http\Controllers\personal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Catatan;
use App\Models\Rencana;
use App\Mail\SendOtpMail; 
use Illuminate\Support\Facades\Mail; 
use Carbon\Carbon; 
use Illuminate\Support\Facades\Hash; 
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Log; 

class UserProfileController extends Controller
{
    // ... (method profile, account, appearance tetap sama) ...
     /**
     * Menampilkan halaman profil user.
     */
    public function profile()
    {
        $user = Auth::user();
        $catatanCount = Catatan::where('user_id', $user->id)->count();
        $rencanaCount = Rencana::where('user_id', $user->id)->count();

        $catatanActiveDates = Catatan::where('user_id', $user->id)
            ->selectRaw('DATE(created_at) as activity_date')
            ->union(
                Catatan::where('user_id', $user->id)
                       ->selectRaw('DATE(updated_at) as activity_date')
            )
            ->distinct()
            ->pluck('activity_date');

        $rencanaActiveDates = Rencana::where('user_id', $user->id)
            ->selectRaw('DATE(created_at) as activity_date')
            ->union(
                Rencana::where('user_id', $user->id)
                       ->selectRaw('DATE(updated_at) as activity_date')
            )
            ->distinct()
            ->pluck('activity_date');

        $totalHariAktif = $catatanActiveDates
            ->merge($rencanaActiveDates)
            ->unique()
            ->count();

        return view('personal::profile.index', compact(
            'user',
            'catatanCount',
            'rencanaCount',
            'totalHariAktif'
        ));
    }

    /**
     * Menampilkan halaman pengaturan akun.
     */
    public function account()
    {
        $user = Auth::user();
        return view('personal::settings.account', compact('user'));
    }

    /**
     * Menampilkan halaman pengaturan tampilan.
     */
    public function appearance()
    {
        $user = Auth::user();
        return view('personal::settings.appearance');
    }

    /**
     * Mengirim OTP untuk verifikasi perubahan akun (menyimpan di tabel users).
     */
    public function sendAccountOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $targetEmail = $request->email;
        $user = Auth::user();

        if ($targetEmail !== $user->email) {
            return response()->json(['message' => 'Email target tidak sesuai.'], 400);
        }

        try {
            $otp = rand(100000, 999999);
            $expiresAt = Carbon::now()->addMinutes(5); // OTP valid 5 menit

            // Simpan OTP dan expiry langsung ke user
            $user->verification_code = $otp;
            $user->verification_code_expires_at = $expiresAt;
            $user->save(); // Simpan perubahan ke database

            Mail::to($targetEmail)->send(new SendOtpMail($otp));

            return response()->json([
                'message' => 'Kode OTP telah dikirim ke ' . $targetEmail,
                'timer' => 60 // Timer UI
            ]);

        } catch (\Exception $e) {
            Log::error('Gagal kirim OTP pengaturan akun: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal mengirim OTP. Coba lagi nanti.'], 500);
        }
    }

    /**
     * Memperbarui pengaturan akun (password) menggunakan OTP dari tabel users.
     */
    public function updateAccount(Request $request)
    {
        $user = Auth::user();

        /** @var \App\Models\User $user */

        $otpRequired = $request->filled('password'); // OTP wajib jika password diisi

        $request->validate([
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'otp' => [$otpRequired ? 'required' : 'nullable', 'digits:6'],
        ], [
            'otp.required' => 'Kode OTP wajib diisi untuk mengubah password.',
        ]);

        // --- Verifikasi OTP jika diperlukan ---
        if ($otpRequired) {
            // Ambil data user TERBARU dari DB untuk memastikan expiry belum diubah proses lain
            $user = $user->fresh();

            if (!$user->verification_code ||
                $user->verification_code != $request->otp ||
                !$user->verification_code_expires_at || // Pastikan expiry tidak null
                Carbon::now()->isAfter($user->verification_code_expires_at))
            {
                // Hapus OTP jika salah/kadaluarsa 
                 $user->verification_code = null;
                 $user->verification_code_expires_at = null;
                 $user->save();
                return back()->withErrors(['otp' => 'Kode OTP tidak valid atau telah kedaluwarsa.'])->withInput();
            }
        }


        $changesMade = false;

        // Logika Update Password
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            // Hapus OTP setelah berhasil digunakan
            $user->verification_code = null;
            $user->verification_code_expires_at = null;
            $changesMade = true;
        }

        if ($changesMade) {
            $user->save();
            return back()->with('success', 'Password berhasil diperbarui.');
        } else {
             // Jika tidak ada password baru diisi
            return back()->with('info', 'Tidak ada perubahan password disimpan.');
        }
    }
}