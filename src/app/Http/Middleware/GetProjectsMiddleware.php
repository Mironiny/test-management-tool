<?php

namespace App\Http\Middleware;

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
        $projects = Project::all();
        View::share('projects', $projects);

        // Set and serve actually selected project and initialize session
        if (!$request->session()->has('selectedProject')) {
            if (Project::all()->count() < 1) {
                $request->session()->put('selectedProject', 0);
            }
            else {
                $request->session()->put('selectedProject', 1);
            }
        }
        $selectedProject = Project::find($request->session()->get('selectedProject'));
        View::share('selectedProject', $selectedProject);

        return $next($request);
    }
}
