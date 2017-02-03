<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('projects');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboards/dashboard');
    }

    /**
     * Show the application dashboard from laravel theme.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexDefault()
    {
        return view('home');
    }
}