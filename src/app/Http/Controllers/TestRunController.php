<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestRunController extends Controller
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
     * Show (render) the test run page.
     *
     * @return view
     */
    public function index(Request $request)
    {
        return view('runs/runsIndex');
    }
}
