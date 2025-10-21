@php
    $categories = $categories ?? \App\Models\Category::orderBy('nama')->get();
    $catatan = $catatan ?? new \App\Models\GrupCatatan();
    $currentTipe = old('tipe', $catatan->tipe ?? 'pengeluaran');
    $currentCategoryId = old('category_id', $catatan->category_id ?? '');
    $lainnyaCategory = \App\Models\Category::where('nama', 'Lainnya...')->where('tipe', $currentTipe)->first();
    $isCurrentlyLainnya = $lainnyaCategory && $currentCategoryId == $lainnyaCategory->id;
@endphp

<div x-data="{
        tipe: '{{ $currentTipe }}',
        selectedCategory: '{{ $currentCategoryId }}',
        isLainnya: {{ $isCurrentlyLainnya ? 'true' : 'false' }},
     }"
     x-init="$watch('tipe', value => { isLainnya = false; selectedCategory = ''; })"
     class="space-y-4">

    <div>
        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
        <input type="text" name="deskripsi" id="deskripsi" required maxlength="255"
               value="{{ old('deskripsi', $catatan->deskripsi) }}"
               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
               placeholder="Masukkan deskripsi catatan">
        @error('deskripsi') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-1">Jumlah (Rp)</label>
        <input type="number" name="jumlah" id="jumlah" required min="0" step="any"
               value="{{ old('jumlah', $catatan->jumlah) }}"
               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
               placeholder="Masukkan jumlah transaksi">
        @error('jumlah') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
        <div class="flex items-center space-x-4">
            <label class="inline-flex items-center">
                <input type="radio" name="tipe" value="pengeluaran" x-model="tipe" class="form-radio text-blue-600">
                <span class="ml-2 text-sm text-gray-700">Pengeluaran</span>
            </label>
            <label class="inline-flex items-center">
                <input type="radio" name="tipe" value="pemasukan" x-model="tipe" class="form-radio text-green-600">
                <span class="ml-2 text-sm text-gray-700">Pemasukan</span>
            </label>
        </div>
        @error('tipe') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
        <select name="category_id" id="category_id" x-model="selectedCategory"
                @change="isLainnya = ($event.target.options[$event.target.selectedIndex].dataset.nama === 'Lainnya...');"
                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            <option value="">-- Pilih Kategori --</option>
            @foreach ($categories as $category)
                <template x-if="tipe === '{{ $category->tipe }}'">
                    <option value="{{ $category->id }}"
                            data-nama="{{ $category->nama }}"
                            :selected="'{{ $category->id }}' === selectedCategory">
                        {{ $category->nama }}
                    </option>
                </template>
            @endforeach
        </select>
        @error('category_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div x-show="isLainnya" x-transition>
        <label for="custom_category" class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori Lainnya</label>
        <input type="text" name="custom_category" id="custom_category" maxlength="255"
               value="{{ old('custom_category', $catatan->custom_category) }}"
               :required="isLainnya"
               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
               placeholder="Masukkan nama kategori baru">
        @error('custom_category') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div x-show="tipe === 'pengeluaran'" x-transition>
         <label for="media" class="block text-sm font-medium text-gray-700 mb-1">Media Pembayaran</label>
        <select name="media" id="media"
                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            <option value="">-- Pilih Media --</option>
            <option value="wallet" {{ old('media', $catatan->media) == 'wallet' ? 'selected' : '' }}>Wallet</option>
            <option value="bank" {{ old('media', $catatan->media) == 'bank' ? 'selected' : '' }}>Bank</option>
            <option value="e-wallet" {{ old('media', $catatan->media) == 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
            <option value="tabungan" {{ old('media', $catatan->media) == 'tabungan' ? 'selected' : '' }}>Tabungan</option>
        </select>
         @error('media') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

</div>