{{-- File: resources/views/group/pages/rencana/_form.blade.php --}}
@php
    $rencana = $rencana ?? new \App\Models\GrupRencana();
@endphp

<div class="space-y-4">
    <div>
        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Rencana</label>
        <input type="text" name="nama" id="nama" required maxlength="255"
               value="{{ old('nama', $rencana->nama) }}"
               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
               placeholder="Misal: Liburan Akhir Tahun">
        @error('nama') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="target_jumlah" class="block text-sm font-medium text-gray-700 mb-1">Target Jumlah (Rp)</label>
        <input type="number" name="target_jumlah" id="target_jumlah" required min="1" step="any"
               value="{{ old('target_jumlah', $rencana->target_jumlah) }}"
               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
               placeholder="Masukkan target dana">
        @error('target_jumlah') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- [BARU] Input Target Tanggal --}}
    <div>
        <label for="target_tanggal" class="block text-sm font-medium text-gray-700 mb-1">Target Tanggal (Opsional)</label>
        <input type="date" name="target_tanggal" id="target_tanggal"
               {{-- Format tanggal YYYY-MM-DD untuk value --}}
               value="{{ old('target_tanggal', $rencana->target_tanggal ? $rencana->target_tanggal->format('Y-m-d') : '') }}"
               min="{{ now()->format('Y-m-d') }}" {{-- Minimal tanggal hari ini --}}
               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
        @error('target_tanggal') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
    {{-- [AKHIR BARU] --}}

    <div>
        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (Opsional)</label>
        <textarea name="deskripsi" id="deskripsi" rows="3"
                  class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                  placeholder="Jelaskan tujuan rencana ini...">{{ old('deskripsi', $rencana->deskripsi) }}</textarea>
        @error('deskripsi') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
</div>