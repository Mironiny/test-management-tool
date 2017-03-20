<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;
use App\Project;
use View;

/**
 * Middleware for projects. Serves all avaible project and actualy selected project.
 *
 */
class GetProjectsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $projects = Auth::user()->projects()->whereNull('ActiveDateTo')->get();

        View::share('projects', $projects);

        // Set and serve actually selected project and initialize session
        if (!$request->session()->has('selectedProject') || $request->session()->get('selectedProject') === 0) {
            if (Auth::user()->projects()->count() < 1) {
                $request->session()->put('selectedProject', 0);
            }
            else {
                $project = Auth::user()->projects()->first();
                $request->session()->put('selectedProject', $project->SUT_id);
            }
        }
        $selectedProject = Project::find($request->session()->get('selectedProject'));
        View::share('selectedProject', $selectedProject);

        return $next($request);
    }
}
