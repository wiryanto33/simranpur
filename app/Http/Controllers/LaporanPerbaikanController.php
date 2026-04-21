<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanPerbaikan;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPerbaikanController extends Controller
{
    public function index()
    {
        return view('laporan-perbaikan.index');
    }

    public function show($id)
    {
        return view('laporan-perbaikan.show', compact('id'));
    }

    public function cetakPdf($id)
    {
        $perbaikan = LaporanPerbaikan::with([
            'laporanKerusakan.kendaraan',
            'laporanKerusakan.pelapor',
            'mekanik.detail',
            'approvedBy.detail',
            'transaksiSukuCadang.sukuCadang',
        ])->findOrFail($id);

        $lk        = $perbaikan->laporanKerusakan;
        $kendaraan = $lk?->kendaraan;
        $transaksis = $perbaikan->transaksiSukuCadang;

        $pdf = Pdf::loadView('pdf.berita-acara-perbaikan', compact('perbaikan', 'lk', 'kendaraan', 'transaksis'))
                  ->setPaper('a4', 'portrait');

        return $pdf->stream('Berita-Acara-LP-' . str_pad($id, 4, '0', STR_PAD_LEFT) . '.pdf');
    }
}
