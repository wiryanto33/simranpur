<?php
namespace App\Http\Controllers;

use App\Models\Kendaraan;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    public function index()
    {
        return view('kendaraan.index');
    }

    public function show($id)
    {
        $kendaraan = Kendaraan::with(['kompi', 'jadwalPemeliharaan.mekanik', 'laporanKerusakan.pelapor'])
            ->findOrFail($id);
            
        return view('kendaraan.show', compact('kendaraan'));
    }
}
