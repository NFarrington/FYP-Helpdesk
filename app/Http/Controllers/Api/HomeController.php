<?php

namespace App\Http\Controllers\Api;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['version' => '1']);
    }
}
