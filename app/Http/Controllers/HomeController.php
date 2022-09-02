<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function getDashboard()
    {
        return view('dashboard');
    }

    public function postDelete(Request $request)
    {
        dd('HERE');
    }
}
