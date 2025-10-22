{{-- resources/views/personal/pages/settings/account.blade.php --}}

<x-personal::settings-layout title="Pengaturan Akun">

    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 border-b pb-4 dark:border-gray-700">Pengaturan Akun</h2>

        <div x-data="{
                otpSent: false,
                timer: 0,
                emailForOtp: '{{ $user->email }}',
                errorMessage: '',
                successMessage: '',
                showPassword: false, // State untuk show/hide password
                loadingOtp: false, // State loading kirim OTP
                initTimer() {
                    let interval;
                    this.$watch('timer', value => {
                        if (value > 0 && !interval) {
                            interval = setInterval(() => { this.timer--; }, 1000);
                        } else if (value <= 0 && interval) {
                            clearInterval(interval);
                            interval = null;
                        }
                    });
                }
             }"
             x-init="initTimer()">

            <form action="{{ route('settings.account.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Bagian Email & OTP --}}
                <fieldset class="border border-gray-300 dark:border-gray-700 p-4 rounded-md">
                    <legend class="text-sm font-medium text-gray-700 dark:text-gray-300 px-2">Verifikasi & Keamanan</legend>
                    <div class="space-y-4">
                         <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Terdaftar</label>
                            <input type="email" id="email" name="email_current" value="{{ $user->email }}" disabled
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400 text-gray-500 sm:text-sm cursor-not-allowed">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Kode OTP akan dikirim ke email ini untuk verifikasi perubahan.</p>
                        </div>

                        {{-- Tombol Kirim OTP --}}
                        <div>
                            <button type="button" @click="
                                errorMessage = ''; successMessage = ''; loadingOtp = true;
                                fetch('{{ route('settings.account.send_otp') }}', {
                                    method: 'POST',
                                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' },
                                    body: JSON.stringify({ email: emailForOtp })
                                }).then(async res => {
                                     const data = await res.json();
                                     if (!res.ok) throw new Error(data.message || 'Gagal mengirim OTP');
                                     return data;
                                }).then(data => {
                                    timer = data.timer || 60;
                                    otpSent = true;
                                    successMessage = data.message || 'OTP Terkirim!';
                                }).catch(err => {
                                    console.error(err);
                                    errorMessage = err.message || 'Gagal mengirim OTP.';
                                    otpSent = false; timer = 0;
                                }).finally(() => {
                                    loadingOtp = false;
                                });
                             " :disabled="timer > 0 || loadingOtp"
                                class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-500 disabled:text-gray-400 disabled:cursor-not-allowed">
                                <svg x-show="loadingOtp" class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"> <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle> <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path> </svg>
                                <span x-text="timer > 0 ? `Kirim Ulang OTP (${timer}s)` : (loadingOtp ? 'Mengirim...' : 'Kirim Kode OTP Verifikasi')"></span>
                            </button>
                            <p x-show="errorMessage" x-text="errorMessage" class="mt-1 text-xs text-red-600 dark:text-red-400"></p>
                            <p x-show="successMessage && !errorMessage" x-text="successMessage" class="mt-1 text-xs text-green-600 dark:text-green-400"></p>
                        </div>

                        {{-- Input OTP (muncul setelah dikirim) --}}
                        <div x-show="otpSent" x-transition>
                            <label for="otp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kode OTP</label>
                            <input type="text" id="otp" name="otp" pattern="[0-9]{6}" maxlength="6"
                                   x-bind:required="otpSent"
                                   class="block w-full max-w-xs px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm"
                                   placeholder="Masukkan 6 digit OTP">
                            @error('otp') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                    </div>
                </fieldset>

                {{-- Bagian Ubah Password --}}
                <fieldset class="border border-gray-300 dark:border-gray-700 p-4 rounded-md">
                     <legend class="text-sm font-medium text-gray-700 dark:text-gray-300 px-2">Ubah Password</legend>
                     <p class="text-xs text-gray-500 dark:text-gray-400 mb-4 px-2">Kosongkan jika tidak ingin mengubah password. Membutuhkan verifikasi OTP.</p>
                     <div class="space-y-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password Baru</label>
                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'"
                                       id="password" name="password" autocomplete="new-password"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm pr-10"
                                       placeholder="Masukkan password baru (min. 8 karakter)">
                                <button type="button" @click="showPassword = !showPassword"
                                        class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                    <svg x-show="!showPassword" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /> <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /> </svg>
                                    <svg x-show="showPassword" x-cloak class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" /> </svg>
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Minimal 8 karakter.</p>
                            @error('password') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Konfirmasi Password Baru</label>
                            <div class="relative">
                                {{-- [PERBAIKAN] Ganti type jadi :type --}}
                                <input :type="showPassword ? 'text' : 'password'"
                                       id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm pr-10"
                                       placeholder="Ulangi password baru">
                                {{-- [BARU] Tombol Show/Hide Password --}}
                                <button type="button" @click="showPassword = !showPassword"
                                        class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                    {{-- Ikon Mata Terbuka --}}
                                    <svg x-show="!showPassword" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /> <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /> </svg>
                                    {{-- Ikon Mata Tertutup --}}
                                     <svg x-show="showPassword" x-cloak class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" /> </svg>
                                </button>
                            </div>
                        </div>
                     </div>
                </fieldset>

                {{-- Tombol Submit --}}
                <div class="flex justify-end pt-4">
                    <button type="submit"
                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-personal::settings-layout>