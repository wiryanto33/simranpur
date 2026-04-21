<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SukuCadangController extends Controller
{
    public function index()
    {
        return view('suku-cadang.index');
    }
}
