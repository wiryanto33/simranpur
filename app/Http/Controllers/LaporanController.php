<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Kendaraan;
use App\Models\JadwalPemeliharaan;
use App\Models\LaporanKerusakan;
use App\Models\LaporanPerbaikan;
use App\Models\SukuCadang;
use Spatie\Activitylog\Models\Activity;

class LaporanController extends Controller
{
    public function kesiapan()
    {
        return view('laporan.kesiapan');
    }

    public function pemeliharaan()
    {
        return view('laporan.pemeliharaan');
    }

    public function kerusakan()
    {
        return view('laporan.kerusakan');
    }

    public function sukuCadang()
    {
        return view('laporan.suku-cadang');
    }

    public function auditLog()
    {
        if (!auth()->user()->hasRole('Admin')) abort(403);
        return view('laporan.audit-log');
    }

    public function export($type, $format)
    {
        if ($type == 'kesiapan' && $format == 'pdf') {
            $query = Kendaraan::with('kompi');
            
            if (request('kompi_id')) {
                $query->where('kompi_id', request('kompi_id'));
            }
            if (request('jenis')) {
                $query->where('jenis', request('jenis'));
            }
            
            $data = $query->get();
            $pdf = Pdf::loadView('laporan.kesiapan-pdf', compact('data'))->setPaper('a4', 'landscape');
            return $pdf->stream('Laporan_Kesiapan_Ranpur_'.now()->format('Ymd').'.pdf');
        }

        return back()->with('error', 'Fitur export ' . $type . ' (' . $format . ') sedang dalam pengembangan.');
    }
}
