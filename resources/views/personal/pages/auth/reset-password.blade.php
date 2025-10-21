@extends('personal.app')

@section('start')
    <section class="min-h-screen w-full bg-gray-50 dark:bg-gray-900 flex items-center justify-center p-4">

        <div class="container mx-auto flex flex-col lg:flex-row items-start justify-center gap-12 lg:gap-50">

            {{-- KIRI: ANIMASI LOTTIE --}}
            <div class="w-full max-w-md hidden lg:flex">
                <div id="lottie-container" style="width: 100%;"></div>
            </div>

            {{-- KANAN: KARTU FORM --}}
            <div class="w-full max-w-lg">
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-8">

                    <div class="flex items-center gap-2 mb-3">
                        <img src="/images/Logo.png" alt="Logo" class="w-10 h-10">
                        <span class="text-xl font-bold text-gray-800 dark:text-white">Credix</span>
                    </div>

                    <div class="text-left mb-8">
                        {{-- Judul --}}
                        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">
                            Reset Your Password
                        </h1>
                        {{-- Teks Instruksi --}}
                        <p class="mt-2 text-gray-500 dark:text-gray-400">
                            Choose a new, strong password for your account.
                        </p>
                    </div>
                    
                    <form class="space-y-6" action="{{ route('password.update') }}" method="POST">
                        @csrf

                        {{-- Input tersembunyi untuk token dan email --}}
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="email" value="{{ $email ?? old('email') }}">
                        
                        {{-- Input Password Baru --}}
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-600 dark:text-gray-300">New Password</label>
                            <div x-data="{ showPassword: false }" class="relative">
                                <input :type="showPassword ? 'text' : 'password'" id="password" name="password" placeholder="••••••••" class="block w-full p-2.5 pr-10 text-sm rounded-lg bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 placeholder-gray-400 text-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500" autocomplete="off" required>
                                <div @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                                    {{-- Ikon Mata Terbuka --}}
                                    <svg x-show="!showPassword" class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    {{-- Ikon Mata Tercoret --}}
                                    <svg x-show="showPassword" class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.774 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243l-4.243-4.243" /></svg>
                                </div>
                            </div>
                            @error('password') <div class="text-red-600 pt-2 text-sm">{{$message}}</div> @enderror
                        </div>

                        {{-- Input Konfirmasi Password Baru --}}
                        <div>
                            <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-600 dark:text-gray-300">Confirm New Password</label>
                            <div x-data="{ showPassword: false }" class="relative">
                                <input :type="showPassword ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" placeholder="••••••••" class="block w-full p-2.5 pr-10 text-sm rounded-lg bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 placeholder-gray-400 text-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500" autocomplete="off" required>
                                <div @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                                    {{-- Ikon Mata Terbuka --}}
                                    <svg x-show="!showPassword" class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    {{-- Ikon Mata Tercoret --}}
                                    <svg x-show="showPassword" class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.774 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243l-4.243-4.243" /></svg>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Tombol Submit --}}
                        <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3 text-center dark:focus:ring-blue-800">
                            Reset Password
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </section>
@endsection

@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>
    <script>
        lottie.loadAnimation({
            container: document.getElementById('lottie-container'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: '/animations/Password Authentication.json' 
        });
    </script>
@endpush