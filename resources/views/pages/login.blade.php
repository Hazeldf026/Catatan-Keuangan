@extends('app')

@section('start')
    <section class="min-h-screen w-full bg-gray-50 dark:bg-gray-900 flex items-center justify-center p-4">
        <div class="container mx-auto flex flex-col lg:flex-row items-center justify-center gap-12 lg:gap-40">

            <div class="w-full max-w-lg bg-white dark:bg-slate-800 rounded-xl shadow-lg p-8">
                
                <div class="flex items-center gap-2 mb-3">
                    <img src="/images/Logo.png" alt="Logo" class="w-10 h-10">
                    <span class="text-xl font-bold text-gray-800 dark:text-white">Credix</span>
                </div>

                <div class="text-start mb-3">
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                        Sign in with email
                    </h1>
                    <p class="mt-2 text-gray-500 dark:text-gray-400 text-sm">
                        Satu tempat untuk semua catatan keuangan, membawa Anda lebih dekat ke tujuan finansial.
                    </p>
                </div>

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="pb-1 pt-3 text-left">
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-600 dark:text-gray-300">Email</label>
                            <input type="email" id="email" name="email" placeholder="name@company.com" class="block w-full p-2.5 text-sm rounded-lg bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 placeholder-gray-400 text-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500" autocomplete="off" required>
                            @error('email')
                                <div class="text-red-600 pt-2">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="pb-1 pt-3 text-left">
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-600 dark:text-gray-300">Password</label>
                            {{-- 1. Bungkus input dengan div yang memiliki x-data --}}
                            <div x-data="{ showPassword: false }" class="relative">
                                {{-- 2. Bind atribut :type ke state showPassword --}}
                                <input :type="showPassword ? 'text' : 'password'" id="password" name="password" placeholder="••••••••" class="block w-full p-2.5 pr-10 text-sm rounded-lg bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 placeholder-gray-400 text-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500" autocomplete="off" required>
                                {{-- 3. Tambahkan tombol ikon di dalam div --}}
                                <div @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                                    {{-- Ikon mata terbuka (saat password tersembunyi) --}}
                                    <svg x-show="!showPassword" class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    {{-- Ikon mata tercoret (saat password terlihat) --}}
                                    <svg x-show="showPassword" class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="display: none;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.774 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243l-4.243-4.243" />
                                    </svg>
                                </div>  
                            </div>
                            @error('password')
                                <div class="text-red-600 pt-2">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="relative inline-flex items-center justify-center w-full">
                        <hr class="w-full h-px my-8 bg-gray-300 dark:bg-gray-600 border-0">
                        <span class="absolute px-3 font-medium text-gray-500 dark:text-gray-400 -translate-x-1/2 bg-white dark:bg-slate-800 left-1/2">or</span>
                    </div>
                    <div class="grid grid-cols-1 gap-4">
                        <a href="#" class="w-full flex items-center justify-center text-gray-700 dark:text-white bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 font-medium rounded-lg text-sm px-5 py-3 text-center">
                            <svg class="w-5 h-5 mr-2" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 488 512"><path fill="currentColor" d="M488 261.8C488 403.3 391.1 504 248 504 110.8 504 0 393.2 0 256S110.8 8 248 8c66.8 0 126 23.4 172.9 61.9l-76.3 64.5C308.6 106.5 280.2 96 248 96c-88.8 0-160.1 71.9-160.1 160.1s71.3 160.1 160.1 160.1c98.2 0 135-70.4 140.8-106.9H248v-85.3h236.1c2.3 12.7 3.9 26.9 3.9 41.4z"></path></svg>
                            Sign in with Google
                        </a>
                    </div>
                    <div class="flex items-center justify-between my-3 mt-5">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="remember" type="checkbox" class="w-4 h-4 border rounded bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-3 focus:ring-blue-600">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="remember" class="text-gray-600 dark:text-gray-300">Remember me</label>
                            </div>
                        </div>
                        <a href="{{ route('password.request') }}" class="text-sm font-medium text-blue-600 dark:text-blue-500 hover:underline">Forgot password?</a>
                    </div>
                    <div class="px-4 pb-1 pt-3">
                        <button class="uppercase block w-full p-3 text-sm rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">Sign in to your account</button>
                    </div>
                    <div class="text-center mt-5">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="text-blue-600 dark:text-blue-500 font-semibold hover:underline">Sign up</a>
                    </p>
                </div>
                </form>
            </div>

            {{-- KANAN: ANIMASI LOTTIE --}}
            <div class="w-full max-w-md hidden lg:flex">
                <div id="lottie-container" style="width: 100%;"></div>
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
            path: '/animations/growth-analysis.json' 
        });
    </script>
</body>
@endpush