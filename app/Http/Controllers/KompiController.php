<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KompiController extends Controller
{
    public function index()
    {
        return view('kompi.index');
    }
}
