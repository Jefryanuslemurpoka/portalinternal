<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnggaranController extends Controller
{
    public function index()
    {
        return view('finance.anggaran.index');
    }

    public function create()
    {
        return view('finance.anggaran.create');
    }

    public function store(Request $request)
    {
        // TODO: Logic store
        return redirect()->route('finance.anggaran.index');
    }

    public function edit($id)
    {
        return view('finance.anggaran.edit');
    }

    public function update(Request $request, $id)
    {
        // TODO: Logic update
        return redirect()->route('finance.anggaran.index');
    }

    public function destroy($id)
    {
        // TODO: Logic delete
        return redirect()->route('finance.anggaran.index');
    }
}