<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Adsetting extends Model
{
    protected $fillable=[
        'id',
        'homepageuppersidebar300x250time',
        'homepageuppersidebar300x250number',
        'homepagemiddlesidebar300x250time',
        'homepagemiddlesidebar300x250number',
        'homepagelowersidebar300x250time',
        'homepagelowersidebar300x250number',
        'homepageuppersidebar300x600time',
        'homepageuppersidebar300x600number',
        'homepagemiddlesidebar300x600time',
        'homepagemiddlesidebar300x600number',
        'homepagelowersidebar300x600time',
        'homepagelowersidebar300x600number',
        'homepageupperbanner728x90time',
        'homepageupperbanner728x90number',
        'homepagemiddlebanner728x90time',
        'homepagemiddlebanner728x90number',
        'homepagelowerbanner728x90time',
        'homepagelowerbanner728x90number',
        'homepagelowestbanner728x90time',
        'homepagelowestbanner728x90number',
        'categorypagesidebar300x250time',
        'categorypagesidebar300x250number',
        'categorypagesidebar300x600time',
        'categorypagesidebar300x600number',
        'postpageuppersidebar300x250time',
        'postpageuppersidebar300x250number',
        'postpagelowersidebar300x250time',
        'postpagelowersidebar300x250number',
        'postpagesidebar300x600time',
        'postpagesidebar300x600number',
    ];
}
