<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Hashtag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HashtagController extends Controller
{
    // -----------------------------
    // LIST HASHTAGS WITH SEARCH
    // -----------------------------
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!isset($user) || empty($user) || $user->delete_status != '0') {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive. Contact your administrator to activate it.'
            ], 401);
        }

        $query = Hashtag::query();

        // Optional type filter
        $type = $request->get('type', 'active'); // all, active, inactive
        if ($type == 'active') {
            $query->where('status', 'active');
        } elseif ($type == 'inactive') {
            $query->where('status', 'inactive');
        }

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('hashtag', 'like', "%$search%");
            });
        }

        $hashtags = $query->orderBy('id', 'desc')->get();

        return response()->json([
            'status' => true,
            'data' => $hashtags
        ]);
    }
}
