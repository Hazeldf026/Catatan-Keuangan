@extends('app')

@section('start')
    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="min-h-screen w-full bg-gray-50 dark:bg-gray-900 flex items-center justify-center p-4">
            <div class="w-full max-w-md">

                {{-- Pesan Error Validasi dari Laravel --}}
                @error('otp')
                    <div class="mb-4 bg-red-100 dark:bg-red-900 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg relative" role="alert">
                        <span class="block sm:inline">{{ $message }}</span>
                    </div>
                @enderror

                {{-- Kartu Form Utama --}}
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-8">
                    <div class="text-center">
                        {{-- Ikon Email --}}
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900">
                            <svg class="h-8 w-8 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                        </div>

                        {{-- Judul --}}
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                            Verify Your Email
                        </h1>

                        {{-- Teks Instruksi --}}
                        <p class="mt-2 text-gray-500 dark:text-gray-400">
                            Please enter the verification code we sent to<br><strong>{{ session('email', $email ?? 'your email') }}</strong>
                        </p>
                    </div>
                    
                    <form x-ref="otpForm"  x-data="otpForm()" class="mt-8" action="{{ route('password.otp.verify') }}" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email ?? '' }}">

                        {{-- Komponen Input OTP dengan Alpine.js --}}
                        <div>
                            {{-- Input tersembunyi untuk menampung nilai OTP gabungan --}}
                            <input type="hidden" name="otp" :value="otp.join('')">

                            <div class="flex justify-center gap-2 lg:gap-3">
                                <template x-for="(digit, index) in otp" :key="index">
                                    <input
                                        type="text"
                                        maxlength="1"
                                        x-model="otp[index]"
                                        :id="'otp-input-' + index"
                                        @input.prevent="handleInput(index)"
                                        @keydown.backspace.prevent="handleBackspace(index)"
                                        @paste.prevent="handlePaste($event)"
                                        class="h-12 w-12 sm:h-14 sm:w-14 rounded-lg border text-center text-xl font-semibold text-gray-800 dark:text-white bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500"
                                        pattern="[0-9]*"
                                        inputmode="numeric"
                                    >
                                </template>
                            </div>
                        
                            <button type="submit" class="w-full mt-8 text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3 text-center dark:focus:ring-blue-800">
                                Confirm
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
        function otpForm() {
            return {
                otp: ['', '', '', '', '', ''],
                // Fungsi untuk menangani input dan fokus otomatis ke kotak selanjutnya
                handleInput(index) {
                    // Hanya izinkan angka
                    this.otp[index] = this.otp[index].replace(/[^0-9]/g, '');
                    
                    if (this.otp[index] !== '' && index < 5) {
                        document.getElementById(`otp-input-${index + 1}`).focus();
                    }

                    if (this.otp.join('').length === 6) {
                        this.$refs.otpForm.submit();
                    }
                },
                // Fungsi untuk menangani backspace
                handleBackspace(index) {
                    if (index > 0) {
                        document.getElementById(`otp-input-${index - 1}`).focus();
                    }
                    // Hapus juga nilai di kotak saat ini
                    if (this.otp[index] !== '') {
                        this.otp[index] = '';
                    } else if (index > 0) {
                        this.otp[index - 1] = '';
                    }
                },
                // Fungsi untuk menangani paste OTP
                handlePaste(event) {
                    const pastedData = event.clipboardData.getData('text').slice(0, 6);
                    pastedData.split('').forEach((char, index) => {
                        this.otp[index] = char;
                    });
                    const lastFilledIndex = pastedData.length - 1;
                    if (lastFilledIndex >= 0 && lastFilledIndex < 5) {
                        document.getElementById(`otp-input-${lastFilledIndex}`).focus();
                    }
                }
            }
        }
    </script>
@endpush