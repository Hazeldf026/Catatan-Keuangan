@php
    $mode = $mode ?? 'create'; 
    $rencana = $rencana ?? new \App\Models\GrupRencana();
@endphp

<div class="space-y-4" 
        x-data="{
            enableDate: {{ old('target_tanggal', $rencana->target_tanggal ? 'true' : 'false') }},
            day: '{{ old('day', $rencana->target_tanggal ? \Carbon\Carbon::parse($rencana->target_tanggal)->format('d') : '') }}',
            month: '{{ old('month', $rencana->target_tanggal ? \Carbon\Carbon::parse($rencana->target_tanggal)->format('m') : '') }}',
            year: '{{ old('year', $rencana->target_tanggal ? \Carbon\Carbon::parse($rencana->target_tanggal)->format('Y') : '') }}',

            fillForm(data) {
                if (!data) return;

                // 1. Isi input standar (Nama & Deskripsi)
                this.$el.querySelector('input[name=\'nama\']').value = data.nama || '';
                this.$el.querySelector('textarea[name=\'deskripsi\']').value = data.deskripsi || '';

                // 2. Isi input Rupiah (Display & Hidden)
                // PERUBAHAN: Gunakan querySelector dengan ID unik
                const targetJumlahHidden = this.$el.querySelector('#target_jumlah_{{ $mode }}');
                const targetJumlahDisplay = this.$el.querySelector('#target_jumlah_display_{{ $mode }}');
                
                if (targetJumlahHidden && targetJumlahDisplay) {
                    const rawValue = data.target_jumlah || '';
                    targetJumlahHidden.value = rawValue;
                    targetJumlahDisplay.value = new Intl.NumberFormat('id-ID').format(rawValue || 0);
                }

                // 3. Isi state tanggal (INI SEKARANG AKAN BERFUNGSI)
                if (data.target_tanggal) {
                    const date = new Date(data.target_tanggal + 'T00:00:00');
                    this.day = String(date.getDate()).padStart(2, '0');
                    this.month = String(date.getMonth() + 1).padStart(2, '0');
                    this.year = String(date.getFullYear());
                    this.enableDate = true;
                } else {
                    this.day = '';
                    this.month = '';
                    this.year = '';
                    this.enableDate = false;
                }
            },

            resetForm() {
                this.$el.closest('form').reset();
                const targetJumlahHidden = this.$el.querySelector('#target_jumlah_{{ $mode }}');
                const targetJumlahDisplay = this.$el.querySelector('#target_jumlah_display_{{ $mode }}');
                if (targetJumlahHidden && targetJumlahDisplay) {
                    targetJumlahHidden.value = '';
                    targetJumlahDisplay.value = '';
                }
                this.enableDate = false;
                this.day = '';
                this.month = '';
                this.year = '';
            }
        }"

        x-on:fill-form.document="if ('{{ $mode }}' === 'edit') { fillForm($event.detail.data) }"
        x-on:reset-form.document="if ('{{ $mode }}' === 'create') { resetForm() }"
    >

        {{-- Nama Rencana --}}
        <div>
            {{-- PERUBAHAN: for dan id dibuat unik --}}
            <label for="nama_{{ $mode }}" class="block mb-2 text-sm font-medium text-gray-900">Nama Rencana</label>
            <input type="text" name="nama" id="nama_{{ $mode }}"
                value="{{ old('nama', $rencana->nama) }}"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                placeholder="Contoh: Dana Darurat" required>
            @error('nama') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        {{-- Target Jumlah (Rp) --}}
        <div>
            {{-- PERUBAHAN: for dan id dibuat unik --}}
            <label for="target_jumlah_display_{{ $mode }}" class="block mb-2 text-sm font-medium text-gray-900">Target Jumlah (Rp)</label>
            <input type="text" id="target_jumlah_display_{{ $mode }}"
                value="{{ old('target_jumlah', isset($rencana->target_jumlah) ? number_format($rencana->target_jumlah, 0, ',', '.') : '') }}" 
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                placeholder="Contoh: 5.000.000" required>
            <input type="hidden" name="target_jumlah" id="target_jumlah_{{ $mode }}" 
                value="{{ old('target_jumlah', $rencana->target_jumlah ?? '') }}">
            @error('target_jumlah') <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p> @enderror
        </div>
            
        {{-- Target Tanggal --}}
        <div class="p-4 border rounded-lg bg-gray-50 border-gray-300">
            <div class="flex items-center justify-between mb-4 select-none">
                {{-- PERUBAHAN: for dan id dibuat unik --}}
                <label for="toggleDate_{{ $mode }}" class="flex-grow block text-sm font-medium text-gray-900 cursor-pointer">
                    Aktifkan Target Tanggal (Opsional)
                </label>
                <label for="toggleDate_{{ $mode }}" class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="toggleDate_{{ $mode }}" x-model="enableDate" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>

            <div>
                <div class="flex items-center space-x-2 transition" :class="{ 'opacity-50 cursor-not-allowed': !enableDate }">
                    <select x-model="day" name="day" :disabled="!enableDate" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">Hari</option>
                        @for ($i = 1; $i <= 31; $i++)
                            <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</option>
                        @endfor
                    </select>
                    <select x-model="month" name="month" :disabled="!enableDate" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">Bulan</option>
                        @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $num => $m)
                            <option value="{{ sprintf('%02d', $num + 1) }}">{{ $m }}</option>
                        @endforeach
                    </select>
                    <select x-model="year" name="year" :disabled="!enableDate" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">Tahun</option>
                        @for ($i = date('Y'); $i <= date('Y') + 10; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                    <button type="button" @click="$refs.dateInput.showPicker()" :disabled="!enableDate" class="p-2.5 border rounded-lg bg-gray-50 border-gray-300 hover:bg-gray-100">
                        <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                <input type="date" x-ref="dateInput" class="sr-only" @change="
                    if ($event.target.value) {
                        let date = new Date($event.target.value + 'T00:00:00');
                        day = String(date.getDate()).padStart(2, '0');
                        month = String(date.getMonth() + 1).padStart(2, '0');
                        year = date.getFullYear();
                    }">
                <input type="hidden" name="target_tanggal" :value="enableDate && day && month && year ? `${year}-${month}-${day}` : ''">
                @error('target_tanggal') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Deskripsi --}}
        <div>
            {{-- PERUBAHAN: for dan id dibuat unik --}}
            <label for="deskripsi_{{ $mode }}" class="block mb-2 text-sm font-medium text-gray-900">Deskripsi (Opsional)</label>
            <textarea name="deskripsi" id="deskripsi_{{ $mode }}" rows="4" 
                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" 
                    placeholder="Contoh: Dana ini akan digunakan untuk keperluan tak terduga"
                    >{{ old('deskripsi', $rencana->deskripsi) }}</textarea>
        </div>
