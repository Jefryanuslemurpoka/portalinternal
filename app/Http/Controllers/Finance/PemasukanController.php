<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PemasukanController extends Controller
{
    public function index()
    {
        return view('finance.pemasukan.index');
    }

    public function create()
    {
        return view('finance.pemasukan.create');
    }

    public function store(Request $request)
    {
        // TODO: Logic store
        return redirect()->route('finance.pemasukan.index');
    }

    public function edit($id)
    {
        return view('finance.pemasukan.edit');
    }

    public function update(Request $request, $id)
    {
        // TODO: Logic update
        return redirect()->route('finance.pemasukan.index');
    }

    public function destroy($id)
    {
        // TODO: Logic delete
        return redirect()->route('finance.pengeluaran.index');
    }
}