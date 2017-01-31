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
    }

    /**
     * Show the requirements page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // $projects = Project::all();
        // foreach ($projects as $project) {
        //     echo $project->Name;
        // }
        return view('requirements');
    }
}
