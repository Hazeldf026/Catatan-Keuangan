<?php

namespace App\Http\Controllers\group;

use App\Http\Controllers\Controller;
use App\Models\Grup;
use Illuminate\Http\Request;

class GroupRoomController extends Controller
{
    public function catatan(Request $request, Grup $grup)
    {
        // Pastikan middleware sudah mengecek user ada di grup

        // Logika ambil data catatan grup...
        // $catatans = ... 

        // Panggil view grup dengan layout grup
        return view('group::catatan.index', [
            'grup' => $grup, // Kirim variabel $grup ke layout dan view
            // 'catatans' => $catatans, // Kirim data lain
        ]); 
    }

    
}
