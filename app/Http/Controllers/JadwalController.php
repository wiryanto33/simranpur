<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\JadwalPemeliharaan;
use Barryvdh\DomPDF\Facade\Pdf;

class JadwalController extends Controller
{
    public function index()
    {
        return view('jadwal.index');
    }

    public function cetakPdf($id)
    {
        $jadwal = JadwalPemeliharaan::with(['kendaraan', 'mekanik', 'mekanik.detail'])->findOrFail($id);
        
        $pdf = Pdf::loadView('jadwal.pdf', compact('jadwal'));
        
        // Use A4 portrait
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->stream('Surat_Jadwal_Pemeliharaan_' . ($jadwal->kendaraan->nomor_ranpur ?? $jadwal->id) . '.pdf');
    }
}
