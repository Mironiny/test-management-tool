<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;

class RequirementsController extends Controller
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
     * Show the requirements page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $selectedProject = $request->session()->get('selectedProject');
        $requirements = Project::find($selectedProject)->requirements->sortBy('ActiveDateFrom')->all();
        return view('requirements/requirements')->with('requirements', $requirements);
    }
}
