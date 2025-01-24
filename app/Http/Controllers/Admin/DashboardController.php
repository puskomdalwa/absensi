<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $user = \Auth::user();
        return view('admin/dashboard/index', compact(
            'user',
        ));
    }
}
