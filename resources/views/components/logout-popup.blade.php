<div x-show="logoutModalOpen" 
    x-cloak 
    {{-- 1. Tambahkan x-data & x-init untuk mengelola hitung mundur --}}
    x-data="{ countdown: 5, isCountingDown: true, timer: null,
        startCountdown() {
            this.isCountingDown = true;
            this.countdown = 5;
            this.timer = setInterval(() => {
                this.countdown--;
                if (this.countdown <= 0) {
                    clearInterval(this.timer);
                    this.isCountingDown = false;
                }
            }, 1000);
        },
        stopCountdown() {
            clearInterval(this.timer);
            this.isCountingDown = true;
            this.countdown = 5;
        }
    }"
    x-init="$watch('logoutModalOpen', value => {
        if (value) {
            startCountdown();
        } else {
            stopCountdown();
        }
    })"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    aria-labelledby="logout-modal-title" role="dialog" aria-modal="true">
    
    {{-- Latar belakang (overlay) --}}
    <div x-show="logoutModalOpen" 
        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-gray-900/60" @click="logoutModalOpen = false">
    </div>

    {{-- Panel Modal --}}
    <div x-show="logoutModalOpen" 
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative w-full max-w-md p-6 overflow-hidden text-left align-middle bg-white rounded-lg shadow-xl dark:bg-slate-800">
        
        <h3 class="text-lg font-bold leading-6 text-gray-900 dark:text-white" id="logout-modal-title">
            Konfirmasi Keluar
        </h3>
        <div class="mt-2">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Apakah Anda yakin ingin keluar dari sesi Anda?
            </p>
        </div>

        {{-- Tombol Aksi --}}
        <div class="mt-6 flex justify-end space-x-3">
            <button type="button" @click="logoutModalOpen = false"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-slate-700 dark:text-gray-300 dark:border-slate-500 dark:hover:bg-slate-600">
                Batal
            </button>
            
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                {{-- 2. Modifikasi Tombol Logout --}}
                <button type="submit"
                        :disabled="isCountingDown"
                        :class="{
                            'bg-red-400 dark:bg-red-800 text-white/80 cursor-not-allowed': isCountingDown,
                            'bg-red-600 hover:bg-red-700': !isCountingDown
                        }"
                        class="px-4 py-2 text-sm font-medium text-white border border-transparent rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-300">
                    
                    Ya, Keluar
                    <span x-show="isCountingDown" x-text="'(' + countdown + ')'"></span>
                </button>
            </form>
        </div>
    </div>
</div>
