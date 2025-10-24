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
use Illuminate\Http\JsonResponse;
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
    public function profile(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;

        // Ambil data awal (tahun ini, bulan ini) - bisa dibuat lebih efisien
        // dengan memanggil getProfileData internal atau hanya mengambil data dasar
        $initialData = $this->getProfileData($request)->getData(true); // Ambil data awal

        // Ambil statistik dasar (ini tidak perlu dinamis)
        $catatanCount = Catatan::where('user_id', $userId)->count();
        $rencanaCount = Rencana::where('user_id', $userId)->count();
        $totalHariAktif = count($initialData['activeDates'] ?? []); // Hitung dari data awal

        return view('personal::profile.index', [
            'user' => $user,
            'catatanCount' => $catatanCount,
            'rencanaCount' => $rencanaCount,
            'totalHariAktif' => $totalHariAktif,
            'initialData' => $initialData // Kirim data awal ke view
        ]);
    }

    public function getProfileData(Request $request): JsonResponse // <-- Tambahkan tipe return JsonResponse
    {
        $user = Auth::user();
        $userId = $user->id;

        // --- Ambil Parameter Tahun & Bulan dari Request ---
        $selectedYear = $request->input('year', now()->year);
        if (!is_numeric($selectedYear) || $selectedYear < 2000 || $selectedYear > now()->year + 5) {
            $selectedYear = now()->year;
        }

        $selectedMonthInput = $request->input('month', now()->format('Y-m'));
        try {
            $selectedMonthCarbon = Carbon::createFromFormat('Y-m', $selectedMonthInput)->startOfMonth();
        } catch (\Exception $e) {
            $selectedMonthCarbon = now()->startOfMonth();
        }
        $selectedMonth = $selectedMonthCarbon->format('Y-m');

        // --- Ambil Tanggal Aktif (sama seperti di method profile) ---
        $catatanActiveDates = Catatan::where('user_id', $userId)
            ->selectRaw('DATE(created_at) as activity_date')
            ->union(Catatan::where('user_id', $userId)->selectRaw('DATE(updated_at) as activity_date'))
            ->distinct()->pluck('activity_date');

        $rencanaActiveDates = Rencana::where('user_id', $userId)
            ->selectRaw('DATE(created_at) as activity_date')
            ->union(Rencana::where('user_id', $userId)->selectRaw('DATE(updated_at) as activity_date'))
            ->distinct()->pluck('activity_date');

        $activeDates = $catatanActiveDates->merge($rencanaActiveDates)->unique()->sort()->values();

        // --- DATA CHART TAHUNAN ---
        $startOfYear = Carbon::create($selectedYear, 1, 1)->startOfYear();
        $endOfYear = Carbon::create($selectedYear, 12, 31)->endOfYear();

        $aktivitasPerBulan = $activeDates
            ->filter(function ($date) use ($startOfYear, $endOfYear) {
                 try { $d = Carbon::parse($date); return $d->isBetween($startOfYear, $endOfYear); } catch (\Exception $e) { return false; }
            })
            ->groupBy(fn($date) => Carbon::parse($date)->format('Y-m'))
            ->map->count();

        $monthsOfYear = collect();
        for ($m = 1; $m <= 12; $m++) {
            $monthsOfYear->push(Carbon::create($selectedYear, $m, 1)->format('Y-m'));
        }

        $activityChartData = $monthsOfYear->map(function ($monthFormatYm) use ($aktivitasPerBulan) {
            return [
                'month' => Carbon::createFromFormat('Y-m', $monthFormatYm)->format('M'),
                'total' => $aktivitasPerBulan->get($monthFormatYm, 0),
            ];
        });

        // --- DATA UNTUK KALENDER (Bulan yang Dipilih) ---
        // Kita hanya perlu activeDates, pemrosesan kalender dilakukan di Frontend
        $calendarInfo = [
            'monthName' => $selectedMonthCarbon->isoFormat('MMMM YYYY'),
            'prevMonthUrlParam' => $selectedMonthCarbon->copy()->subMonth()->format('Y-m'),
            'nextMonthUrlParam' => $selectedMonthCarbon->copy()->addMonth()->format('Y-m'),
            'canGoNextMonth' => $selectedMonthCarbon->lt(now()->startOfMonth()), // Bisa ke bulan depan?
        ];

        // --- Kembalikan data sebagai JSON ---
        return response()->json([
            'success' => true,
            'selectedYear' => $selectedYear,
            'selectedMonth' => $selectedMonth, // YYYY-MM
            'activityChartData' => $activityChartData, // Data untuk chart
            'activeDates' => $activeDates, // Array ['YYYY-MM-DD', ...] untuk kalender
            'calendarInfo' => $calendarInfo, // Info tambahan kalender
        ]);
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
        return view('personal::settings.appearance', compact('user'));
    }

    public function updateEmail(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
        ]);

        $emailChanged = $validated['email'] !== $user->email;
        $nameChanged = $validated['name'] !== $user->name;

        $user->name = $validated['name'];

        if ($emailChanged) {
            $user->email = $validated['email'];
            $user->email_verified_at = null; // Tandai sebagai belum terverifikasi
            $user->verification_code = null; // Hapus kode OTP lama
            $user->verification_code_expires_at = null;
            $user->save();
            
            return back()->with('success', 'Email berhasil diubah. Silakan kirim kode verifikasi untuk memverifikasi email baru Anda.');
        
        } elseif ($nameChanged) {
            $user->save();
            return back()->with('success', 'Nama pengguna berhasil diperbarui.');
        }

        return back()->with('info', 'Tidak ada perubahan yang disimpan.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('Password saat ini tidak cocok.');
                    }
                },
            ],
            'password' => [
                'required',
                'string',
                Password::min(8), // Tambahkan rules yang lebih kuat jika perlu
                'confirmed',
            ],
        ]);

        $user->password = Hash::make($validated['password']);
        $user->save();

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    public function verifyEmail(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);

        if ($user->verification_code == $validated['otp'] && 
            $user->verification_code_expires_at && 
            now()->isBefore($user->verification_code_expires_at)) {
            
            $user->email_verified_at = now();
            $user->verification_code = null;
            $user->verification_code_expires_at = null;
            $user->save();

            return back()->with('success', 'Email berhasil diverifikasi.');
        }

        return back()->withErrors(['otp' => 'Kode OTP tidak valid atau telah kedaluwarsa.'])
                    ->with('otp_sent_timer', 60);
    }

    public function sendEmailVerificationOtp(Request $request)
    {
        $user = Auth::user();

        if ($user->email_verified_at) {
            return back()->with('info', 'Email Anda sudah terverifikasi.');
        }

        $otp = rand(100000, 999999);
        $user->verification_code = $otp;
        $user->verification_code_expires_at = now()->addMinutes(10);
        
        try {
            // INI LOGIKA PENGIRIMAN EMAILNYA
            Mail::to($user->email)->send(new SendOtpMail($otp));
            
            // Simpan OTP ke DB HANYA jika email berhasil terkirim
            $user->save(); 
            
            return back()->with('otp_sent_timer', 60) // 60 detik
                         ->with('success', 'Kode OTP telah dikirim ke ' . $user->email);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim OTP email (send): ' . $e->getMessage());
            return back()->withErrors(['otp' => 'Gagal mengirim OTP. Pastikan konfigurasi email Anda benar.']);
        }
    }

    public function resendEmailOtp(Request $request)
    {
        $user = Auth::user();

        if ($user->email_verified_at) {
            return back()->with('info', 'Email Anda sudah terverifikasi.');
        }

        $otp = rand(100000, 999999);
        $user->verification_code = $otp;
        $user->verification_code_expires_at = now()->addMinutes(10);
        
        try {
            // INI LOGIKA PENGIRIMAN EMAILNYA
            Mail::to($user->email)->send(new SendOtpMail($otp));
            
            // Simpan OTP ke DB HANYA jika email berhasil terkirim
            $user->save(); 
            
            return back()->with('otp_sent_timer', 60) // 60 detik
                         ->with('success', 'Kode OTP baru telah dikirim ke ' . $user->email);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim OTP email (resend): ' . $e->getMessage());
            return back()->withErrors(['otp' => 'Gagal mengirim ulang OTP. Pastikan konfigurasi email Anda benar.']);
        }
    }
}