@extends('app')

@section('start')
    <section class="bg-gray-50 dark:bg-gray-900">
        {{-- Wadah utama untuk menengahkan konten di halaman --}}
<div class="min-h-screen w-full bg-gray-50 dark:bg-gray-900 flex items-center justify-center p-4">
    <div class="w-full max-w-md">

        {{-- Pesan Status (misal: "Kami telah mengirimkan link reset password ke email Anda!") --}}
        @if (session('status'))
            <div class="mb-4 bg-green-100 dark:bg-green-900 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg relative" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif

        {{-- Kartu Form Utama --}}
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-8">
            <div class="text-left">
                {{-- Judul --}}
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white">
                    Forgot your password
                </h1>
                {{-- Teks Instruksi --}}
                <p class="mt-2 text-gray-500 dark:text-gray-400">
                    Please enter the email address you'd like your password reset information sent to
                </p>
            </div>
            
            <form class="mt-8 space-y-6" action="{{ route('password.email') }}" method="POST">
                @csrf
                
                {{-- Input Email --}}
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-600 dark:text-gray-300">Enter email address</label>
                    <input type="email" name="email" id="email" 
                            class="block w-full p-2.5 text-sm rounded-lg bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 placeholder-gray-400 text-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500" 
                            placeholder="name@company.com" autocomplete="off" required value="{{ old('email') }}">
                    
                    {{-- Pesan Error Validasi --}}
                    @error('email')
                        <div class="text-red-600 pt-2 text-sm">{{$message}}</div>
                    @enderror
                </div>
                
                {{-- Tombol Submit --}}
                <button type="submit" 
                        class="w-full text-white bg-slate-800 hover:bg-slate-900 focus:ring-4 focus:outline-none focus:ring-slate-300 font-medium rounded-lg text-sm px-5 py-3 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Request reset link
                </button>
            </form>
            
            {{-- Link Kembali ke Login --}}
            <div class="text-center mt-6">
                <a href="{{ route('login') }}" class="font-medium text-sm text-blue-600 dark:text-blue-500 hover:underline">
                    Back to Login
                </a>
            </div>
        </div>
    </div>
</div>
    </section>
@endsection