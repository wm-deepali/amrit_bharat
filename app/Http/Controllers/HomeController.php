<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        try {
            $user = Auth::user();

            // Common
            $reporterCount = User::where('role', 'reporter')->count();

            if ($user->role == 'admin') {

                $postCount = Post::count();
                $pendingCount = Post::where('status', 'pending')->count();
                $totalViews = Post::sum('views');

            } else {

                $postCount = Post::where('user_id', $user->id)->count();
                $pendingCount = Post::where('user_id', $user->id)->where('status', 'pending')->count();
                $totalViews = Post::where('user_id', $user->id)->sum('views');
            }

            return view('admin.index', compact('postCount', 'pendingCount', 'reporterCount', 'totalViews'));

        } catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }

}
