<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Country;
use App\City;
use App\State;
use App\SiteIntro;
use App\Posttag;
use App\Post;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ApiController extends Controller
{
    public function intro()
    {
        $url = env('APP_URL') . '/storage/app/public/intro/';
        $data = SiteIntro::select("site_intro.id", "site_intro.heading", "site_intro.short_description", DB::raw("CONCAT('" . $url . "', site_intro.image) as image"))
            ->orderBy('id', 'desc')
            ->take(3)->get();
        return response()->json(['status' => true, 'message' => 'Introduction', 'data' => $data]);
    }
    public function country()
    {
        $url = env('ASSET_URL') . '/flags/';
        $ext = '.png';

        // Fetch only India
        $countries = Country::select(
            'id',
            'code',
            'name',
            'phonecode',
            DB::raw("CONCAT(CONCAT('" . $url . "', LOWER(countries.code)),'" . $ext . "') as flag")
        )
            ->where('name', 'India') // filter for India
            ->get();

        return response()->json(['status' => true, 'message' => 'Country List', 'data' => $countries]);
    }


    public function state($id)
    {
        $url = env('APP_URL');
        if ($id != '') {
            $states = State::select('id', 'name')->where('country_id', $id)->get();
            return response()->json(['status' => true, 'message' => 'State List', 'data' => $states]);
        } else {
            return response()->json(['status' => false, 'message' => 'Please enter valid country id', 'data' => []]);
        }
    }

    public function city($id)
    {
        $url = env('APP_URL');
        if ($id != '') {
            $cities = City::select('id', 'name')->where('state_id', $id)->get();
            return response()->json(['status' => true, 'message' => 'City List', 'data' => $cities]);
        } else {
            return response()->json(['status' => false, 'message' => 'Please enter valid country id', 'data' => []]);
        }
    }




}