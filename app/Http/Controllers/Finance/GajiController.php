<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GajiController extends Controller
{
    public function index()
    {
        return view('finance.gaji.index');
    }

    public function create()
    {
        return view('finance.gaji.create');
    }

    public function store(Request $request)
    {
        // TODO: Logic store
        return redirect()->route('finance.gaji.index');
    }

    public function edit($id)
    {
        return view('finance.gaji.edit');
    }

    public function update(Request $request, $id)
    {
        // TODO: Logic update
        return redirect()->route('finance.gaji.index');
    }

    public function destroy($id)
    {
        // TODO: Logic delete
        return redirect()->route('finance.gaji.index');
    }
}