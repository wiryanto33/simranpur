<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PermintaanSukuCadangController extends Controller
{
    public function index()
    {
        return view('permintaan-suku-cadang.index');
    }
}
