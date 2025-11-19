<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanKeuanganController extends Controller
{
    public function index()
    {
        return view('finance.laporan.index');
    }

    public function bulanan()
    {
        return view('finance.laporan.bulanan');
    }

    public function tahunan()
    {
        return view('finance.laporan.tahunan');
    }

    public function export()
    {
        // TODO: Logic export
        return back();
    }
}