</div>

<script>
    function initializeRupiahScript_{{ $mode }}() {
        const jumlahDisplay = document.getElementById('target_jumlah_display_{{ $mode }}');
        const jumlahHidden = document.getElementById('target_jumlah_{{ $mode }}');

        // Hentikan jika elemen tidak ditemukan (mencegah error)
        if (!jumlahDisplay || !jumlahHidden) return;

        function formatNumber(number) {
            let cleanNumber = number.toString().replace(/[^0-9]/g, '');
            return new Intl.NumberFormat('id-ID').format(cleanNumber || 0);
        }

        function deformatNumber(formattedNumber) {
            return formattedNumber.toString().replace(/[^0-9]/g, '');
        }

        jumlahDisplay.addEventListener('input', function(e) {
            let rawValue = e.target.value;
            let cleanValue = deformatNumber(rawValue);
            
            jumlahHidden.value = cleanValue;
            
            let formattedValue = formatNumber(cleanValue);
            
            let start = e.target.selectionStart;
            let end = e.target.selectionEnd;
            let diff = formattedValue.length - rawValue.length;

            e.target.value = formattedValue;

            e.target.setSelectionRange(start + diff, end + diff);
        });

        // Hapus 'if (jumlahDisplay.value)' agar tidak menimpa nilai dari fillForm
    }
    
    // Jalankan fungsi unik ini saat DOM siap
    document.addEventListener('DOMContentLoaded', initializeRupiahScript_{{ $mode }});
</script>