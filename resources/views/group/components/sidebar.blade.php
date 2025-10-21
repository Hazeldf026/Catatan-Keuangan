@php
    // Ambil parameter 'grup' dari route saat ini
    // Ini bisa berupa ID atau objek Grup jika Route Model Binding aktif di route
    $grupFromRoute = request()->route('grup'); 
    
    // Jika $grupFromRoute belum berupa objek Grup (misal hanya ID), ambil dari database
    // Pastikan model Grup di-import jika perlu (biasanya otomatis jika pakai App\Models\Grup)
    $grup = ($grupFromRoute instanceof \App\Models\Grup) ? $grupFromRoute : \App\Models\Grup::find($grupFromRoute);

    // Jika karena alasan aneh $grup tidak ditemukan, set default untuk menghindari error
    if (!$grup) {
        // Ini seharusnya tidak terjadi jika route & middleware benar
        // Tapi sebagai fallback
        $grup = new \App\Models\Grup(['nama' => 'Grup Tidak Ditemukan', 'grup_code' => 'ERROR']); 
    }
@endphp

{{-- Komponen Sidebar --}}
<aside class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
<div class="h-full px-3 py-4 overflow-y-auto bg-gray-800 flex flex-col">
    {{-- Bagian Atas: Profil Grup (Sekarang bisa langsung pakai $grup) --}}
    <div class="mb-6 flex flex-col items-center text-center">
        <img class="w-20 h-20 rounded-full mb-3 ring-2 ring-gray-500 p-1"
            src="https://ui-avatars.com/api/?name={{ urlencode($grup->nama) }}&background=random&color=fff"
            alt="Avatar Grup {{ $grup->nama }}">
        <h2 class="text-lg font-semibold text-white truncate w-full" title="{{ $grup->nama }}">
            {{ $grup->nama }}
        </h2>
        <span class="text-xs font-mono text-gray-400 mt-1 bg-gray-700 px-2 py-0.5 rounded">
            {{ $grup->grup_code }}
        </span>
    </div>

    {{-- Bagian Tengah: Navigasi Utama Grup --}}
    <ul class="space-y-2 font-medium flex-grow">
        {{-- Link Catatan/Dashboard --}}
        <li>
            {{-- Gunakan nama route 'group.catatan.index' --}}
            <a href="{{ route('group.catatan.index', ['grup' => $grup->id]) }}" 
            class="{{ request()->routeIs('group.catatan.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} flex items-center p-2 rounded-lg group transition-colors duration-150">
            <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors duration-150 {{ request()->routeIs('group.catatan.*') ? 'text-white' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18"><path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z"/></svg>
            <span class="ms-3">Dashboard</span>
            </a>
        </li>
        {{-- Link Analisis (Ganti # dengan route nanti) --}}
        <li>
            <a href="#" {{-- href="{{ route('group.analisis.index', ['grup' => $grup->id]) }}" --}}
            class="{{ request()->routeIs('group.analisis.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} flex items-center p-2 rounded-lg group transition-colors duration-150">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors duration-150 {{ request()->routeIs('group.analisis.*') ? 'text-white' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4H8M4 8H8M4 12H8M4 16H8m4-12h4m-4 4h4m-4 4h4m-4 4h4"/></svg>
            <span class="ms-3">Analisis</span>
            </a>
        </li>
        {{-- Link Rencana (Ganti # dengan route nanti) --}}
        <li>
            <a href="{{ route('group.rencana.index', ['grup' => $grup->id]) }}" {{-- href="{{ route('group.rencana.index', ['grup' => $grup->id]) }}" --}}
            class="{{ request()->routeIs('group.rencana.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} flex items-center p-2 rounded-lg group transition-colors duration-150">
            <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors duration-150 {{ request()->routeIs('group.rencana.*') ? 'text-white' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="ms-3">Rencana</span>
            </a>
        </li>
        {{-- Link Detail Grup (Ganti # dengan route nanti) --}}
        <li>
            <a href="#" {{-- href="{{ route('group.detail.index', ['grup' => $grup->id]) }}" --}}
            class="{{ request()->routeIs('group.detail.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} flex items-center p-2 rounded-lg group transition-colors duration-150">
            <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors duration-150 {{ request()->routeIs('group.detail.*') ? 'text-white' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 18"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5h5M5 8h2m-2 3h9m-9 3h9m2-9h1a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1h-1M2 9V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V9Z"/></svg>
            <span class="ms-3">Detail Grup</span>
            </a>
        </li>
    </ul>

    {{-- Bagian Bawah: Tombol Keluar --}}
    <div class="mt-auto pt-4 border-t border-gray-700">
        {{-- Arahkan ke halaman daftar grup di personal (route 'grup.index') --}}
        <a href="{{ route('grup.index') }}" 
            class="flex items-center p-2 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white group transition-colors duration-150">
            <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors duration-150" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"> <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H4m12 0-4 4m4-4-4-4m3-4h2a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3h-2"/> </svg>
            <span class="ms-3">Keluar Room</span>
        </a>
    </div>
</div>
</aside>