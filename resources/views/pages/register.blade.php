@extends('app')

@section('start')
    <section class="min-h-screen w-full flex items-center justify-center bg-gray-50 dark:bg-gray-900 p-4">

    <div class="container mx-auto flex flex-col lg:flex-row items-start justify-center gap-12 lg:gap-50">

        {{-- KIRI: Teks Informatif & Branding --}}
        <div class="w-full max-w-lg text-gray-800 dark:text-white">
            
            {{-- Logo dan Nama Aplikasi Anda --}}
            <div class="flex items-center gap-2 mb-8">
                <img src="/images/Logo.png" alt="Logo" class="w-12"> {{-- Ganti dengan path logo Anda --}}
                <h1 class="text-3xl font-bold">Credix</h1> {{-- Ganti dengan nama aplikasi Anda --}}
            </div>

            {{-- Poin-poin Keunggulan (Gunakan Opsi 1 atau 2 di atas) --}}
            <div class="space-y-6">
                <div class="flex items-start gap-4">
                    <svg class="h-7 w-7 text-blue-500 mt-1 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <div>
                        <h3 class="font-semibold text-lg">Mulai dengan Cepat</h3>
                        <p class="text-gray-500 dark:text-gray-400">Dapatkan gambaran lengkap kondisi keuangan Anda hanya dalam beberapa menit.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <svg class="h-7 w-7 text-blue-500 mt-1 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <div>
                        <h3 class="font-semibold text-lg">Lacak Semuanya</h3>
                        <p class="text-gray-500 dark:text-gray-400">Catat setiap pemasukan dan pengeluaran, dari kopi pagi hingga investasi.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <svg class="h-7 w-7 text-blue-500 mt-1 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <div>
                        <h3 class="font-semibold text-lg">Raih Tujuan Finansial</h3>
                        <p class="text-gray-500 dark:text-gray-400">Dengan data yang akurat, buat anggaran yang lebih cerdas dan capai target Anda lebih cepat.</p>
                    </div>
                </div>
            </div>
            
        </div>

        {{-- KANAN: KARTU FORM REGISTRASI --}}
        <div class="w-full max-w-md bg-white dark:bg-slate-800 rounded-xl shadow-lg p-8">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">
                Create your Account
            </h1>

            <div>
                <a href="#" class="w-full flex items-center justify-center text-gray-700 dark:text-white bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 font-medium rounded-lg text-sm px-5 py-3 text-center">
                    <svg class="w-5 h-5 mr-2" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w.org/2000/svg" viewBox="0 0 488 512"><path fill="currentColor" d="M488 261.8C488 403.3 391.1 504 248 504 110.8 504 0 393.2 0 256S110.8 8 248 8c66.8 0 126 23.4 172.9 61.9l-76.3 64.5C308.6 106.5 280.2 96 248 96c-88.8 0-160.1 71.9-160.1 160.1s71.3 160.1 160.1 160.1c98.2 0 135-70.4 140.8-106.9H248v-85.3h236.1c2.3 12.7 3.9 26.9 3.9 41.4z"></path></svg>
                    Sign up with Google
                </a>
            </div>

            <div class="relative inline-flex items-center justify-center w-full my-6">
                <hr class="w-full h-px bg-gray-200 dark:bg-gray-600 border-0">
                <span class="absolute px-3 font-medium text-gray-500 dark:text-gray-400 -translate-x-1/2 bg-white dark:bg-slate-800 left-1/2">or</span>
            </div>

            <form class="space-y-4" action="{{ route('register') }}" method="POST">
                @csrf

                {{-- Input Name --}}
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-600 dark:text-gray-300">Your Name</label>
                    <input type="text" name="name" id="name" class="block w-full p-2.5 text-sm rounded-lg bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 placeholder-gray-400 text-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500" placeholder="John Doe" autocomplete="off" required value="{{ old('name') }}">
                </div>

                {{-- Input Email --}}
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-600 dark:text-gray-300">Your email</label>
                    <input type="email" name="email" id="email" class="block w-full p-2.5 text-sm rounded-lg bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 placeholder-gray-400 text-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500" placeholder="name@company.com" autocomplete="off" required value="{{ old('email') }}">
                    @error('email') <div class="text-red-600 pt-2 text-sm">{{$message}}</div> @enderror
                </div>

                {{-- Input Password --}}
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-600 dark:text-gray-300">Password</label>
                    <input type="password" name="password" id="password" placeholder="••••••••" class="block w-full p-2.5 text-sm rounded-lg bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 placeholder-gray-400 text-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500" autocomplete="off" required>
                    @error('password') <div class="text-red-600 pt-2 text-sm">{{$message}}</div> @enderror
                </div>

                {{-- Input Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-600 dark:text-gray-300">Confirm password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="••••••••" class="block w-full p-2.5 text-sm rounded-lg bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 placeholder-gray-400 text-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500" autocomplete="off" required>
                    @error('password_confirmation') <div class="text-red-600 pt-2 text-sm">{{$message}}</div> @enderror
                </div>

                {{-- Tombol Submit --}}
                <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3 text-center dark:focus:ring-blue-800">
                    Create an account
                </button>

                {{-- Link ke Login --}}
                <p class="text-sm font-light text-center text-gray-500 dark:text-gray-400">
                    Already have an account? <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Sign in here</a>
                </p>
            </form>
        </div>
        
    </div>
</section>
@endsection