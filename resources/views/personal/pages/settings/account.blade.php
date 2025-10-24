{{-- resources/views/personal/pages/settings/account.blade.php --}}

<x-personal::settings-layout title="Pengaturan Akun">

    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 border-b pb-4 dark:border-gray-700">Pengaturan Akun</h2>

        {{-- 
          BAGIAN 1: FORM UPDATE NAMA & EMAIL
          Form ini sekarang ditutup sebelum bagian OTP
        --}}
        <form action="{{ route('settings.account.updateEmail') }}" method="POST">
            @csrf
            <fieldset class="border border-gray-300 dark:border-gray-600 p-4 rounded-lg">
                <legend class="text-lg font-medium text-gray-900 dark:text-white px-2">
                    Informasi Akun
                </legend>

                <div class="space-y-4 mt-2">
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Pengguna</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="email" class="block text-sm font-medium text-gray-900 dark:text-white">Email</label>
                            @if ($user->email_verified_at)
                                <span class="text-xs bg-green-100 text-green-800 font-medium px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">
                                    <svg class="inline w-3 h-3 me-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.707-9.293a1 1 0 0 0-1.414-1.414L9 10.586 7.707 9.293a1 1 0 0 0-1.414 1.414l2 2a1 1 0 0 0 1.414 0l4-4Z" clip-rule="evenodd"/>
                                    </svg>
                                    Terverifikasi
                                </span>
                            @else
                                <span class="text-xs bg-yellow-100 text-yellow-800 font-medium px-2.5 py-0.5 rounded-full dark:bg-yellow-900 dark:text-yellow-300">
                                    <svg class="inline w-3 h-3 me-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm0-1-6.2-3.4A1 1 0 0 1 3 12.6V5.4a1 1 0 0 1 1.4-.8L10 8.3l5.6-3.7A1 1 0 0 1 17 5.4v7.2a1 1 0 0 1-.6.9L10 17Zm-5-4.5 5 2.8 5-2.8V6.9L10 9.7l-5-2.8v4.6Z" clip-rule="evenodd"/>
                                    </svg>
                                    Belum Terverifikasi
                                </span>
                            @endif
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-end mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        Simpan Perubahan
                    </button>
                </div>
            </fieldset>
        </form>
        {{-- FORM 1 BERAKHIR DI SINI --}}


        {{-- 
          BAGIAN 2: BLOK VERIFIKASI OTP
          Sekarang berada di luar form pertama, sebagai bloknya sendiri
        --}}
        @if (!$user->email_verified_at)
            {{-- Saya tambahkan fieldset baru agar UI tetap rapi --}}
            <fieldset class="border border-yellow-300 dark:border-yellow-600 p-4 rounded-lg mt-6">
                <legend class="text-lg font-medium text-yellow-800 dark:text-yellow-300 px-2">
                    Verifikasi Email
                </legend>

                <div x-data="{
                        timer: {{ session('otp_sent_timer', 0) }},
                        loadingSend: false
                     }"
                     x-init="
                        if (timer > 0) {
                            let interval = setInterval(() => {
                                timer--;
                                if (timer <= 0) {
                                    clearInterval(interval);
                                }
                            }, 1000);
                        }
                     "
                     class="mt-2">

                    {{-- Tombol "Kirim Kode" (Form 2) --}}
                    <div x-show="timer <= 0" x-cloak>
                        <form action="{{ route('settings.account.sendEmailOtp') }}" method="POST" @submit="loadingSend = true">
                            @csrf
                            <button type="submit" :disabled="loadingSend" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500 disabled:text-gray-400 disabled:cursor-wait"
                                    :class="{ 'opacity-50': loadingSend }">
                                <span x-show="!loadingSend">Kirim Kode Verifikasi</span>
                                <span x-show="loadingSend">Mengirim...</span>
                            </button>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Tekan untuk mengirim kode verifikasi ke <strong>{{ $user->email }}</strong>.
                            </p>
                            @error('otp') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </form>
                    </div>

                    {{-- Input OTP (Form 3) --}}
                    <div x-show="timer > 0" x-cloak>
                        <form action="{{ route('settings.account.verifyEmail') }}" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <label for="otp" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode OTP</label>
                                <div class="flex space-x-2">
                                    <input type="text" id="otp" name="otp" inputmode="numeric" pattern="\d{6}" maxlength="6" required
                                           class="flex-grow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="123456">
                                    
                                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                        Verifikasi
                                    </button>
                                </div>
                                @error('otp') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </form>

                        {{-- Link "Kirim Ulang" (Form 4) --}}
                        <form action="{{ route('settings.account.resendEmailOtp') }}" method="POST" @submit="loadingSend = true" x-show="timer <= 0" x-cloak class="mt-2">
                            @csrf
                            <button type="submit" :disabled="loadingSend" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500 disabled:text-gray-400 disabled:cursor-wait"
                                    :class="{ 'opacity-50': loadingSend }">
                                <span x-show="!loadingSend">Kirim Ulang OTP</span>
                                <span x-show="loadingSend">Mengirim...</span>
                            </button>
                        </form>
                        
                        {{-- Teks timer --}}
                        <p x-show="timer > 0" class="text-sm text-gray-500 dark:text-gray-400 mt-2" x-cloak>
                            Kirim ulang kode dalam <span x-text="timer" class="font-medium"></span> detik.
                        </p>
                    </div>

                </div>
            </fieldset>
        @endif


        {{-- 
          BAGIAN 3: FORM UPDATE PASSWORD
          (Form 5, ini sudah benar dari awal)
        --}}
        <form action="{{ route('settings.account.updatePassword') }}" method="POST" class="mt-6">
            @csrf
            <fieldset class="border border-gray-300 dark:border-gray-600 p-4 rounded-lg" x-data="{ showPassword: false, showNewPassword: false, showConfirm: false }">
                <legend class="text-lg font-medium text-gray-900 dark:text-white px-2">
                    Ubah Password
                </legend>
                
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 mb-4">
                    Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.
                </p>

                <div class="space-y-4">
                    <div>
                        <label for="current_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password Saat Ini</label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" id="current_password" name="current_password" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 px-3 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                <svg x-show="!showPassword" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                <svg x-show="showPassword" x-cloak class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.5 7.5l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L18 18" /></svg>
                            </button>
                        </div>
                        @error('current_password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password Baru</label>
                        <div class="relative">
                            <input :type="showNewPassword ? 'text' : 'password'" id="password" name="password" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                            <button type="button" @click="showNewPassword = !showNewPassword" class="absolute inset-y-0 right-0 px-3 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                <svg x-show="!showNewPassword" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                <svg x-show="showNewPassword" x-cloak class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.5 7.5l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L18 18" /></svg>
                            </button>
                        </div>
                        @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Konfirmasi Password Baru</label>
                        <div class="relative">
                            <input :type="showConfirm ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                             <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 px-3 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                <svg x-show="!showConfirm" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                <svg x-show="showConfirm" x-cloak class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.5 7.5l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L18 18" /></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-4">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        Ubah Password
                    </button>
                </div>
            </fieldset>
        </form>

    </div>
</x-personal::settings-layout>