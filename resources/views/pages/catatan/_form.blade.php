

@php
    use App\Models\Category;

    $categories = Category::all();
    $tipe = old('tipe', $catatan->category->tipe ?? null);
    $oldCategoryId = old('category_id', $catatan->category_id ?? null);
@endphp

<form action="{{ $action }}" method="POST">
    @csrf
    @if ($method === 'PUT')
        @method('PUT')
    @endif

    {{-- Jenis Transaksi --}}
    <div class="mb-5">
        <label for="tipe" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis Transaksi</label>
        <select id="tipe" name="tipe" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
            <option value="" disabled selected hidden>Pilih Jenis</option>
            <option value="pemasukan" @selected($tipe == 'pemasukan')>Pemasukan</option>
            <option value="pengeluaran" @selected($tipe == 'pengeluaran')>Pengeluaran</option>
        </select>
        @error('tipe')
            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- Kategori --}}
    <div class="mb-5" id="category_container" style="display: none;">
        <label for="category_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori</label>
        <select id="category_id" name="category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
            <option value="" disabled selected hidden>Pilih Kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" data-tipe="{{ $category->tipe }}" @selected($oldCategoryId == $category->id)>{{ $category->nama }}</option>
            @endforeach
        </select>
        @error('category_id')
            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- Lainnya... --}}
    <div class="mb-5" id="custom_category_container" style="display: none;">
        <label for="custom_category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori Baru</label>
        <input type="text" id="custom_category" name="custom_category" 
        value="{{ old('custom_category', $catatan->custom_category ?? '') }}"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
        placeholder="Contoh: Kategori Hobi">
        @error('custom_category')
            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- Jumlah --}}
    <div class="mb-5">
    <label for="jumlah_display" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jumlah (Rp)</label>
    
    {{-- Input yang Dilihat Pengguna (untuk display) --}}
    <input type="text" id="jumlah_display" 
            value="{{ old('jumlah', isset($catatan) ? number_format($catatan->jumlah, 0, ',', '.') : '') }}"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
            placeholder="15.000" required>
    
    {{-- Input Tersembunyi (untuk dikirim ke server) --}}
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

    {{-- Submit --}}
    <div class="flex items-center justify-end">
        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Simpan
        </button>
    </div>
</form>

<script>
    // Ambil elemen input
    const jumlahDisplay = document.getElementById('jumlah_display');
    const jumlahHidden = document.getElementById('jumlah');

    // Fungsi untuk memformat angka
    function formatNumber(number) {
        // Hapus semua karakter selain digit
        let cleanNumber = number.toString().replace(/[^0-9]/g, '');
        // Format dengan pemisah ribuan (titik)
        return new Intl.NumberFormat('id-ID').format(cleanNumber || 0);
    }

    // Event listener saat pengguna mengetik
    jumlahDisplay.addEventListener('input', function(e) {
        // Ambil nilai mentah dari input display
        let rawValue = e.target.value;
        // Hapus semua pemisah untuk disimpan di input hidden
        let cleanValue = rawValue.replace(/[^0-9]/g, '');
        
        // Update nilai input hidden
        jumlahHidden.value = cleanValue;
        
        // Update nilai input display dengan format baru
        // Simpan posisi kursor sebelum memformat
        let cursorPosition = e.target.selectionStart;
        e.target.value = formatNumber(rawValue);
        
        // Kembalikan posisi kursor agar tidak loncat ke akhir
        // Ini adalah trik agar user experience tetap nyaman
        let newLength = e.target.value.length;
        let oldLength = rawValue.length;
        if (newLength > oldLength) {
            e.target.setSelectionRange(cursorPosition + (newLength - oldLength), cursorPosition + (newLength - oldLength));
        } else {
            e.target.setSelectionRange(cursorPosition, cursorPosition);
        }
    });

    // Inisialisasi format saat halaman dimuat (untuk mode edit)
    if (jumlahDisplay.value) {
        jumlahDisplay.value = formatNumber(jumlahDisplay.value);
    }
</script>

<script>
    const tipeSelect = document.getElementById('tipe');
    const categorySelect = document.getElementById('category_id');
    const categoryContainer = document.getElementById('category_container');
    const CustomCategoryContainer = document.getElementById('custom_category_container');
    const CustomCategoryInput = document.getElementById('custom_category');
    const allCategories = Array.from(categorySelect.options).filter(opt => opt.value !== "");

    function populateCategories() {
        const selectedTipe = tipeSelect.value;
        categorySelect.innerHTML = '<option value="" disabled selected hidden >Pilih Kategori</option>';

        allCategories.forEach(option => {
            if (option.dataset.tipe === selectedTipe) {
                categorySelect.appendChild(option.cloneNode(true));
            }
        });

        categoryContainer.style.display = selectedTipe ? 'block' : 'none';
        toogleCustomInput();
    }

    function toogleCustomInput() {
        const selectedId = categorySelect.value;
        const isLainnya = categorySelect.options[categorySelect.selectedIndex]?.text === 'Lainnya...';
        if (isLainnya) {
            CustomCategoryContainer.style.display = 'block';
            CustomCategoryInput.required = true;
        } else {
            CustomCategoryContainer.style.display = 'none';
            CustomCategoryInput.required = false;
        }
    }

    tipeSelect.addEventListener('change', populateCategories);
    categorySelect.addEventListener('change', toogleCustomInput);

    // Init for edit mode
    populateCategories();
</script>