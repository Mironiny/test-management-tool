<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\User;

class UserController extends Controller
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
     * Show (render) the requirements page.
     *
     * @return view
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        return view('user/userIndex')->with('user', $user);
    }
}
