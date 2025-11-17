<?php

namespace App\Http\Controllers;

use App\Aboutus;
use App\Ad;
use App\Adsetting;
use App\Category;
use App\City;
use App\Comment;
use App\PostComment;
use App\CommentReply;
use App\ContactusContent;
use App\Contactus;
use App\Cookiepolicy;
use App\Epaper;
use App\Footersetting;
use App\Headersetting;
use App\Homepagewidget;
use App\Post;
use App\Postcategory;
use App\Postsubcategory;
use App\Privacypolicy;
use App\Questionoftheday;
use App\State;
use App\Subcategory;
use App\Subscriber;
use App\Tag;
use App\Team;
use App\Teamcategory;
use App\Termsofuse;
use App\User;
use Illuminate\Support\Facades\Storage;
use Image;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Notifications\NewsLikeNotification;
use App\Traits\Notification;
use App\Notifications\NewsCommentNotification;

class FrontController extends Controller
{
    use Notification;
    public function index()
    {
        $headersetting=Headersetting::select('datetimeformat','image')->first();
        $footersetting=Footersetting::first();
        $headercategorieswithsubcategory=Category::where('status','active')->where('showonheader','yes')->where('hassubcategory','yes')->with(['subcategories'])->get();
        $headercategorieswithoutsubcategory=Category::where('status','active')->where('showonheader','yes')->where('hassubcategory','no')->orderBy('sequence','asc')->get();
        $tags=Tag::select(['id','slug','name'])->where('status','active')->orderBy('sequence', 'asc')->take(7)->get();
        $footercategories=Category::select(['id','slug','name'])->where('status','active')->where('showonfooter','yes')->get();
        $breaking_news_ids = Postcategory::latest('id')->where('category_id',14)->limit(10)->pluck('post_id')->toArray();
        $breakingnews = Post::latest('id')->select(['id','slug','title'])->whereIn('id',$breaking_news_ids)->where('user_delete_status','0')->where('status','published')->take(10)->get();
        // Common
        $homepagewidget=Homepagewidget::firstOrFail();
       
        $centercategoryids=$homepagewidget->centercategories->pluck('category_id')->toArray();
        $lowercategoryids=$homepagewidget->lowercategories->pluck('category_id')->toArray();
        
        $center_1_post_ids = Postcategory::latest('id')->where('category_id',$centercategoryids[0])->limit(4)->pluck('post_id')->toArray();
        $center1posts = Post::select(['id','slug','image','title'])->latest('id')->whereIn('id',$center_1_post_ids)->where('status','published')->where('user_delete_status','0')->take(4)->get();
        $center1category=Category::findOrFail($centercategoryids[0]);
        
        $center_2_post_ids = Postcategory::latest('id')->where('category_id',$centercategoryids[1])->limit(4)->pluck('post_id')->toArray();
        $center2posts = Post::select(['id','slug','image','title'])->latest('id')->whereIn('id',$center_2_post_ids)->where('status','published')->where('user_delete_status','0')->take(4)->get();
        $center2category=Category::findOrFail($centercategoryids[1]);
        
        $center_3_post_ids = Postcategory::latest('id')->where('category_id',$centercategoryids[2])->limit(4)->pluck('post_id')->toArray();
        $center3posts = Post::select(['id','slug','image','title'])->latest('id')->whereIn('id',$center_3_post_ids)->where('status','published')->where('user_delete_status','0')->take(4)->get();
        $center3category=Category::findOrFail($centercategoryids[2]);
        
        
        /*********lower categories Data ******/
        
        $lower_1_post_ids = Postcategory::latest('id')->where('category_id',$lowercategoryids[0])->limit(4)->pluck('post_id')->toArray();
        $lower1posts = Post::select(['id','slug','image','title'])->latest('id')->whereIn('id',$lower_1_post_ids)->where('status','published')->where('user_delete_status','0')->take(4)->get();
        $lower1category=Category::findOrFail($lowercategoryids[0]);
        
        $lower_2_post_ids = Postcategory::latest('id')->where('category_id',$lowercategoryids[1])->limit(4)->pluck('post_id')->toArray();
        $lower2posts = Post::select(['id','slug','image','title'])->latest('id')->whereIn('id',$lower_2_post_ids)->where('status','published')->where('user_delete_status','0')->take(4)->get();
        $lower2category=Category::findOrFail($lowercategoryids[1]);
        
        $lower_3_post_ids = Postcategory::latest('id')->where('category_id',$lowercategoryids[2])->limit(4)->pluck('post_id')->toArray();
        $lower3posts = Post::select(['id','slug','image','title'])->latest('id')->whereIn('id',$lower_3_post_ids)->where('status','published')->where('user_delete_status','0')->take(4)->get();
        $lower3category=Category::findOrFail($lowercategoryids[2]);
        
        
        
        $videocategoryids=$homepagewidget->videocategories->pluck('category_id')->toArray();
        $videocategories=Category::whereIn('id',$videocategoryids)->get();
        if($homepagewidget->cataloguecategory=='*'){
            if ($homepagewidget->catalogueposttype=='all') {
                $catalogueposts=Post::select(['id','slug','image','title'])->where('status','published')->where('user_delete_status','0')->orderBy('id','desc')->take(5)->get();
            } elseif($homepagewidget->catalogueposttype=='trending') {
                $catalogueposts=Post::select(['id','slug','image','title'])->where('status','published')->where('user_delete_status','0')->orderBy('views', 'desc')->take(5)->get();
            } elseif($homepagewidget->catalogueposttype=='latest') {
                $catalogueposts=Post::select(['id','slug','image','title'])->where('status','published')->where('user_delete_status','0')->orderBy('id', 'desc')->take(5)->get();
            } elseif($homepagewidget->catalogueposttype=='oldest') {
                $catalogueposts=Post::select(['id','slug','image','title'])->where('status','published')->where('user_delete_status','0')->orderBy('id', 'asc')->take(5)->get();
            }
        } else {
            if ($homepagewidget->catalogueposttype=='all') {
                $catalogueposts = Post::select(['id','slug','image','title'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                    $query->where('category_id',$homepagewidget->cataloguecategory);
                })->where('status','published')->where('user_delete_status','0')->orderBy('id','desc')->take(5)->get();
            } elseif($homepagewidget->catalogueposttype=='trending') {
                $catalogueposts = Post::select(['id','slug','image','title'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                    $query->where('category_id',$homepagewidget->cataloguecategory);
                })->where('status','published')->where('user_delete_status','0')->orderBy('views', 'desc')->take(5)->get();
            } elseif($homepagewidget->catalogueposttype=='latest') {
                $catalogueposts = Post::select(['id','slug','image','title'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                    $query->where('category_id',$homepagewidget->cataloguecategory);
                })->where('status','published')->where('user_delete_status','0')->orderBy('id', 'desc')->take(5)->get();
            } elseif($homepagewidget->catalogueposttype=='oldest') {
                $catalogueposts = Post::select(['id','slug','image','title'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                    $query->where('category_id',$homepagewidget->cataloguecategory);
                })->where('status','published')->where('user_delete_status','0')->orderBy('id', 'asc')->take(5)->get();
            }
        }
        if ($homepagewidget->categorytab1posttype=='all') {
            $uppertab1posts = Post::select(['id','slug','title'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->categorytab1);
            })->where('status','published')->where('user_delete_status','0')->orderBy('id', 'desc')->take(7)->get();
        } elseif($homepagewidget->categorytab1posttype=='trending') {
            $uppertab1posts = Post::select(['id','slug','title'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->categorytab1);
            })->where('status','published')->where('user_delete_status','0')->take(7)->orderBy('views', 'desc')->get();
        } elseif($homepagewidget->categorytab1posttype=='latest') {
            $uppertab1posts = Post::select(['id','slug','title'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->categorytab1);
            })->where('status','published')->where('user_delete_status','0')->take(7)->orderBy('id', 'desc')->get();
        } elseif($homepagewidget->categorytab1posttype=='oldest') {
            $uppertab1posts = Post::select(['id','slug','title'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->categorytab1);
            })->where('status','published')->where('user_delete_status','0')->take(7)->orderBy('id', 'asc')->get();
        }
        $uppertab1category=Category::findOrFail($homepagewidget->categorytab1);
        if ($homepagewidget->categorytab2posttype=='all') {
            $uppertab2posts = Post::select(['id','slug','title'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->categorytab2);
            })->where('status','published')->where('user_delete_status','0')->orderBy('id','desc')->take(7)->get();
        } elseif($homepagewidget->categorytab2posttype=='trending') {
            $uppertab2posts = Post::select(['id','slug','title'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->categorytab2);
            })->where('status','published')->where('user_delete_status','0')->take(7)->orderBy('views', 'desc')->get();
        } elseif($homepagewidget->categorytab2posttype=='latest') {
            $uppertab2posts = Post::select(['id','slug','title'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->categorytab2);
            })->where('status','published')->where('user_delete_status','0')->take(7)->orderBy('id', 'desc')->get();
        } elseif($homepagewidget->categorytab2posttype=='oldest') {
            $uppertab2posts = Post::select(['id','slug','title'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->categorytab2);
            })->where('status','published')->where('user_delete_status','0')->take(7)->orderBy('id', 'asc')->get();
        }
         
        $uppertab2category=Category::findOrFail($homepagewidget->categorytab2);
        if ($homepagewidget->categorytab3posttype=='all') {
            $uppertab3posts = Post::select(['id','slug','title'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->categorytab3);
            })->where('status','published')->where('user_delete_status','0')->orderBy('id','desc')->take(7)->get();
        } elseif($homepagewidget->categorytab3posttype=='trending') {
            $uppertab3posts = Post::select(['id','slug','title'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->categorytab3);
            })->where('status','published')->where('user_delete_status','0')->take(7)->orderBy('views', 'desc')->get();
        } elseif($homepagewidget->categorytab3posttype=='latest') {
            $uppertab3posts = Post::select(['id','slug','title'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->categorytab3);
            })->where('status','published')->where('user_delete_status','0')->take(7)->orderBy('id', 'desc')->get();
        } elseif($homepagewidget->categorytab3posttype=='oldest') {
            $uppertab3posts = Post::select(['id','slug','title'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->categorytab3);
            })->where('status','published')->where('user_delete_status','0')->take(7)->orderBy('id', 'asc')->get();
        }
        
        $uppertab3category=Category::findOrFail($homepagewidget->categorytab3);
        if ($homepagewidget->categorytab4posttype=='all') {
            $uppertab4posts = Post::select(['id','slug','title'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->categorytab4);
            })->where('status','published')->where('user_delete_status','0')->orderBy('id','desc')->take(7)->get();
        } elseif($homepagewidget->categorytab4posttype=='trending') {
            $uppertab4posts = Post::select(['id','slug','title'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->categorytab4);
            })->where('status','published')->where('user_delete_status','0')->take(7)->orderBy('views', 'desc')->get();
        } elseif($homepagewidget->categorytab4posttype=='latest') {
            $uppertab4posts = Post::select(['id','slug','title'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->categorytab4);
            })->where('status','published')->where('user_delete_status','0')->take(7)->orderBy('id', 'desc')->get();
        } elseif($homepagewidget->categorytab4posttype=='oldest') {
            $uppertab4posts = Post::select(['id','slug','title'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->categorytab4);
            })->where('status','published')->where('user_delete_status','0')->take(7)->orderBy('id', 'asc')->get();
        }
        $uppertab4category=Category::findOrFail($homepagewidget->categorytab4);
        if($homepagewidget->slidercategory=='*'){
            if ($homepagewidget->sliderposttype=='all') {
                $sliderposts=Post::select(['id','slug','title','image'])->where('status','published')->where('user_delete_status','0')->orderBy('id','desc')->take(5)->get();
            } elseif($homepagewidget->sliderposttype=='trending') {
                $sliderposts=Post::select(['id','slug','title','image'])->where('status','published')->where('user_delete_status','0')->orderBy('views', 'desc')->take(5)->get();
            } elseif($homepagewidget->sliderposttype=='latest') {
                $sliderposts=Post::select(['id','slug','title','image'])->where('status','published')->where('user_delete_status','0')->orderBy('id', 'desc')->take(5)->get();
            } elseif($homepagewidget->sliderposttype=='oldest') {
                $sliderposts=Post::where('status','published')->where('user_delete_status','0')->orderBy('id', 'asc')->take(5)->get();
            }
        } else {
            if ($homepagewidget->sliderposttype=='all') {
                $sliderposts = Post::select(['id','slug','title','image'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                    $query->where('category_id',$homepagewidget->slidercategory);
                })->where('status','published')->where('user_delete_status','0')->orderBy('id','desc')->take(5)->get();
            } elseif($homepagewidget->sliderposttype=='trending') {
                $sliderposts = Post::select(['id','slug','title','image'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                    $query->where('category_id',$homepagewidget->slidercategory);
                })->where('status','published')->where('user_delete_status','0')->orderBy('views', 'desc')->take(5)->get();
            } elseif($homepagewidget->sliderposttype=='latest') {
                $sliderposts = Post::select(['id','slug','title','image'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                    $query->where('category_id',$homepagewidget->slidercategory);
                })->where('status','published')->where('user_delete_status','0')->orderBy('id', 'desc')->take(5)->get();
            } elseif($homepagewidget->sliderposttype=='oldest') {
                $sliderposts = Post::select(['id','slug','title','image'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                    $query->where('category_id',$homepagewidget->slidercategory);
                })->where('status','published')->where('user_delete_status','0')->orderBy('id', 'asc')->take(5)->get();
            }
        }
       
        if($homepagewidget->trendingcategory=='*'){
            if ($homepagewidget->trendingposttype=='all') {
                $trendingposts=Post::select(['id','slug','title'])->where('status','published')->where('user_delete_status','0')->orderBy('id','desc')->take(9)->get();
            } elseif($homepagewidget->trendingposttype=='trending') {
                $trendingposts=Post::select(['id','slug','title'])->where('status','published')->where('user_delete_status','0')->orderBy('views', 'desc')->take(9)->get();
            } elseif($homepagewidget->trendingposttype=='latest') {
                $trendingposts=Post::select(['id','slug','title'])->where('status','published')->where('user_delete_status','0')->orderBy('id', 'desc')->take(9)->get();
            } elseif($homepagewidget->trendingposttype=='oldest') {
                $trendingposts=Post::select(['id','slug','title'])->where('status','published')->where('user_delete_status','0')->orderBy('id', 'asc')->take(9)->get();
            }
        } else {
            if ($homepagewidget->trendingposttype=='all') {
                $trendingposts = Post::select(['id','slug','title'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                    $query->where('category_id',$homepagewidget->trendingcategory);
                })->where('status','published')->where('user_delete_status','0')->orderBy('id','desc')->take(9)->get();
            } elseif($homepagewidget->trendingposttype=='trending') {
                $trendingposts = Post::select(['id','slug','title'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                    $query->where('category_id',$homepagewidget->trendingcategory);
                })->where('status','published')->where('user_delete_status','0')->orderBy('views', 'desc')->take(9)->get();
            } elseif($homepagewidget->trendingposttype=='latest') {
                $trendingposts = Post::select(['id','slug','title'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                    $query->where('category_id',$homepagewidget->trendingcategory);
                })->where('status','published')->where('user_delete_status','0')->orderBy('id', 'desc')->take(9)->get();
            } elseif($homepagewidget->trendingposttype=='oldest') {
                $trendingposts = Post::select(['id','slug','title'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                    $query->where('category_id',$homepagewidget->trendingcategory);
                })->where('status','published')->where('user_delete_status','0')->orderBy('id', 'asc')->take(9)->get();
            }
        }
        
        if ($homepagewidget->otherwidgetposttype=='all') {
            $other_widget_post_ids = Postcategory::latest('id')->where('category_id',$homepagewidget->otherwidgetcategory)->limit(16)->pluck('post_id')->toArray();
            $otherwidgetposts = Post::select(['id','slug','title','image'])->latest('id')->whereIn('id',$other_widget_post_ids)->where('status','published')->where('user_delete_status','0')->take(16)->get();
        } elseif($homepagewidget->otherwidgetposttype=='trending') {
            $otherwidgetposts = Post::select(['id','slug','title','image'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->otherwidgetcategory);
            })->where('status','published')->where('user_delete_status','0')->orderBy('views', 'desc')->take(16)->get();
        } elseif($homepagewidget->otherwidgetposttype=='latest') {
            $otherwidgetposts = Post::select(['id','slug','title','image'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->otherwidgetcategory);
            })->where('status','published')->where('user_delete_status','0')->orderBy('id', 'desc')->take(16)->get();
        } elseif($homepagewidget->otherwidgetposttype=='oldest') {
            $otherwidgetposts = Post::select(['id','slug','title','image'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->otherwidgetcategory);
            })->where('status','published')->where('user_delete_status','0')->orderBy('id', 'asc')->take(16)->get();
        }
        
        $otherwidgetcategory=Category::findOrFail($homepagewidget->otherwidgetcategory);
        
        
        if ($homepagewidget->mustreadposttype=='all') {
            $must_read_post_ids = Postcategory::latest('id')->where('category_id',$homepagewidget->mustreadcategory)->limit(16)->pluck('post_id')->toArray();
            $mustreadposts = Post::select(['id','slug','title','image'])->latest('id')->whereIn('id',$must_read_post_ids)->where('status','published')->where('user_delete_status','0')->take(16)->get();
        } elseif($homepagewidget->mustreadposttype=='trending') {
            $mustreadposts = Post::select(['id','slug','title','image'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->mustreadcategory);
            })->where('status','published')->where('user_delete_status','0')->orderBy('views', 'desc')->take(16)->get();
        } elseif($homepagewidget->mustreadposttype=='latest') {
            $mustreadposts = Post::select(['id','slug','title','image'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->mustreadcategory);
            })->where('status','published')->where('user_delete_status','0')->orderBy('id', 'desc')->take(16)->get();
        } elseif($homepagewidget->mustreadposttype=='oldest') {
            $mustreadposts = Post::select(['id','slug','title','image'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->mustreadcategory);
            })->where('status','published')->where('user_delete_status','0')->orderBy('id', 'asc')->take(16)->get();
        }
        
        $mustreadcategory=Category::findOrFail($homepagewidget->mustreadcategory);
        
        if ($homepagewidget->youmaylikeposttype=='all') {
            $you_may_like_post_ids = Postcategory::latest('id')->where('category_id',$homepagewidget->youmaylikecategory)->limit(4)->pluck('post_id')->toArray();
            $youmaylikeposts = Post::select(['id','slug','title','image'])->latest('id')->whereIn('id',$you_may_like_post_ids)->where('status','published')->where('user_delete_status','0')->take(4)->get();
        } elseif($homepagewidget->youmaylikeposttype=='trending') {
            $youmaylikeposts = Post::select(['id','slug','title','image'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->youmaylikecategory);
            })->where('status','published')->where('user_delete_status','0')->orderBy('views', 'desc')->take(4)->get();
        } elseif($homepagewidget->youmaylikeposttype=='latest') {
            $youmaylikeposts = Post::select(['id','slug','title','image'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->youmaylikecategory);
            })->where('status','published')->where('user_delete_status','0')->orderBy('id', 'desc')->take(4)->get();
        } elseif($homepagewidget->youmaylikeposttype=='oldest') {
            $youmaylikeposts = Post::select(['id','slug','title','image'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->youmaylikecategory);
            })->where('status','published')->where('user_delete_status','0')->orderBy('id', 'asc')->take(4)->get();
        }
        
        $youmaylikecategory=Category::findOrFail($homepagewidget->youmaylikecategory);
        
        if ($homepagewidget->sidebartab1posttype=='all') {
            $sidebartab1posts = Post::select(['id','slug','title','image'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->sidebartab1category);
            })->where('status','published')->where('user_delete_status','0')->orderBy('id','desc')->take(5)->get();
        } elseif($homepagewidget->sidebartab1posttype=='trending') {
            $sidebartab1posts = Post::select(['id','slug','title','image'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->sidebartab1category);
            })->where('status','published')->where('user_delete_status','0')->orderBy('views', 'desc')->take(5)->get();
        } elseif($homepagewidget->sidebartab1posttype=='latest') {
            $sidebartab1posts = Post::select(['id','slug','title','image'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->sidebartab1category);
            })->where('status','published')->where('user_delete_status','0')->orderBy('id', 'desc')->take(5)->get();
        } elseif($homepagewidget->sidebartab1posttype=='oldest') {
            $sidebartab1posts = Post::select(['id','slug','title','image'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->sidebartab1category);
            })->where('status','published')->where('user_delete_status','0')->orderBy('id', 'asc')->take(5)->get();
        }
        $sidebartab1category=Category::findOrFail($homepagewidget->sidebartab1category);
        if ($homepagewidget->sidebartab2posttype=='all') {
            $sidebartab2posts = Post::select(['id','slug','title','image'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->sidebartab2category);
            })->where('status','published')->where('user_delete_status','0')->orderBy('id','desc')->take(5)->get();
        } elseif($homepagewidget->sidebartab2posttype=='trending') {
            $sidebartab2posts = Post::select(['id','slug','title','image'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->sidebartab2category);
            })->where('status','published')->where('user_delete_status','0')->orderBy('views', 'desc')->take(5)->get();
        } elseif($homepagewidget->sidebartab2posttype=='latest') {
            $sidebartab2posts = Post::select(['id','slug','title','image'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->sidebartab2category);
            })->where('status','published')->where('user_delete_status','0')->orderBy('id', 'desc')->take(5)->get();
        } elseif($homepagewidget->sidebartab2posttype=='oldest') {
            $sidebartab2posts = Post::select(['id','slug','title','image'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->sidebartab2category);
            })->where('status','published')->where('user_delete_status','0')->orderBy('id', 'asc')->take(5)->get();
        }
        
        $sidebartab2category=Category::findOrFail($homepagewidget->sidebartab2category);
        if ($homepagewidget->sidebartab3posttype=='all') {
            $sidebar_tab3_post_ids = Postcategory::latest('id')->where('category_id',$homepagewidget->sidebartab3category)->limit(5)->pluck('post_id')->toArray();
            $sidebartab3posts = Post::select(['id','slug','title','image'])->latest('id')->whereIn('id',$sidebar_tab3_post_ids)->where('status','published')->where('user_delete_status','0')->take(5)->get();
        } elseif($homepagewidget->sidebartab3posttype=='trending') {
            $sidebartab3posts = Post::select(['id','slug','title','image'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->sidebartab3category);
            })->where('status','published')->where('user_delete_status','0')->orderBy('views', 'desc')->take(5)->get();
        } elseif($homepagewidget->sidebartab3posttype=='latest') {
            $sidebartab3posts = Post::select(['id','slug','title','image'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->sidebartab3category);
            })->where('status','published')->where('user_delete_status','0')->orderBy('id', 'desc')->take(5)->get();
        } elseif($homepagewidget->sidebartab3posttype=='oldest') {
            $sidebartab3posts = Post::select(['id','slug','title','image'])->whereHas('categories', function (Builder $query) use($homepagewidget) {
                $query->where('category_id',$homepagewidget->sidebartab3category);
            })->where('status','published')->where('user_delete_status','0')->orderBy('id', 'asc')->take(5)->get();
        }
        $sidebartab3category=Category::findOrFail($homepagewidget->sidebartab3category);
        $question=Questionoftheday::where('status','active')->first();
        $today=now()->toDateString();
        $uppersidebar300x250=Ad::whereDate('startdate','<=',$today)->whereDate('enddate','>=',$today)->where('page','homepage')->where('position','uppersidebar300x250')->where('status','active')->get();
        $middlesidebar300x250=Ad::whereDate('startdate','<=',$today)->whereDate('enddate','>=',$today)->where('page','homepage')->where('position','middlesidebar300x250')->where('status','active')->get();
        $lowersidebar300x250=Ad::whereDate('startdate','<=',$today)->whereDate('enddate','>=',$today)->where('page','homepage')->where('position','lowersidebar300x250')->where('status','active')->get();
        $uppersidebar300x600=Ad::whereDate('startdate','<=',$today)->whereDate('enddate','>=',$today)->where('page','homepage')->where('position','uppersidebar300x600')->where('status','active')->get();
        $middlesidebar300x600=Ad::whereDate('startdate','<=',$today)->whereDate('enddate','>=',$today)->where('page','homepage')->where('position','middlesidebar300x600')->where('status','active')->get();
        // print_r($middlesidebar300x600);
        $lowersidebar300x600=Ad::whereDate('startdate','<=',$today)->whereDate('enddate','>=',$today)->where('page','homepage')->where('position','lowersidebar300x600')->where('status','active')->get();
        $upperbanner728x90=Ad::whereDate('startdate','<=',$today)->whereDate('enddate','>=',$today)->where('page','homepage')->where('position','upperbanner728x90')->where('status','active')->get();
        $middlebanner728x90=Ad::whereDate('startdate','<=',$today)->whereDate('enddate','>=',$today)->where('page','homepage')->where('position','middlebanner728x90')->where('status','active')->get();
        $lowerbanner728x90=Ad::whereDate('startdate','<=',$today)->whereDate('enddate','>=',$today)->where('page','homepage')->where('position','lowerbanner728x90')->where('status','active')->get();
        $lowestbanner728x90=Ad::whereDate('startdate','<=',$today)->whereDate('enddate','>=',$today)->where('page','homepage')->where('position','lowestbanner728x90')->where('status','active')->get();
        $adsetting=Adsetting::first();
        
        return view('front.index')
        ->with('headersetting',$headersetting)
        ->with('footersetting',$footersetting)
        ->with('homepagewidget',$homepagewidget)
        ->with('tags',$tags)
        ->with('headercategorieswithsubcategories',$headercategorieswithsubcategory)
        ->with('headercategorieswithoutsubcategories',$headercategorieswithoutsubcategory)
        ->with('footercategories',$footercategories)
        ->with('catalogueposts',$catalogueposts)
        ->with('uppertab1category',$uppertab1category)
        ->with('uppertab1posts',$uppertab1posts)
        ->with('uppertab2category',$uppertab2category)
        ->with('uppertab2posts',$uppertab2posts)
        ->with('uppertab3category',$uppertab3category)
        ->with('uppertab3posts',$uppertab3posts)
        ->with('uppertab4category',$uppertab4category)
        ->with('uppertab4posts',$uppertab4posts)
        ->with('sliderposts',$sliderposts)
        ->with('trendingposts',$trendingposts)
        ->with('otherwidgetcategory',$otherwidgetcategory)
        ->with('otherwidgetposts',$otherwidgetposts)
        ->with('mustreadcategory',$mustreadcategory)
        ->with('mustreadposts',$mustreadposts)
        ->with('youmaylikecategory',$youmaylikecategory)
        ->with('youmaylikeposts',$youmaylikeposts)
        ->with('sidebartab1category',$sidebartab1category)
        ->with('sidebartab1posts',$sidebartab1posts)
        ->with('sidebartab2category',$sidebartab2category)
        ->with('sidebartab2posts',$sidebartab2posts)
        ->with('sidebartab3category',$sidebartab3category)
        ->with('sidebartab3posts',$sidebartab3posts)
        ->with('center1category',$center1category)
        ->with('center1posts',$center1posts)
        ->with('center2category',$center2category)
        ->with('center2posts',$center2posts)
        ->with('center3category',$center3category)
        ->with('center3posts',$center3posts)
        
        ->with('lower1category',$lower1category)
        ->with('lower1posts',$lower1posts)
        ->with('lower2category',$lower2category)
        ->with('lower2posts',$lower2posts)
        ->with('lower3category',$lower3category)
        ->with('lower3posts',$lower3posts)
        ->with('videocategories',$videocategories)
        ->with('breakingnews',$breakingnews)
        ->with('question',$question)
        ->with('uppersidebar300x250',$uppersidebar300x250)
        ->with('middlesidebar300x250',$middlesidebar300x250)
        ->with('lowersidebar300x250',$lowersidebar300x250)
        ->with('uppersidebar300x600',$uppersidebar300x600)
        ->with('middlesidebar300x600',$middlesidebar300x600)
        ->with('lowersidebar300x600',$lowersidebar300x600)
        ->with('upperbanner728x90',$upperbanner728x90)
        ->with('middlebanner728x90',$middlebanner728x90)
        ->with('lowerbanner728x90',$lowerbanner728x90)
        ->with('lowestbanner728x90',$lowestbanner728x90)
        ->with('adsetting',$adsetting);
    }

    public function postbycategory($categoryurl,$subcategoryurl=null)
    {
        try {
            // Common
            $headersetting=Headersetting::first();
            $footersetting=Footersetting::first();
            $headercategorieswithsubcategory=Category::where('status','active')->where('showonheader','yes')->where('hassubcategory','yes')->with(['subcategories'])->get();
            $headercategorieswithoutsubcategory=Category::where('status','active')->where('showonheader','yes')->where('hassubcategory','no')->orderBy('sequence','asc')->get();
            $tags=Tag::where('status','active')->orderBy('sequence', 'asc')->take(7)->get();
            $footercategories=Category::where('status','active')->where('showonfooter','yes')->get();
            $breaking_news_ids = Postcategory::latest('id')->where('category_id',14)->limit(10)->pluck('post_id')->toArray();
            $breakingnews = Post::latest('id')->select(['id','slug','title'])->whereIn('id',$breaking_news_ids)->where('status','published')->where('user_delete_status','0')->take(10)->get();
            // Common
            $category=Category::where('slug',$categoryurl)->firstOrFail();
            $subcategory=Null;
            if($subcategoryurl){
                $subcategory=Subcategory::where('slug',$subcategoryurl)->firstorFail();
                $post_ids = Postsubcategory::where('subcategory_id',$subcategory->id)->pluck('post_id')->toArray();
                $posts = Post::latest('id')->whereIn('id',$post_ids)->where('status','published')->where('user_delete_status','0')->paginate(20);
            } else {
                $post_ids = Postcategory::latest('id')->where('category_id',$category->id)->pluck('post_id')->toArray();
                $posts = Post::latest('id')->whereIn('id',$post_ids)->where('status','published')->where('user_delete_status','0')->paginate(20);
            }
            $big_post_ids = Postcategory::latest('id')->where('category_id',$category->id)->limit(5)->pluck('post_id')->toArray();
            $bigposts = Post::select(['id','slug','image','title'])->whereIn('id',$big_post_ids)->where('user_delete_status','0')->where('status','published')->with(['categories'])->orderBy('views', 'desc')->take(5)->get();
            $today=now()->toDateString();
            $sidebar300x250=Ad::whereHas('categories',function (Builder $query) use($category){
                $query->where('category_id',$category->id);
            })->whereDate('startdate','<=',$today)->whereDate('enddate','>=',$today)->where('page','categorypage')->where('position','sidebar300x250')->where('status','active')->get();
            
            $sidebar300x600=Ad::whereHas('categories',function (Builder $query) use($category){
                $query->where('category_id',$category->id);
            })->whereDate('startdate','<=',$today)->whereDate('enddate','>=',$today)->where('page','categorypage')->where('position','sidebar300x600')->where('status','active')->get();
            $adsetting=Adsetting::first();
            return view('front.category')
            ->with('headersetting',$headersetting)->with('footersetting',$footersetting)
            ->with('headercategorieswithsubcategories',$headercategorieswithsubcategory)
            ->with('headercategorieswithoutsubcategories',$headercategorieswithoutsubcategory)
            ->with('tags',$tags)
            ->with('footercategories',$footercategories)
            ->with('category',$category)
            ->with('subcategory',$subcategory)
            ->with('posts',$posts)
            ->with('bigposts',$bigposts)
            ->with('breakingnews',$breakingnews)
            ->with('sidebar300x250',$sidebar300x250)
            ->with('sidebar300x600',$sidebar300x600)
            ->with('adsetting',$adsetting);
        } catch (\Exception $ex) {

        }
    }

    public function postdetail($categoryurl,$posturl)
    {
        try {
            // Common
            $headersetting=Headersetting::first();
            $footersetting=Footersetting::first();
            $headercategorieswithsubcategory=Category::where('status','active')->where('showonheader','yes')->where('hassubcategory','yes')->with(['subcategories'])->get();
            $headercategorieswithoutsubcategory=Category::where('status','active')->where('showonheader','yes')->where('hassubcategory','no')->orderBy('sequence','asc')->get();
            $tags=Tag::where('status','active')->orderBy('sequence', 'asc')->take(7)->get();
            $footercategories=Category::where('status','active')->where('showonfooter','yes')->get();
            $breaking_news_ids = Postcategory::latest('id')->where('category_id',14)->limit(10)->pluck('post_id')->toArray();
            $breakingnews = Post::latest('id')->select(['id','slug','title'])->whereIn('id',$breaking_news_ids)->where('status','published')->where('user_delete_status','0')->take(10)->get();
            // Common
            $category=Category::where('slug',$categoryurl)->firstOrFail();
            $post = Post::where('slug',$posturl)->with(['tags','comments'])->firstOrFail();
            $big_post_ids = Postcategory::where('category_id',$category->id)->pluck('post_id')->toArray();
            $bigposts = Post::select(['slug','image','title'])->whereIn('id',$big_post_ids)->where('status','published')->where('user_delete_status','0')->orderBy('views', 'desc')->take(5)->get();
            $homepagewidget=Homepagewidget::first();
            if ($homepagewidget->youmaylikeposttype=='all') {
                $you_may_like_post_ids = Postcategory::where('category_id',$homepagewidget->youmaylikecategory)->pluck('post_id')->toArray();
                $youmaylikeposts = Post::whereIn('id',$you_may_like_post_ids)->where('status','published')->where('user_delete_status','0')->take(4)->get();
            } elseif($homepagewidget->youmaylikeposttype=='trending') {
                $you_may_like_post_ids = Postcategory::where('category_id',$homepagewidget->youmaylikecategory)->pluck('post_id')->toArray();
                $youmaylikeposts = Post::whereIn('id',$you_may_like_post_ids)->where('status','published')->where('user_delete_status','0')->orderBy('views', 'desc')->take(4)->get();
            } elseif($homepagewidget->youmaylikeposttype=='latest') {
                $you_may_like_post_ids = Postcategory::where('category_id',$homepagewidget->youmaylikecategory)->pluck('post_id')->toArray();
                $youmaylikeposts = Post::latest('id')->whereIn('id',$you_may_like_post_ids)->where('status','published')->where('user_delete_status','0')->take(4)->get();
            } elseif($homepagewidget->youmaylikeposttype=='oldest') {
                $you_may_like_post_ids = Postcategory::where('category_id',$homepagewidget->youmaylikecategory)->pluck('post_id')->toArray();
                $youmaylikeposts = Post::whereIn('id',$you_may_like_post_ids)->where('status','published')->where('user_delete_status','0')->orderBy('id', 'asc')->take(4)->get();
            }
            $views=$post->views+1;
            Post::where('id',$post->id)->update([
                'views'=>$views,
            ]);
            $today=now()->toDateString();
            $uppersidebar300x250=Ad::whereDate('startdate','<=',$today)->whereDate('enddate','>=',$today)->where('page','postpage')->where('position','uppersidebar300x250')->where('status','active')->get();
            $lowersidebar300x250=Ad::whereDate('startdate','<=',$today)->whereDate('enddate','>=',$today)->where('page','postpage')->where('position','lowersidebar300x250')->where('status','active')->get();
            $sidebar300x600=Ad::whereDate('startdate','<=',$today)->whereDate('enddate','>=',$today)->where('page','postpage')->where('position','sidebar300x600')->where('status','active')->get();
            $adsetting=Adsetting::first();
            return view('front.news-details')
            ->with('headersetting',$headersetting)
            ->with('footersetting',$footersetting)
            ->with('headercategorieswithsubcategories',$headercategorieswithsubcategory)
            ->with('headercategorieswithoutsubcategories',$headercategorieswithoutsubcategory)
            ->with('tags',$tags)
            ->with('footercategories',$footercategories)
            ->with('category',$category)
            ->with('post',$post)
            ->with('bigposts',$bigposts)
            ->with('youmaylikeposts',$youmaylikeposts)
            ->with('views',$views)
            ->with('breakingnews',$breakingnews)
            ->with('uppersidebar300x250',$uppersidebar300x250)
            ->with('lowersidebar300x250',$lowersidebar300x250)
            ->with('sidebar300x600',$sidebar300x600)
            ->with('adsetting',$adsetting);
        } catch (\Exception $ex) {

        }
    }

    public function becomeareporter()
    {
        // Common
        $headersetting=Headersetting::first();
        $footersetting=Footersetting::first();
        $headercategorieswithsubcategory=Category::where('status','active')->where('showonheader','yes')->where('hassubcategory','yes')->with(['subcategories'])->get();
        $headercategorieswithoutsubcategory=Category::where('status','active')->where('showonheader','yes')->where('hassubcategory','no')->orderBy('sequence','asc')->get();
        $tags=Tag::where('status','active')->orderBy('sequence', 'asc')->take(7)->get();
        $footercategories=Category::where('status','active')->where('showonfooter','yes')->get();
        $breaking_news_ids = Postcategory::latest('id')->where('category_id',14)->limit(10)->pluck('post_id')->toArray();
        $breakingnews = Post::latest('id')->select(['id','slug','title'])->whereIn('id',$breaking_news_ids)->where('status','published')->where('user_delete_status','0')->take(10)->get();
        // Common
        $states=State::where('country_id','101')->get();
        return view('front.become-a-reporter')
        ->with('headersetting',$headersetting)->with('footersetting',$footersetting)
        ->with('headercategorieswithsubcategories',$headercategorieswithsubcategory)
        ->with('headercategorieswithoutsubcategories',$headercategorieswithoutsubcategory)
        ->with('tags',$tags)->with('footercategories',$footercategories)
        ->with('states',$states)->with('breakingnews',$breakingnews);
    }

    public function fetchcitybystateid(Request $request)
    {
        if($request->filled('state_id')){
            try{
                $city=City::where('state_id',$request->state_id)->orderBy('name','ASC')->get();
                return response()->json([
                    'msgCode' => '200',
                    'html' => view('front.ajax.cityoptions')->with('cities',$city)->render(),
                ]);
            }
            catch(\Exception $ex){
                return response()->json([
                    'msgCode' => '400',
                    'msgText' => $ex->getMessage(),
                ]);
            }
        }
        else{
            return response()->json([
                'msgCode' => '400',
                'msgText' => 'No State Selected',
            ]);
        }
    }

    public function storebecomeareporter(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'contact' => 'required|digits:10|unique:users',
            'gender' => 'required',
            'address' => 'required',
            'state' => 'required|integer',
            'city' => 'required|integer',
            'image' => 'required|image|max:1024',
            'cv' => 'nullable|mimes:pdf,xls,doc,docx',
        ]);
        try {
            $code = 'RP'.rand ( 100 , 999 );
            $data=array(
                'name' => $request->name,
                'email' => $request->email,
                'contact' => $request->contact,
                'password' => Hash::make($request->contact),
                'gender' => $request->gender,
                'address' => $request->address,
                'state_id' => $request->state,
                'city_id' => $request->city,
                'image' => $request->image->store('users'),
                'added_by' => 'frontend',
                'role' => 'reporter',
                'user_number' => $code
            );
            if($request->hasFile('cv')){
                $data['cv'] = $request->cv->store('users');
            }
            User::create($data);
            return redirect(route('become-a-reporter'))->with('success','Registration Submitted');
        } catch (\Exception $ex) {
            return redirect(route('become-a-reporter'))->with('error','Error Encountered '.$ex->getMessage());
        }
    }

    public function addcomment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email',
            'contact' => 'required|digits:10',
            'comment' => 'required',
        ]);
        if ($validator->passes()) {
            try {
                $user_id = $this->getUser($request->email, $request->name, $request->contact);
                $post = Post::where('id', $request->post_id)->first();
                PostComment::create([
                    'post_id'=>$request->post_id,
                    'user_id'=>$user_id,
                    'comment'=>$request->comment,
                    'status' => 'Approved'
                ]);
                
                if($post->user_id != $user_id)
                {
                    $notifyArray=array(
                        'post_id' => $post->id,
                        'title' => $post->title,
                        'name' => $request->name,
                        'type' => 'comment',
                        'user_id' => $post->user_id,
                        'comment'=>$request->comment
                    );
                    
                    $this->userNotification($notifyArray);
                }
                
                return response()->json([
                    'msgCode' => '200',
                    'msgText' => 'Comment Added',
                ]);
            } catch(\Exception $ex) {
                return response()->json([
                    'msgCode' => '400',
                    'msgText' => $ex->getMessage(),
                ]);
            }
        } else {
            return response()->json([
                'msgCode'=>'401',
                'errors'=>$validator->errors(),
            ]);
        }
    }

    public function addcommentreply(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email',
            'contact' => 'required|digits:10',
            'comment' => 'required',
        ]);
        if ($validator->passes()) {
            try {
                $post = Post::where('id', $request->post_id)->first();
                $user_id = $this->getUser($request->email, $request->name, $request->contact);
                $comment = PostComment::findOrFail($id);
                CommentReply::create([
                    'post_id'=>$request->post_id,
                    'user_id'=>$user_id,
                    'reply'=>$request->comment,
                    'comment_id'=>$id,
                    'status' => 'Approved'
                ]);
                
                if($post->user_id != $user_id)
                {
                    $notifyArray=array(
                        'post_id' => $post->id,
                        'title' => $post->title,
                        'name' => $request->name,
                        'type' => 'comment',
                        'user_id' => $post->user_id,
                        'comment'=>$request->comment
                    );
                    
                    $this->userNotification($notifyArray);
                   
                }
                
                if($comment->user_id != $user_id)
                {
                    $notifyArray1=array(
                        'post_id' => $post->id,
                        'comment' => $comment->comment,
                        'name' => $request->name,
                        'type' => 'comment',
                        'user_id' => $comment->user_id,
                        'reply'=>$request->comment
                    );
                     
                    $this->userCommentNotification($notifyArray1);
                }
                return response()->json([
                    'msgCode' => '200',
                    'msgText' => 'Comment Added',
                ]);
            } catch(\Exception $ex) {
                return response()->json([
                    'msgCode' => '400',
                    'msgText' => $ex->getMessage(),
                ]);
            }
        } else {
            return response()->json([
                'msgCode'=>'401',
                'errors'=>$validator->errors(),
            ]);
        }
    }

    public function search(Request $request)
    {
        try {
            // Common
            $headersetting=Headersetting::first();
            $footersetting=Footersetting::first();
            $headercategorieswithsubcategory=Category::where('status','active')->where('showonheader','yes')->where('hassubcategory','yes')->with(['subcategories'])->get();
            $headercategorieswithoutsubcategory=Category::where('status','active')->where('showonheader','yes')->where('hassubcategory','no')->orderBy('sequence','asc')->get();
            $tags=Tag::where('status','active')->orderBy('sequence', 'asc')->take(7)->get();
            $footercategories=Category::where('status','active')->where('showonfooter','yes')->get();
            $breaking_news_ids = Postcategory::latest('id')->where('category_id',14)->limit(10)->pluck('post_id')->toArray();
            $breakingnews = Post::with('user')->latest('id')->select(['id','slug','title'])->whereIn('id',$breaking_news_ids)->where('status','published')->where('user_delete_status','0')->take(10)->get();
            // Common
            $tag=Null;
            $keyword=Null;
            $source=Null;
            if($request->has('keyword')){
                $keyword=$request->keyword;
                $posts = Post::with('user')->where('status','published')->where('user_delete_status','0')->where('title', 'like', '%'.$keyword.'%')->orderBy('id', 'desc')->paginate(20);
                $posts->appends(['keyword' => $keyword]);

            } elseif($request->has('tag')){
                $keyword=$request->tag;
                $tag=Tag::where('slug',$request->tag)->firstOrFail();
                $posts = Post::with('user')->latest('id')->whereHas('tags', function (Builder $query) use($tag) {
                    $query->where('tag_id',$tag->id);
                })->where('status','published')->where('user_delete_status','0')->paginate(20);
                $posts->appends(['tag' => $keyword]);
            }
            elseif($request->has('source')){
                $source=$request->source;
                $posts = Post::with('user')->where('status','published')->where('user_delete_status','0')->where('user_id', $source)->orderBy('id', 'desc')->paginate(20);
                $posts->appends(['source' => $source]);

            }
            $bigposts = Post::with('user')->where('status','published')->where('user_delete_status','0')->orderBy('views', 'desc')->take(5)->get();
            return view('front.search')
            ->with('headersetting',$headersetting)->with('footersetting',$footersetting)
            ->with('headercategorieswithsubcategories',$headercategorieswithsubcategory)
            ->with('headercategorieswithoutsubcategories',$headercategorieswithoutsubcategory)
            ->with('tags',$tags)->with('footercategories',$footercategories)->with('tag',$tag)
            ->with('keyword',$keyword)->with('posts',$posts)->with('bigposts',$bigposts)->with('breakingnews',$breakingnews);
        } catch (\Exception $ex) {

        }
    }

    public function aboutus()
    {
        // Common
        $headersetting=Headersetting::first();
        $footersetting=Footersetting::first();
        $headercategorieswithsubcategory=Category::where('status','active')->where('showonheader','yes')->where('hassubcategory','yes')->with(['subcategories'])->get();
        $headercategorieswithoutsubcategory=Category::where('status','active')->where('showonheader','yes')->where('hassubcategory','no')->orderBy('sequence','asc')->get();
        $tags=Tag::where('status','active')->orderBy('sequence', 'asc')->take(7)->get();
        $footercategories=Category::where('status','active')->where('showonfooter','yes')->get();
        $breaking_news_ids = Postcategory::latest('id')->where('category_id',14)->limit(10)->pluck('post_id')->toArray();
        $breakingnews = Post::latest('id')->select(['id','slug','title'])->whereIn('id',$breaking_news_ids)->where('status','published')->where('user_delete_status','0')->take(10)->get();
        // Common
        $aboutus=Aboutus::first();
        $reporters=User::where('role','reporter')->where('status','approved')->get();
        return view('front.about-us')
        ->with('headersetting',$headersetting)->with('footersetting',$footersetting)
        ->with('headercategorieswithsubcategories',$headercategorieswithsubcategory)
        ->with('headercategorieswithoutsubcategories',$headercategorieswithoutsubcategory)
        ->with('tags',$tags)->with('footercategories',$footercategories)
        ->with('aboutus',$aboutus)->with('reporters',$reporters)->with('breakingnews',$breakingnews);
    }

    public function contactus()
    {
        // Common
        $headersetting=Headersetting::first();
        $footersetting=Footersetting::first();
        $headercategorieswithsubcategory=Category::where('status','active')->where('showonheader','yes')->where('hassubcategory','yes')->with(['subcategories'])->get();
        $headercategorieswithoutsubcategory=Category::where('status','active')->where('showonheader','yes')->where('hassubcategory','no')->orderBy('sequence','asc')->get();
        $tags=Tag::where('status','active')->orderBy('sequence', 'asc')->take(7)->get();
        $footercategories=Category::where('status','active')->where('showonfooter','yes')->get();
        $breaking_news_ids = Postcategory::latest('id')->where('category_id',14)->limit(10)->pluck('post_id')->toArray();
        $breakingnews = Post::latest('id')->select(['id','slug','title'])->whereIn('id',$breaking_news_ids)->where('status','published')->where('user_delete_status','0')->take(10)->get();
        // Common
        $aboutus=Aboutus::first();
        $contact=ContactusContent::first();
        return view('front.contact-us')
        ->with('headersetting',$headersetting)->with('footersetting',$footersetting)
        ->with('headercategorieswithsubcategories',$headercategorieswithsubcategory)
        ->with('headercategorieswithoutsubcategories',$headercategorieswithoutsubcategory)
        ->with('tags',$tags)->with('footercategories',$footercategories)->with('aboutus',$aboutus)->with('breakingnews',$breakingnews)->with('contact',$contact);
    }

    public function cookiepolicy()
    {
        // Common
        $headersetting=Headersetting::first();
        $footersetting=Footersetting::first();
        $headercategorieswithsubcategory=Category::where('status','active')->where('showonheader','yes')->where('hassubcategory','yes')->with(['subcategories'])->get();
        $headercategorieswithoutsubcategory=Category::where('status','active')->where('showonheader','yes')->where('hassubcategory','no')->orderBy('sequence','asc')->get();
        $tags=Tag::where('status','active')->orderBy('sequence', 'asc')->take(7)->get();
        $footercategories=Category::where('status','active')->where('showonfooter','yes')->get();
        $breaking_news_ids = Postcategory::latest('id')->where('category_id',14)->limit(10)->pluck('post_id')->toArray();
        $breakingnews = Post::latest('id')->select(['id','slug','title'])->whereIn('id',$breaking_news_ids)->where('status','published')->where('user_delete_status','0')->take(10)->get();
        // Common
        $cookiepolicy=Cookiepolicy::first();
        return view('front.cookie-policy')
        ->with('headersetting',$headersetting)->with('footersetting',$footersetting)
        ->with('headercategorieswithsubcategories',$headercategorieswithsubcategory)
        ->with('headercategorieswithoutsubcategories',$headercategorieswithoutsubcategory)
        ->with('tags',$tags)->with('footercategories',$footercategories)->with('cookiepolicy',$cookiepolicy)->with('breakingnews',$breakingnews);
    }

    public function privacypolicy()
    {
        // Common
        $headersetting=Headersetting::first();
        $footersetting=Footersetting::first();
        $headercategorieswithsubcategory=Category::where('status','active')->where('showonheader','yes')->where('hassubcategory','yes')->with(['subcategories'])->get();
        $headercategorieswithoutsubcategory=Category::where('status','active')->where('showonheader','yes')->where('hassubcategory','no')->orderBy('sequence','asc')->get();
        $tags=Tag::where('status','active')->orderBy('sequence', 'asc')->take(7)->get();
        $footercategories=Category::where('status','active')->where('showonfooter','yes')->get();
        $breaking_news_ids = Postcategory::latest('id')->where('category_id',14)->limit(10)->pluck('post_id')->toArray();
        $breakingnews = Post::latest('id')->select(['id','slug','title'])->whereIn('id',$breaking_news_ids)->where('status','published')->where('user_delete_status','0')->take(10)->get();
        // Common
        $privacypolicy=Privacypolicy::first();
        return view('front.privacy-policy')
        ->with('headersetting',$headersetting)->with('footersetting',$footersetting)
        ->with('headercategorieswithsubcategories',$headercategorieswithsubcategory)
        ->with('headercategorieswithoutsubcategories',$headercategorieswithoutsubcategory)
        ->with('tags',$tags)->with('footercategories',$footercategories)->with('privacypolicy',$privacypolicy)->with('breakingnews',$breakingnews);
    }

    public function termsofuse()
    {
        // Common
        $headersetting=Headersetting::first();
        $footersetting=Footersetting::first();
        $headercategorieswithsubcategory=Category::where('status','active')->where('showonheader','yes')->where('hassubcategory','yes')->with(['subcategories'])->get();
        $headercategorieswithoutsubcategory=Category::where('status','active')->where('showonheader','yes')->where('hassubcategory','no')->orderBy('sequence','asc')->get();
        $tags=Tag::where('status','active')->orderBy('sequence', 'asc')->take(7)->get();
        $footercategories=Category::where('status','active')->where('showonfooter','yes')->get();
        $breaking_news_ids = Postcategory::latest('id')->where('category_id',14)->limit(10)->pluck('post_id')->toArray();
        $breakingnews = Post::latest('id')->select(['id','slug','title'])->whereIn('id',$breaking_news_ids)->where('status','published')->where('user_delete_status','0')->take(10)->get();
        // Common
        $termsofuse=Termsofuse::first();
        return view('front.terms-of-use')
        ->with('headersetting',$headersetting)
        ->with('footersetting',$footersetting)
        ->with('headercategorieswithsubcategories',$headercategorieswithsubcategory)
        ->with('headercategorieswithoutsubcategories',$headercategorieswithoutsubcategory)
        ->with('tags',$tags)
        ->with('footercategories',$footercategories)
        ->with('termsofuse',$termsofuse)
        ->with('breakingnews',$breakingnews);
    }

    public function addcontactus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email',
            'contact' => 'required|digits:10',
            'message' => 'required',
        ]);
        if ($validator->passes()) {
            try {
                $data=array(
                    'name'=>$request->name,
                    'email'=>$request->email,
                    'contact'=>$request->contact,
                    'content'=>$request->message,
                    );
                Contactus::create($data);
                Mail::send('email.contact-enquiry', $data, function($message) {
                $message->to('prakashprabhaw@gmail.com','Prakash Prabhaw')->from('support@prakashprabhaw.com','Support Prakash Prabhaw')
                        ->subject('New Contact Enquiry');
                });
                return response()->json([
                    'msgCode' => '200',
                    'msgText' => 'Query Added',
                ]);
            } catch(\Exception $ex) {
                return response()->json([
                    'msgCode' => '400',
                    'msgText' => $ex->getMessage(),
                ]);
            }
        } else {
            return response()->json([
                'msgCode'=>'401',
                'errors'=>$validator->errors(),
            ]);
        }
    }
    
    public function ourteam()
    {
        // Common
        $headersetting=Headersetting::first();
        $footersetting=Footersetting::first();
        $headercategorieswithsubcategory=Category::where('status','active')->where('showonheader','yes')->where('hassubcategory','yes')->with(['subcategories'])->get();
        $headercategorieswithoutsubcategory=Category::where('status','active')->where('showonheader','yes')->where('hassubcategory','no')->orderBy('sequence','asc')->get();
        $tags=Tag::where('status','active')->orderBy('sequence', 'asc')->take(7)->get();
        $footercategories=Category::where('status','active')->where('showonfooter','yes')->get();
        $breaking_news_ids = Postcategory::latest('id')->where('category_id',14)->limit(10)->pluck('post_id')->toArray();
        $breakingnews = Post::latest('id')->select(['id','slug','title'])->whereIn('id',$breaking_news_ids)->where('status','published')->where('user_delete_status','0')->take(10)->get();
        // Common
        $categories=Teamcategory::with(['teams'])->where('status','active')->get();
        return view('front.our-team')
        ->with('headersetting',$headersetting)
        ->with('footersetting',$footersetting)
        ->with('headercategorieswithsubcategories',$headercategorieswithsubcategory)
        ->with('headercategorieswithoutsubcategories',$headercategorieswithoutsubcategory)
        ->with('tags',$tags)
        ->with('footercategories',$footercategories)
        ->with('breakingnews',$breakingnews)
        ->with('categories',$categories);
    }
    
    public function addsubscriber(Request $request)
    {
        $request->validate([
            'email'=>'required|email|unique:subscribers',
        ]);
        try {
            Subscriber::create([
                'email'=>$request->email,
            ]);
            return redirect()->back()->with('success','Subscribed Successfully');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error',$ex->getMessage());
        }
    }

    public function submitpoll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'option' => 'required',
        ]);
        if ($validator->passes()) {
            try {
                $questioncurrent=Questionoftheday::where('status','active')->firstOrFail();
                $question=Questionoftheday::where('id',$questioncurrent->id)->value($request->option.'vote');
                Questionoftheday::where('id',$questioncurrent->id)->update([
                    $request->option.'vote'=>$question+1,
                ]);
                session([
                    'questionid' => $questioncurrent->id,
                ]);
                return response()->json([
                    'msgCode' => '200',
                    'msgText' => 'Vote Added',
                ]);
            } catch(\Exception $ex) {
                return response()->json([
                    'msgCode' => '400',
                    'msgText' => $ex->getMessage(),
                ]);
            }
        } else {
            return response()->json([
                'msgCode'=>'401',
                'errors'=>$validator->errors(),
            ]);
        }
    }
    
    public function epaper()
    {
        // Common
        $headersetting=Headersetting::first();
        $footersetting=Footersetting::first();
        $headercategorieswithsubcategory=Category::where('status','active')->where('showonheader','yes')->where('hassubcategory','yes')->with(['subcategories'])->get();
        $headercategorieswithoutsubcategory=Category::where('status','active')->where('showonheader','yes')->where('hassubcategory','no')->orderBy('sequence','asc')->get();
        $tags=Tag::where('status','active')->orderBy('sequence', 'asc')->take(7)->get();
        $footercategories=Category::where('status','active')->where('showonfooter','yes')->get();
        $breaking_news_ids = Postcategory::latest('id')->where('category_id',14)->limit(10)->pluck('post_id')->toArray();
        $breakingnews = Post::latest('id')->select(['id','slug','title'])->whereIn('id',$breaking_news_ids)->where('status','published')->where('user_delete_status','0')->take(10)->get();
        // Common
        $epaper=Epaper::whereDate('date',now())->first();
        $dates=Epaper::where('status','active')->orderBy('date','DESC')->get();
        return view('front.e-paper')
        ->with('headersetting',$headersetting)->with('footersetting',$footersetting)
        ->with('headercategorieswithsubcategories',$headercategorieswithsubcategory)
        ->with('headercategorieswithoutsubcategories',$headercategorieswithoutsubcategory)
        ->with('tags',$tags)->with('footercategories',$footercategories)
        ->with('breakingnews',$breakingnews)->with('epaper',$epaper)->with('dates',$dates);
    }
    
    public function filterepaper(Request $request)
    {
        try {
            if($request->filled('date')){
                $epaper=Epaper::whereDate('date',$request->date)->first();
            } else {
                $epaper=Epaper::whereDate('date',now())->first();
            }
            return response()->json([
                "msgCode" => "200",
                "html" => view('front.ajax.e-paper')->with('epaper',$epaper)->render(),
            ]);
        } catch(\Exception $ex) {
            return response()->json([
                'msgCode' => '400',
                'msgText' => $ex->getMessage(),
            ]);
        }
    }
    
    public function customfunction()
    {
        try{
            $posts=Post::where('id',8853)->get();
            foreach($posts as $post) {
                dump(Storage::size($post->image));
                // if(isset($post->image) && Storage::exists($post->image)){
                //     // dump(Storage::size($post->image));
                //     $imagehash=$post->image;
                //     $path = storage_path('app/public/'.$imagehash);
                //     Image::make($path)->resize(600,600,function($constraint){
                //         $constraint->aspectRatio();
                //         $constraint->upsize();
                //     })->save($path);   
                // }
            }
            // $posts=Post::whereNotNull('image')->skip(600)->take(500)->get();
            // $i=1;
            // dump('started');
            // foreach($posts as $post){
            //     if(isset($post->image) && Storage::exists($post->image)){
            //         $imagehash=$post->image;
            //         $path = storage_path('app/public/'.$imagehash);
            //         Image::make($path)->resize(600,600,function($constraint){
            //             $constraint->aspectRatio();
            //             $constraint->upsize();
            //         })->save($path);   
            //         dump('done-'.$i);
            //     } else {
            //         dump('Not done-'.$i);
            //     }
            //     $i++;
            // }
            dump('done');
        } catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }
    
    public function getUser($email,$name,$mobile)
    {
        $code = 'USER'.rand ( 100 , 999 );
        $userEmail = User::where('email', '=', $email)->first();
        if ($userEmail === null) {
           $userContact = User::where('contact', '=', $mobile)->first();
           if ($userContact === null) {
               $data=array(
                'name' => $name,
                'email' => $email,
                'contact' => $mobile,
                'password' => Hash::make($code),
                'added_by' => 'frontend',
                'role' => 'user',
                'user_number' => $code
                );
               $user =  User::create($data);
                return $user->id;
           }
           else
           {
               return $userContact->id;
           }
        }
        else
        {
            return $userEmail->id;
        }
    }
}