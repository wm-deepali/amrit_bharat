<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Banner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    // -----------------------------
    // ALL BANNERS
    // -----------------------------
    public function allBanners(Request $request)
    {
        $type = $request->type ?? 'published';
        $query = Banner::query();

        if ($type != 'all') {
            $query->where('status', $type);
        }

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('url', 'like', "%$search%");
            });
        }

        $banners = $query->latest()->get();

        return response()->json([
            'status' => true,
            'data' => $banners
        ]);
    }

}
