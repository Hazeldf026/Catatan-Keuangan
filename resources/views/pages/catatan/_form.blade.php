<form action="{{ $action }}" method="POST" x-data="{
    tipe: '{{ old('tipe', $catatan->category->tipe ?? '') }}',
    categoryId: '{{ old('category_id', $catatan->category_id ?? '') }}',
    alokasi: '{{ old('alokasi', $catatan->alokasi ?? '') }}',
    isLainnyaSelected() {
        if (!this.categoryId) return false;
        const selectedOption = document.querySelector(`#category_id option[value='${this.categoryId}']`);
        return selectedOption && selectedOption.text === 'Lainnya...';
    }
}">
    @csrf
    @if ($method === 'PUT')
        @method('PUT')
    @endif

    {{-- Jenis Transaksi --}}
    <div class="mb-5">
        <label for="tipe" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis Transaksi</label>
        <select id="tipe" name="tipe" x-model="tipe" @change="categoryId = ''; alokasi = ''" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
            <option value="" disabled selected hidden>Pilih Jenis</option>
            <option value="pemasukan">Pemasukan</option>
            <option value="pengeluaran">Pengeluaran</option>
        </select>
        @error('tipe')
            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- Kategori --}}
    <div class="mb-5" x-show="tipe" x-cloak>
        <label for="category_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori</label>
        <select id="category_id" name="category_id" x-model="categoryId" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
            <option value="" disabled selected hidden>Pilih Kategori</option>
            @foreach ($categories as $category)
                <template x-if="tipe === '{{ $category->tipe }}'">
                    <option value="{{ $category->id }}">{{ $category->nama }}</option>
                </template>
            @endforeach
        </select>
        @error('category_id')
            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- Lainnya... --}}
    <div class="mb-5" x-show="isLainnyaSelected()" x-cloak>
        <label for="custom_category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori Baru</label>
        <input type="text" id="custom_category" name="custom_category" 
        :required="isLainnyaSelected()"
        value="{{ old('custom_category', $catatan->custom_category ?? '') }}"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
        placeholder="Contoh: Kategori Hobi">
        @error('custom_category')
            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- Input Alokasi (Hanya muncul untuk Pemasukan) --}}
    <div class="mb-5" x-show="tipe === 'pemasukan'" x-cloak>
        <label for="alokasi" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alokasikan Ke</label>
        <select id="alokasi" name="alokasi" x-model="alokasi" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
            <option value="">Pilih Alokasi</option>
            <option value="media">Media Penyimpanan</option>
            <option value="rencana">Rencana Tabungan</option>
        </select>
    </div>

    {{-- Input Select Media (Muncul saat Pengeluaran, atau saat Pemasukan dialokasikan ke Media) --}}
    <div class="mb-5" x-show="tipe === 'pengeluaran' || (tipe === 'pemasukan' && alokasi === 'media')" x-cloak>
        <label for="media" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Media Penyimpanan</label>
        <select name="media" id="media" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
            <option value="">Pilih Media</option>
            <option value="wallet" @selected(old('media', $catatan->media) == 'wallet')>Wallet</option>
            <option value="bank" @selected(old('media', $catatan->media) == 'bank')>Bank</option>
            <option value="e-wallet" @selected(old('media', $catatan->media) == 'e-wallet')>E-Wallet</option>
            <option value="tabungan" @selected(old('media', $catatan->media) == 'tabungan')>Tabungan</option>
        </select>
        @error('media') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
    </div>

    {{-- Pilihan Rencana (Hanya Muncul Jika Pemasukan dialokasikan ke Rencana) --}}
    <div class="mb-5" x-show="tipe === 'pemasukan' && alokasi === 'rencana'" x-cloak>
        <label for="rencana_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Rencana</label>
        <select name="rencana_id" id="rencana_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
            <option value="">Pilih salah satu rencanamu</option>
            @foreach ($rencanas as $rencana)
                <option value="{{ $rencana->id }}" @selected(old('rencana_id', $catatan->rencana_id) == $rencana->id)>
                    {{ $rencana->nama }}
                </option>
            @endforeach
        </select>
        @error('rencana_id') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
    </div>
    
    {{-- Jumlah --}}
    <div class="mb-5">
        <label for="jumlah_display" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jumlah (Rp)</label>
        <input type="text" id="jumlah_display" value="{{ old('jumlah', isset($catatan) ? number_format($catatan->jumlah, 0, ',', '.') : '') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="15.000" required>
        <input type="hidden" name="jumlah" id="jumlah" value="{{ old('jumlah', $catatan->jumlah ?? '') }}">
        @error('jumlah')
            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- Deskripsi --}}
    <div class="mb-5">
        <label for="deskripsi" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi</label>
        <input type="text" id="deskripsi" name="deskripsi" value="{{ old('deskripsi', $catatan->deskripsi ?? '') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Contoh: Beli Kopi" required>
        @error('deskripsi')
            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- Submit dan Batal --}}
    <div class="mt-6 flex justify-end space-x-3"> {{-- Tambahkan margin atas dan spacing --}}
        
        {{-- Tombol Batal --}}
        @if($method === 'PUT')
            {{-- Saat Edit, kembali ke halaman index --}}
            <a href="{{ route('catatan.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:bg-gray-600">
                Batal
            </a>
        @else
            {{-- Saat Create, kembali ke halaman index --}}
            <a href="{{ route('catatan.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:bg-gray-600">
                Batal
            </a>
        @endif

        {{-- Tombol Simpan/Update --}}
        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            {{ $method === 'PUT' ? 'Update Catatan' : 'Simpan Catatan' }} {{-- Ubah teks tombol --}}
        </button>
    </div>
</form>

{{-- SCRIPT UNTUK FORMAT ANGKA (TETAP SAMA) --}}
<script>
    const jumlahDisplay = document.getElementById('jumlah_display');
    const jumlahHidden = document.getElementById('jumlah');

    function formatNumber(number) {
        let cleanNumber = number.toString().replace(/[^0-9]/g, '');
        return new Intl.NumberFormat('id-ID').format(cleanNumber || 0);
    }

    jumlahDisplay.addEventListener('input', function(e) {
        let rawValue = e.target.value;
        let cleanValue = rawValue.replace(/[^0-9]/g, '');
        jumlahHidden.value = cleanValue;
        
        let cursorPosition = e.target.selectionStart;
        e.target.value = formatNumber(rawValue);
        let newLength = e.target.value.length;
        let oldLength = rawValue.length;

        if (newLength > oldLength) {
            e.target.setSelectionRange(cursorPosition + (newLength - oldLength), cursorPosition + (newLength - oldLength));
        } else {
            e.target.setSelectionRange(cursorPosition, cursorPosition);
        }
    });

    if (jumlahDisplay.value) {
        jumlahDisplay.value = formatNumber(jumlahDisplay.value);
    }
</script>