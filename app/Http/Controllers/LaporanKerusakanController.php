<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanKerusakan;
use App\Models\Kendaraan;

class LaporanKerusakanController extends Controller
{
    public function index()
    {
        return view('laporan-kerusakan.index');
    }

    public function show($id)
    {
        return view('laporan-kerusakan.show', compact('id'));
    }
}
