<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    /**
     * Display the workspace index page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('superadmin.workspace.index');
    }
}