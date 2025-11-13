<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    /**
     * Display the workspace index page for karyawan
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('karyawan.workspace.index');
    }
}