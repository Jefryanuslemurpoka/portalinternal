<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        return view('finance.invoice.index');
    }

    public function create()
    {
        return view('finance.invoice.create');
    }

    public function store(Request $request)
    {
        // TODO: Logic store
        return redirect()->route('finance.invoice.index');
    }

    public function show($id)
    {
        return view('finance.invoice.show');
    }

    public function edit($id)
    {
        return view('finance.invoice.edit');
    }

    public function update(Request $request, $id)
    {
        // TODO: Logic update
        return redirect()->route('finance.invoice.index');
    }

    public function destroy($id)
    {
        // TODO: Logic delete
        return redirect()->route('finance.invoice.index');
    }

    public function download($id)
    {
        // TODO: Logic download PDF
        return back();
    }
}