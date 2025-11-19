<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function index()
    {
        return view('finance.pengeluaran.index');
    }

    public function create()
    {
        return view('finance.pengeluaran.create');
    }

    public function store(Request $request)
    {
        // TODO: Logic store
        return redirect()->route('finance.pengeluaran.index');
    }

    public function edit($id)
    {
        return view('finance.pengeluaran.edit');
    }

    public function update(Request $request, $id)
    {
        // TODO: Logic update
        return redirect()->route('finance.pengeluaran.index');
    }

    public function destroy($id)
    {
        // TODO: Logic delete
        return redirect()->route('finance.pengeluaran.index');
    }
}