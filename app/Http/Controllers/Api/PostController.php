<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Posttag;
use App\Postcategory;
use App\Post;
use App\Reason;
use App\Tag;
use App\Ad;
use App\Adsetting;
use App\Category;
use App\Subcategory;
use App\PostBookmarkLike;
use App\PostComment;
use App\CommentReply;
use App\CommentLike;
use App\ReplyLike;
use Illuminate\Support\Str;
use Mail;
use DB;
use Carbon\Carbon;
use App\Notifications\NewsLikeNotification;
use App\Traits\Notification;
use App\Notifications\NewsCommentNotification;


class PostController extends Controller
{
    use Notification;
   
    public function getTag()
    {
        $tags = Tag::select('id', 'name', 'slug', 'metatitle', 'metadescription', 'metakeyword', 'status')
                    ->where('status', 'active')
                        ->orderBy('id', 'desc')
                        ->get();
        return response()->json(['status'=>true, 'message' => 'Tags', 'data'=>$tags]);
        
    }


    public function getCategory()
    {
        $categories = Category::select('id', 'name', 'slug', 'metatitle', 'metadescription', 'metakeyword', 'hassubcategory', 'status')
                        ->where('status', 'active')
                        ->orderBy('id', 'desc')
                        ->get();
        return response()->json(['status'=>true, 'message' => 'Categories', 'data'=>$categories]);
    }


    public function getPost(Request $request)
    {
        if($request->has('per_page'))  
        {
            $per_page=$request->per_page;
        }
        else
        {
            $per_page=5;
        }
        $url = env('APP_URL').'/storage/app/public/';
        $posts = Post::select("posts.*", DB::raw("CONCAT('".$url."', posts.image) as image"))
                    ->where('status','published')
                    ->where('user_delete_status','0')
                    ->orderBy('id', 'desc')
                    ->withCount('postcomments as total_post_comments')
                    ->paginate($per_page);

        $user =  Auth::user();
        
            $posts->getCollection()->transform(function ($row, $key)use($user) {
                if($user)
                {
                    $existData = PostBookmarkLike::where('user_id', $user->id)->where('post_id', $row->id)->first();
                }
                
                if(isset($existData) &&!empty($existData))
                {
                    $like = $existData->likes;
                    $bookmark = $existData->bookmark;
                }
                else
                {
                    $like = 0;
                    $bookmark = 0;
                }
                if($row->image == '')
                {
                    $row->image = env('APP_URL').'/public/front/images/logo.png';
                }
                $row->user_like = $like;
                $row->bookmark = $bookmark;
                
                
                return $row;
            });
        
        
        
        return response()->json(['status'=>true, 'message' => 'Posts', 'data'=>$posts]);
    }


    public function getPostByTag(Request $request)
    {
        $requestData = $request->query();
        $request->replace($requestData);
        $validator = Validator::make($requestData, [
            'tag' => 'required|exists:tags,slug',
        ]);
        if ($validator->passes()) 
        {
            if($request->has('per_page'))  
            {
                $per_page=$request->per_page;
            }
            else
            {
                $per_page=5;
            }
            $url = env('APP_URL').'/storage/app/public/';
            $tag = Tag::where('slug', $request->query('tag'))->first();
            $tagId = $tag->id;
            
            $posts =DB::table('posttags')
                ->join('posts','posttags.post_id', '=', 'posts.id')
                ->select("posts.*", DB::raw("CONCAT('".$url."', posts.image) as image"), 'posttags.tag_id')
                ->where('posttags.tag_id', $tagId)
                ->where('posts.status','published')
                ->where('posts.user_delete_status','0')
                ->orderBy('id', 'desc')
                ->paginate($per_page);
        
        $user =  Auth::user();
        
            $posts->getCollection()->transform(function ($row, $key)use($user) {
                $comment_count = PostComment::where('post_id', $row->id)->count();
               
                if($user)
                {
                    $existData = PostBookmarkLike::where('user_id', $user->id)->where('post_id', $row->id)->first();
                }
                
                if(isset($existData) &&!empty($existData))
                {
                    $like = $existData->likes;
                    $bookmark = $existData->bookmark;
                    
                }
                else
                {
                    $like = 0;
                    $bookmark = 0;
                }
                if($row->image == '')
                {
                    $row->image = env('APP_URL').'/public/front/images/logo.png';
                }
                $row->user_like = $like;
                $row->bookmark = $bookmark;
                $row->total_post_comments = $comment_count;
                
                return $row;
            });
        
        
            return response()->json(['status'=>true, 'message' => 'Posts', 'data'=>$posts]);
        }
        else
        {
            return response()->json(['status'=>false, 'message'=> 'Invalid Request', 'data'=> $validator->errors()], 401);
        }
    }


    public function getPostByCategory(Request $request)
    {
        $requestData = $request->query();
        $request->replace($requestData);
        $validator = Validator::make($requestData, [
            'slug' => 'required|exists:categories,slug',
        ]);
        if ($validator->passes()) 
        {
            if($request->has('per_page'))  
            {
                $per_page=$request->per_page;
            }
            else
            {
                $per_page=5;
            }
            $url = env('APP_URL').'/storage/app/public/';
            $category = Category::where('slug', $request->query('slug'))->where('status', 'active')->first();
            $categoryId = $category->id;
            
            $posts =DB::table('postcategories')
                ->join('posts','postcategories.post_id', '=', 'posts.id')
                ->select("posts.*", DB::raw("CONCAT('".$url."', posts.image) as image"), 'postcategories.category_id')
                ->where('postcategories.category_id', $categoryId)
                ->where('posts.status','published')
                ->where('posts.user_delete_status','0')
                ->orderBy('id', 'desc')
                ->paginate($per_page);

            $user =  Auth::user();
            //$posts = $posts->getCollection()->withCount('postcomments');
            
                $posts->getCollection()->transform(function ($row, $key)use($user) {
                    $comment_count = PostComment::where('post_id', $row->id)->count();
                    if($user)
                    {
                        $existData = PostBookmarkLike::where('user_id', $user->id)->where('post_id', $row->id)->first();
                    }
                    
                    if(isset($existData) && !empty($existData))
                    {
                        $like = $existData->likes;
                        $bookmark = $existData->bookmark;
                    }
                    else
                    {
                        $like = 0;
                        $bookmark = 0;
                    }
                    if($row->image == '')
                    {
                        $row->image = env('APP_URL').'/public/front/images/logo.png';
                    }
                    $row->user_like = $like;
                    $row->bookmark = $bookmark;
                    $row->total_post_comments = $comment_count;
                    
                    return $row;
                });
            
           return response()->json(['status'=>true, 'message' => 'Posts', 'data'=>$posts]);
        }
        else
        {
            return response()->json(['status'=>false, 'message'=> 'Invalid Request', 'data'=> $validator->errors()], 401);
        }
     }

    public function getCategoryWithSubcategories()
    {
        $categories = Category::with('subcategories')->select('id', 'name', 'slug', 'metatitle', 'metadescription', 'metakeyword', 'hassubcategory', 'status')
                        ->where('status', 'active')
                        ->orderBy('id', 'desc')
                        ->get();
        return response()->json(['status'=>true, 'message' => 'Categories', 'data'=>$categories]);
        
    }


    public function getPostBySubCategory(Request $request)
    {
        $requestData = $request->query();
        $request->replace($requestData);
        $validator = Validator::make($requestData, [
            'slug' => 'required|exists:subcategories,slug',
        ]);
        if ($validator->passes()) 
        {
            if($request->has('per_page'))  
            {
                $per_page=$request->per_page;
            }
            else
            {
                $per_page=5;
            }
            $url = env('APP_URL').'/storage/app/public/';
            $subcategory = Subcategory::where('slug', $request->query('slug'))->where('status', 'active')->first();
            $subcategoryId = $subcategory->id;
            
            $posts =DB::table('postsubcategories')
                ->join('posts','postsubcategories.post_id', '=', 'posts.id')
                ->select("posts.*", DB::raw("CONCAT('".$url."', posts.image) as image"), 'postsubcategories.subcategory_id')
                ->where('postsubcategories.subcategory_id', $subcategoryId)
                ->where('posts.status','published')
                ->where('posts.user_delete_status','0')
                ->orderBy('id', 'desc')
                ->paginate($per_page);

            $user =  Auth::user();
            
                $posts->getCollection()->transform(function ($row, $key)use($user) {
                    $comment_count = PostComment::where('post_id', $row->id)->count();
                    if($user)
                    {
                        $existData = PostBookmarkLike::where('user_id', $user->id)->where('post_id', $row->id)->first();
                    }
                    
                    if(isset($existData) &&!empty($existData))
                    {
                    
                        $like = $existData->likes;
                        $bookmark = $existData->bookmark;
                    }
                    else
                    {
                        $like = 0;
                        $bookmark = 0;
                    }
                    if($row->image == '')
                    {
                        $row->image = env('APP_URL').'/public/front/images/logo.png';
                    }
                    $row->user_like = $like;
                    $row->bookmark = $bookmark;
                    $row->total_post_comments = $comment_count;
                    
                    return $row;
               });
            

            return response()->json(['status'=>true, 'message' => 'Posts', 'data'=>$posts]);
            
        }
        else
        {
            return response()->json(['status'=>false, 'message'=> 'Invalid Request', 'data'=> $validator->errors()], 401);
        }
    }

    public function search(Request $request)
    {
        $requestData = $request->query();
        $request->replace($requestData);
        $validator = Validator::make($requestData, [
            'keyword' => 'required',
        ]);
        if ($validator->passes()) 
        {
            if($request->has('per_page'))  
            {
                $per_page=$request->per_page;
            }
            else
            {
                $per_page=5;
            }
            $url = env('APP_URL').'/storage/app/public/';
            $posts = Post::select("posts.*", DB::raw("CONCAT('".$url."', posts.image) as image"))->where('status','published')->where('user_delete_status','0')->where('title', 'like', '%'.$request->query('keyword').'%')->orderBy('id', 'desc')->withCount('postcomments as total_post_comments')->paginate($per_page);

            $user =  Auth::user();
           
                $posts->getCollection()->transform(function ($row, $key)use($user) {
                   if($user)
                    {
                        $existData = PostBookmarkLike::where('user_id', $user->id)->where('post_id', $row->id)->first();
                    }
                    
                    if(isset($existData) &&!empty($existData))
                    {
                    
                        $like = $existData->likes;
                        $bookmark = $existData->bookmark;
                    }
                    else
                    {
                        $like = 0;
                        $bookmark = 0;
                    }
                    if($row->image == '')
                    {
                        $row->image = env('APP_URL').'/public/front/images/logo.png';
                    }
                    $row->user_like = $like;
                    $row->bookmark = $bookmark;
                    
                    return $row;
                });
            
            
            return response()->json(['status'=>true, 'message' => 'Posts', 'data'=>$posts]);
            
        }
        else
        {
            return response()->json(['status'=>false, 'message'=> 'Invalid Request', 'data'=> $validator->errors()], 401);
        }
    }

    public function addView(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:posts',
           ]);

        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
        }
        $post = Post::where('id', $request->id)->where('user_delete_status','0')->first();
        if(!empty($post))
        {
            $view = $post->views + 1;
            Post::where('id', $request->id)->update(['views' => $view]);
            
            return response()->json([
                'status'=>true, 
                'message' => 'View added successfully.', 
                'data'=>[]
            ]);
        }
        else
        {
            return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=>[]], 401);
        }
        
    }


    public function addLike(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:posts,id',
           ]);

        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
        }
        $user =  Auth::user();
        if(isset($user) && !empty($user) && $user->delete_status == '0')
        {
            $post = Post::where('id', $request->post_id)->where('user_delete_status','0')->first();
            if(!empty($post))
            {
                $like = $post->likes + 1;
                
                $existLikeTable = PostBookmarkLike::where('user_id', $user->id)->where('post_id', $request->post_id)->where('likes', '1')->first();
                
                if(empty($existLikeTable))
                {
                    Post::where('id', $request->post_id)->update(['likes' => $like]);
                 }
                $existLike = PostBookmarkLike::where('user_id', $user->id)->where('post_id', $request->post_id)->first();
                if(empty($existLike))
                {
                    PostBookmarkLike::create([
                        'user_id'=>$user->id,
                        'post_id'=>$request->post_id,
                        'likes'=>'1'
                    ]);
                    
                    if($post->user_id != $user->id)
                    {   
                        $notifyArray=array(
                            'post_id' => $post->id,
                            'title' => $post->title,
                            'name' => Auth::user()->name,
                            'type' => 'like',
                            'user_id' => $post->user_id
                        );
                        $this->userNotification($notifyArray);
                        
                    }
                    if(Auth::user()->id != $post->user_id)
                    {
                        User::find($post->user_id)->notify(new NewsLikeNotification($post->user_id, $post->title));
                    }
                }
                else
                {
                    PostBookmarkLike::where('user_id', $user->id)->where('post_id', $request->post_id)->update(['likes' => '1']);
                }
            
                return response()->json([
                    'status'=>true, 
                    'message' => 'Like added successfully.', 
                    'data'=>[]
                ]);
            }
            else
            {
                return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> []], 401);
            }
        }
        else
        {
            return response()->json(['status'=>false, 'message' => 'Your account is inactive. Contact your administrator to activate it', 'data'=>''], 401);
        }
    }


    public function removeLike(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:posts,id',
           ]);

        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
        }
        $user =  Auth::user();
        if(isset($user) && !empty($user) && $user->delete_status == '0')
        {
            $post = Post::where('id', $request->post_id)->where('user_delete_status','0')->first();
            if(!empty($post))
            {
                $like = ($post->likes == 0 ) ? 0 : $post->likes - 1;
                
                $existLikeTable = PostBookmarkLike::where('user_id', $user->id)->where('post_id', $request->post_id)->where('likes', '1')->first();
                
                if(!empty($existLikeTable))
                {
                    Post::where('id', $request->post_id)->update(['likes' => $like]);
                    PostBookmarkLike::where('user_id', $user->id)->where('post_id', $request->post_id)->update(['likes' => '0']);
                }
                return response()->json([
                    'status'=>true, 
                    'message' => 'Like removed successfully.', 
                    'data'=>[]
                ]);
            
            }
        
            else
            {
                return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> []], 401);
            }
        }
        else
        {
            return response()->json(['status'=>false, 'message' => 'Your account is inactive. Contact your administrator to activate it', 'data'=>''], 401);
        }
    }

    public function addBookmark(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:posts,id',
           ]);

        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
        }
        $user =  Auth::user();
        if(isset($user) && !empty($user) && $user->delete_status == '0')
        {
            $post = Post::where('id', $request->post_id)->where('user_delete_status','0')->first();
            if(!empty($post))
            {
                $existBookmark = PostBookmarkLike::where('user_id', $user->id)->where('post_id', $request->post_id)->first();
                if(empty($existBookmark))
                {
                    PostBookmarkLike::create([
                        'user_id'=>$user->id,
                        'post_id'=>$request->post_id,
                        'bookmark'=>'1'
                    ]);
                }
                else
                {
                    PostBookmarkLike::where('user_id', $user->id)->where('post_id', $request->post_id)->update(['bookmark' => '1']);
                }
            
                return response()->json([
                    'status'=>true, 
                    'message' => 'Bookmark added successfully.', 
                    'data'=>[]
                ]);
            }
        
            else
            {
                return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> []], 401);
            }
        }
        else
        {
            return response()->json(['status'=>false, 'message' => 'Your account is inactive. Contact your administrator to activate it', 'data'=>''], 401);
        }
    }


    public function removeBookmark(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:posts,id',
           ]);

        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
        }
        $user =  Auth::user();
        if(isset($user) && !empty($user) && $user->delete_status == '0')
        {
            $post = Post::where('id', $request->post_id)->where('user_delete_status','0')->first();
            if(!empty($post))
            {
                $existBookmark = PostBookmarkLike::where('user_id', $user->id)->where('post_id', $request->post_id)->where('bookmark', '1')->first();
                if(!empty($existBookmark))
                {
                   PostBookmarkLike::where('user_id', $user->id)->where('post_id', $request->post_id)->update(['bookmark' => '0']);
                }
                
                return response()->json([
                    'status'=>true, 
                    'message' => 'Bookmark removed successfully.', 
                    'data'=>[]
                ]);
            }
        
            else
            {
                return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> []], 401);
            }
        }
        else
        {
            return response()->json(['status'=>false, 'message' => 'Your account is inactive. Contact your administrator to activate it', 'data'=>''], 401);
        }
    }

    

    public function listUserBookmark(Request $request)
    {
        $user =  Auth::user();
        if(isset($user) && !empty($user) && $user->delete_status == '0')
        {
            if(!empty($user))
            {
                if($request->has('per_page'))  
                {
                    $per_page=$request->per_page;
                }
                else
                {
                    $per_page=20;
                }
                $url = env('APP_URL').'/storage/app/public/';
                $bookmarks = PostBookmarkLike::with(['post'=>function ($q)use($url){
                                            $q->select("posts.*", DB::raw("CONCAT('".$url."', posts.image) as image"));
                                            }])->has('post')
                                            ->select('post_id', 'bookmark', 'likes as user_likes')
                                            ->where('user_id', $user->id)
                                            ->where('bookmark', '1')->paginate($per_page);
                
                $bookmarks->getCollection()->transform(function ($row, $key)use($user) 
                    {
                    if($row->post->image == '')
                    {
                        $row->post->image = env('APP_URL').'/public/front/images/logo.png';
                    }
                    
                    return $row;
                });
                                            
            return response()->json(['status'=>true, 'message' => 'Posts', 'data'=>$bookmarks]);
            }
            else
            {
                return response()->json([
                    'status'=>false, 
                    'message' => 'User not found', 
                    'data'=>[]
                ]);
            }
        }
        else
        {
            return response()->json(['status'=>false, 'message' => 'Your account is inactive. Contact your administrator to activate it', 'data'=>''], 401);
        }
        
        
    }

    public function postDetails(Request $request)
    {
        $requestData = $request->query();
        $request->replace($requestData);
        $validator = Validator::make($requestData, [
            'id' => 'required|exists:posts',
        ]);
        if ($validator->passes()) 
        {
            $user =  Auth::user();
           
                $url = env('APP_URL').'/storage/app/public/';
                $uurl = env('APP_URL').'/storage/app/public/';
                $post = Post::where('id', $request->id)->where('user_delete_status','0')->first();
                
                if(!empty($post))
                {
                    $post = Post::with(['postcomments', 'postcomments.user'=>function ($q)use($uurl){
                                        $q->select("users.id", "users.name", "users.username", DB::raw("CONCAT('".$uurl."', users.image) as image"));
                                    }, 'postcomments' => function($query)use($user){
                                        $query->withCount('commentreplies as total_reply');
                                        $query->with(['commentlikes' =>function($qu)use($user){
                                            if($user)
                                            {
                                                $qu->where('user_id', $user->id);
                                            }
                                            
                                            
                                        }]);
        
                                    }])
                                    
                                    ->select("posts.*", DB::raw("CONCAT('".$url."', posts.image) as image"))->where('status','published')->where('id', $request->query('id'))->withCount('postcomments as total_post_comments')->first();
                    //$post->loadCount('reply');
        
                    if(!empty($post))
                    {
                        $categories = Postcategory::with(['category' =>function ($q){
                            $q->select("categories.id", "categories.name", "categories.slug");
                        }])->where('post_id', $request->query('id'))->select('id', 'post_id', 'category_id')->get();
        
                        $post->categories = $categories;
                        
                        $tags = Posttag::with(['tag' =>function ($q){
                            $q->select("tags.id", "tags.name", "tags.slug");
                        }])->where('post_id', $request->query('id'))->select('id', 'post_id', 'tag_id')->get();
        
                        $post->tags = $tags;
                        $existData =[];
                        if($user)
                        {
                            $existData = PostBookmarkLike::where('user_id', $user->id)->where('post_id', $request->id)->first();
                        }
                        
                        if(!empty($existData))
                        {
                            $like = $existData->likes;
                            $bookmark = $existData->bookmark;
                        }
                        else
                        {
                            $like = 0;
                            $bookmark = 0;
                        }
                        $post->user_like = $like;
                        $post->bookmark = $bookmark;
                        if($post->image == '')
                        {
                            $post->image = env('APP_URL').'/public/front/images/logo.png';
                        }
                        if(isset($categories) && count($categories) > 0)
                        {
                            $cslug = $categories[0]->category->slug;
                            $pslug = $post->slug;
                            $post->weburl = env('APP_URL').'/'.$cslug.'/'.$pslug.'/detail';
                        }
                        if(isset($post->postcomments) && count($post->postcomments)>0)
                        {
                            $postcomments = $post->postcomments->map(function ($news){
                                if(count($news->commentlikes) > 0)
                                {
                                    if($news->commentlikes[0]['likes'] == 1)
                                    {
                                        $news->self_like =1;
                                    }
                                    else{
                                        $news->self_like =0;
                                    }
                                }
                                else{
                                    $news->self_like =0;
                                }
                                
                            });
                            $post->postcomments = $postcomments;
                        }
                    }
                    
                    
                    
                   
                    return response()->json(['status'=>true, 'message' => 'Post', 'data'=>$post]);
                }
        
                else
                {
                    return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> []], 401);
                }
           
            
        }
        else
        {
            return response()->json(['status'=>false, 'message'=> 'Invalid Request', 'data'=> $validator->errors()], 401);
        }
        
    }

/**************************************Comment Reply system management************************ */
    public function addComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:posts,id',
            'comment' => 'required',
           ]);

        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
        }
        $user =  Auth::user();
        if(isset($user) && !empty($user) && $user->delete_status == '0')
        {
            $post = Post::where('id', $request->post_id)->where('user_delete_status','0')->first();
            if(!empty($post))
            {
                PostComment::create([
                    'user_id'=>$user->id,
                    'post_id'=>$request->post_id,
                    'comment'=>$request->comment
                ]);
                if($post->user_id != $user->id)
                {
                    $notifyArray=array(
                        'post_id' => $post->id,
                        'title' => $post->title,
                        'name' => Auth::user()->name,
                        'type' => 'comment',
                        'user_id' => $post->user_id,
                        'comment'=>$request->comment
                    );
                    $this->userNotification($notifyArray);
                }
                if(Auth::user()->id != $post->user_id)
                {
                    User::find($post->user_id)->notify(new NewsCommentNotification($post->user_id, $post->title));
                }
                
                return response()->json([
                    'status'=>true, 
                    'message' => 'Comment added successfully.', 
                    'data'=>[]
                ]);
            }
        
            else
            {
                return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> []], 401);
            }
        }
        else
        {
            return response()->json(['status'=>false, 'message' => 'Your account is inactive. Contact your administrator to activate it', 'data'=>''], 401);
        }
    }

    public function editComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comment_id' => 'required|exists:post_comments,id',
            'comment' => 'required',
           ]);

        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
        }
        $user =  Auth::user();
        $existComment = PostComment::where('id', $request->comment_id)->where('user_id', $user->id)->first();
        if(empty($existComment))
        {
            return response()->json(['status'=>false, 'message'=> 'Comment not exist', 'data'=> []], 401);
        }
        else{
            $existComment->update(['comment' => $request->comment, 'is_edit' => "Yes"]);
            return response()->json([
                'status'=>true, 
                'message' => 'Comment updated successfully.', 
                'data'=>[]
            ]);
        }  
    }

    public function deleteComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comment_id' => 'required',
           ]);

        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
        }
        $user =  Auth::user();
        $ids = explode(",", $request->comment_id);

       // $existComment = PostComment::where('id', $request->comment_id)->where('user_id', $user->id)->first();
        // if(empty($existComment))
        // {
        //     return response()->json(['status'=>false, 'message'=> 'You are not authorized to delete this comment', 'data'=> []], 401);
        // }
        // else{
            PostComment::whereIn('id', $ids)->where('user_id', $user->id)->delete();
            //$existComment->delete();
            return response()->json([
                'status'=>true, 
                'message' => 'Comment deleted successfully.', 
                'data'=>[]
            ]);
        //}  
    }

    public function addLikeOnComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comment_id' => 'required|exists:post_comments,id',
           ]);

        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
        }
        $user =  Auth::user();
        if(isset($user) && !empty($user) && $user->delete_status == '0')
        {
            $comment =  PostComment::find($request->comment_id);
            $post = Post::where('id', $comment->post_id)->where('user_delete_status','0')->first();
            if(!empty($post))
            {
                $total_likes = $comment->total_likes + 1;
                $like = CommentLike::where('comment_id', $request->comment_id)->where('user_id', $user->id)->first();
                if(empty($like))
                {
                    PostComment::where('id', $request->comment_id)->update(['total_likes' => $total_likes]);
                    CommentLike::create([
                        'user_id'=>$user->id,
                        'comment_id'=>$request->comment_id,
                        'likes'=> 1
                    ]);
                    
                    
                    
        
                    
                }
                else{
                    if($like->likes == 0)
                    {
                        PostComment::where('id', $request->comment_id)->update(['total_likes' => $total_likes]);
                        CommentLike::where('user_id', $user->id)->where('comment_id', $request->comment_id)->update(['likes' => 1]);
                    }
                }
            
                if($post->user_id != $user->id)
                {
                    $notifyArray=array(
                        'post_id' => $post->id,
                        'title' => $post->title,
                        'name' => Auth::user()->name,
                        'type' => 'like',
                        'user_id' => $post->user_id,
                        'comment'=>''
                    );
                    $this->userNotification($notifyArray);
                }
                
                if($comment->user_id != $user->id)
                {
                    $notifyArray1=array(
                        'post_id' => $post->id,
                        'comment' => $comment->comment,
                        'name' => Auth::user()->name,
                        'type' => 'like',
                        'user_id' => $comment->user_id,
                        'reply'=>''
                    );
                    $this->userCommentNotification($notifyArray1);
                }
                
                return response()->json([
                    'status'=>true, 
                    'message' => 'Like added successfully.', 
                    'data'=>[]
                ]);
            }
            else
            {
                return response()->json(['status'=>false, 'message'=> 'Post not found', 'data'=> []], 401);
            }
        }
        else
        {
            return response()->json(['status'=>false, 'message' => 'Your account is inactive. Contact your administrator to activate it', 'data'=>''], 401);
        }
    }

    public function removeLikeOnComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comment_id' => 'required|exists:post_comments,id',
           ]);

        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
        }
        $user =  Auth::user();
        if(isset($user) && !empty($user) && $user->delete_status == '0')
        {
            $like = CommentLike::where('comment_id', $request->comment_id)->where('user_id', $user->id)->where('likes', 1)->first();
            if(!empty($like))
            {
                $comment =  PostComment::find($request->comment_id);
                $total_likes = $comment->total_likes - 1;
    
                PostComment::where('id', $request->comment_id)->update(['total_likes' => $total_likes]);
                CommentLike::where('comment_id', $request->comment_id)->where('user_id', $user->id)->update(['likes' => 0]);
            }
    
            return response()->json([
                'status'=>true, 
                'message' => 'Like removed successfully.', 
                'data'=>[]
            ]);
        }
        else
        {
            return response()->json(['status'=>false, 'message' => 'Your account is inactive. Contact your administrator to activate it', 'data'=>''], 401);
        }
    }

    public function addReplyOnComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comment_id' => 'required|exists:post_comments,id',
            'reply' => 'required',
           ]);

        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
        }
        $user =  Auth::user();
        if(isset($user) && !empty($user) && $user->delete_status == '0')
        {
            $comment =  PostComment::find($request->comment_id);
            $post = Post::where('id', $comment->post_id)->where('user_delete_status','0')->first();
            if(!empty($post))
            {
                CommentReply::create([
                    'user_id'=>$user->id,
                    'comment_id'=>$request->comment_id,
                    'reply'=>$request->reply
                ]);
                
                
                if($post->user_id != $user->id)
                {
                    $notifyArray=array(
                        'post_id' => $post->id,
                        'title' => $post->title,
                        'name' => Auth::user()->name,
                        'type' => 'comment',
                        'user_id' => $post->user_id,
                        'comment'=>$request->reply
                    );
                    $this->userNotification($notifyArray);
                }
                
                if($comment->user_id != $user->id)
                {
                    $notifyArray1=array(
                        'post_id' => $post->id,
                        'comment' => $comment->comment,
                        'name' => Auth::user()->name,
                        'type' => 'comment',
                        'user_id' => $comment->user_id,
                        'reply'=>$request->reply
                    );
                    $this->userCommentNotification($notifyArray1);
                }
                
                
                return response()->json([
                    'status'=>true, 
                    'message' => 'Reply added successfully.', 
                    'data'=>[]
                ]);
            }
            else
            {
                return response()->json(['status'=>false, 'message'=> 'Post not found', 'data'=> []], 401);
            }
        }
        else
        {
            return response()->json(['status'=>false, 'message' => 'Your account is inactive. Contact your administrator to activate it', 'data'=>''], 401);
        }
    }

    public function deleteReply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reply_id' => 'required|exists:comment_replies,id',
           ]);

        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
        }
        $user =  Auth::user();
        if(isset($user) && !empty($user) && $user->delete_status == '0')
        {
            $existReply = CommentReply::where('id', $request->reply_id)->where('user_id', $user->id)->first();
            if(empty($existReply))
            {
                return response()->json(['status'=>false, 'message'=> 'You are not authorized to delete this reply', 'data'=> []], 401);
            }
            else{
                $existReply->delete();
                return response()->json([
                    'status'=>true, 
                    'message' => 'Reply deleted successfully.', 
                    'data'=>[]
                ]);
            } 
        }
        else
        {
            return response()->json(['status'=>false, 'message' => 'Your account is inactive. Contact your administrator to activate it', 'data'=>''], 401);
        }
    }
    public function addLikeOnReply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reply_id' => 'required|exists:comment_replies,id',
           ]);

        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
        }
        $user =  Auth::user();
        if(isset($user) && !empty($user) && $user->delete_status == '0')
        {
            $reply =  CommentReply::find($request->reply_id);
                $total_likes = $reply->total_likes + 1;
            $like = ReplyLike::where('reply_id', $request->reply_id)->where('user_id', $user->id)->first();
            if(empty($like))
            {
               
    
                CommentReply::where('id', $request->reply_id)->update(['total_likes' => $total_likes]);
                ReplyLike::create([
                    'user_id'=>$user->id,
                    'reply_id'=>$request->reply_id,
                    'likes'=> 1
                ]);
            }
            else{
                if($like->likes == 0)
                {
                    CommentReply::where('id', $request->reply_id)->update(['total_likes' => $total_likes]);
                    ReplyLike::where('user_id', $user->id)->where('reply_id', $request->reply_id)->update(['likes' => 1]);
                }
            }
    
            return response()->json([
                'status'=>true, 
                'message' => 'Like added successfully.', 
                'data'=>[]
            ]);
        }
        else
        {
            return response()->json(['status'=>false, 'message' => 'Your account is inactive. Contact your administrator to activate it', 'data'=>''], 401);
        }
    }

    public function removeLikeOnReply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reply_id' => 'required|exists:comment_replies,id',
           ]);

        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
        }
        $user =  Auth::user();
        if(isset($user) && !empty($user) && $user->delete_status == '0')
        {
            $like = ReplyLike::where('reply_id', $request->reply_id)->where('user_id', $user->id)->where('likes', 1)->first();
            if(!empty($like))
            {
                $reply =  CommentReply::find($request->reply_id);
                $total_likes = $reply->total_likes - 1;
    
                CommentReply::where('id', $request->reply_id)->update(['total_likes' => $total_likes]);
                ReplyLike::where('reply_id', $request->reply_id)->where('user_id', $user->id)->update(['likes' => 0]);
            }
    
            return response()->json([
                'status'=>true, 
                'message' => 'Like removed successfully.', 
                'data'=>[]
            ]);
        }
        else
        {
            return response()->json(['status'=>false, 'message' => 'Your account is inactive. Contact your administrator to activate it', 'data'=>''], 401);
        }
    }

    public function commentDetails(Request $request)
    {
        $requestData = $request->query();
        $request->replace($requestData);
        $validator = Validator::make($requestData, [
            'id' => 'required|exists:post_comments',
        ]);
        if ($validator->passes()) 
        {
            $user =  Auth::user();
            if(isset($user) && !empty($user) && $user->delete_status == '0')
            {
                $url = env('APP_URL').'/storage/app/public/';
    
                $comment = PostComment::with(['commentreplies', 'commentreplies.user'=>function ($q)use($url){
                                    $q->select("users.id", "users.name", "users.username", DB::raw("CONCAT('".$url."', users.image) as image"));
                                }, 'user' => function($query)use($url){
                                    $query->select("users.id", "users.name", "users.username", DB::raw("CONCAT('".$url."', users.image) as image"));
                                }])
                                ->with(['commentlikes' =>function($qu)use($user){
                                    $qu->where('user_id', $user->id);
                                }])
                                ->where('id', $request->query('id'))->withCount('commentreplies as total_reply')->first();
    
                if(count($comment->commentlikes) > 0)
                {
                    if($comment->commentlikes[0]['likes'] == 1)
                    {
                        $comment->self_like =1;
                    }
                    else{
                        $comment->self_like =0;
                    }
                }
                else{
                    $comment->self_like =0;
                }
                if(isset($comment->commentreplies) && count($comment->commentreplies)>0)
                {
                    $commentreply = $comment->commentreplies->map(function ($reply)use($user){
                        $userId = $user->id;
                        $reply->self_reply_like = $this->userReplyLike($userId, $reply->id);
                        return $reply;
                    });
                $comment->commentreplies = $commentreply;
                }
    
                
                
                return response()->json(['status'=>true, 'message' => 'Comment', 'data'=>$comment]);
            }
            else
            {
                return response()->json(['status'=>false, 'message' => 'Your account is inactive. Contact your administrator to activate it', 'data'=>''], 401);
            }
            
        }
        else
        {
            return response()->json(['status'=>false, 'message'=> 'Invalid Request', 'data'=> $validator->errors()], 401);
        }
        
    }

    public function getPostByRandomTag(Request $request)
    {
        
        $url = env('APP_URL').'/storage/app/public/';
        $tag = Tag::inRandomOrder()->first();
        $tagName = strtoupper(trim($tag->name));
        $tagId = $tag->id;
        while($this->checkTagPost($tagId) == 0)
        {
            $tag = Tag::inRandomOrder()->first();
            $tagName = strtoupper(trim($tag->name));
            $tagId = $tag->id;
        }
        
        $posts =DB::table('posttags')
            ->join('posts','posttags.post_id', '=', 'posts.id')
            ->select("posts.*", DB::raw("CONCAT('".$url."', posts.image) as image"), 'posttags.tag_id')
            ->where('posttags.tag_id', $tagId)
            ->where('posts.status','published')
            ->where('posts.user_delete_status','0')
            ->orderBy('id', 'desc')
            ->take(10)->get();

        if(isset($posts) && count($posts) == 0)
        {

        }
        $user =  Auth::user();
        
            if(isset($posts) && count($posts) > 0)
            {
                $posts = $posts->map(function ($row, $key)use($user) {
                    $comment_count = PostComment::where('post_id', $row->id)->count();
                    if($user)
                    {
                        $existData = PostBookmarkLike::where('user_id', $user->id)->where('post_id', $row->id)->first();
                    }
                    
                    if(isset($existData) &&!empty($existData))
                    {
                        $like = $existData->likes;
                        $bookmark = $existData->bookmark;
                        
                    }
                    else
                    {
                        $like = 0;
                        $bookmark = 0;
                    }
                    if($row->image == '')
                    {
                        $row->image = env('APP_URL').'/public/front/images/logo.png';
                    }
                    $row->user_like = $like;
                    $row->bookmark = $bookmark;
                    $row->total_post_comments = $comment_count;
                    
                    return $row;
                });
            
        }
        
            return response()->json(['status'=>true, 'message' =>$tagName, 'data'=>$posts]);
        
    }


    public function getDailyPost(Request $request)
    {
        if($request->has('per_page'))  
        {
            $per_page=$request->per_page;
        }
        else
        {
            $per_page=5;
        }
        $url = env('APP_URL').'/storage/app/public/';
        $posts = Post::select("posts.*", DB::raw("CONCAT('".$url."', posts.image) as image"))
                    ->where('status','published')
                    ->where('user_delete_status','0')
                    ->whereDate('created_at', date('Y-m-d'))
                    ->orderBy('id', 'desc')
                    ->withCount('postcomments as total_post_comments')
                    ->paginate($per_page);

        $user =  Auth::user();
        
            if(isset($posts) && count($posts) > 0)
            {
                $posts->getCollection()->transform(function ($row, $key)use($user) {

                    $category = Postcategory::with(['category' =>function ($q){
                        $q->select("categories.id", "categories.name", "categories.slug");
                    }])->where('post_id', $row->id)->select('id', 'post_id', 'category_id')->first();
    
                    if(isset($category) && !empty($category) > 0)
                    {
                        $cslug = $category->category->slug;
                        $pslug = $row->slug;
                        $row->weburl = env('APP_URL').'/'.$cslug.'/'.$pslug.'/detail';
                    }

                    if($user)
                    {
                        $existData = PostBookmarkLike::where('user_id', $user->id)->where('post_id', $row->id)->first();
                    }
                    
                    if(isset($existData) &&!empty($existData))
                    {
                    
                        $like = $existData->likes;
                        $bookmark = $existData->bookmark;
                        
                    }
                    else
                    {
                        $like = 0;
                        $bookmark = 0;
                    }
                    if($row->image == '')
                    {
                        $row->image = env('APP_URL').'/public/front/images/logo.png';
                    }
                    $row->user_like = $like;
                    $row->bookmark = $bookmark;
                    
                    
                    return $row;
                });
            
            
        }
        
        
        return response()->json(['status'=>true, 'message' => 'Posts', 'data'=>$posts]);
    }


    public function getRecentPost()
    {
        $user =  Auth::user();
        $date = Carbon::now()->subDays(5);
        $url = env('APP_URL').'/storage/app/public/';
        $posts = Post::select("posts.*", DB::raw("CONCAT('".$url."', posts.image) as image"))
                    ->where('status','published')
                    ->where('user_delete_status','0')
                    ->where('created_at', '>=', $date)
                    ->orderBy('id', 'desc')
                    ->withCount('postcomments as total_post_comments')
                    ->get();

        
            if(isset($posts) && count($posts) > 0)
            {
                $posts->map(function ($row, $key)use($user) {
                    if($user)
                    {
                        $existData = PostBookmarkLike::where('user_id', $user->id)->where('post_id', $row->id)->first();
                    }
                    
                    if(isset($existData) &&!empty($existData))
                    {
                    
                        $like = $existData->likes;
                        $bookmark = $existData->bookmark;
                        
                    }
                    else
                    {
                        $like = 0;
                        $bookmark = 0;
                    }
                    $row->user_like = $like;
                    $row->bookmark = $bookmark;
                    
                    
                    return $row;
                });
            }
            
        
        
        
        return response()->json(['status'=>true, 'message' => 'Posts', 'data'=>$posts]);
    }


    public function getRelatedPost(Request $request)
    {
        $requestData = $request->query();
        $request->replace($requestData);
        $validator = Validator::make($requestData, [
            'category_id' => 'required|exists:categories,id',
            'post_id' => 'required|exists:posts,id',
        ]);
        $user =  Auth::user();
        if ($validator->passes()) 
        {
            $url = env('APP_URL').'/storage/app/public/';
            $categoryId = $request->query('category_id');
            $postId = $request->query('post_id');
            
            $posts =DB::table('postcategories')
                ->join('posts','postcategories.post_id', '=', 'posts.id')
                ->select("posts.*", DB::raw("CONCAT('".$url."', posts.image) as image"), 'postcategories.category_id')
                ->where('postcategories.category_id', $categoryId)
                ->where('posts.id', '!=' , $postId)
                ->where('posts.status','published')
                ->where('posts.user_delete_status','0')
                ->orderBy('id', 'desc')
                ->take(4)->get();

            
                $posts->map(function ($row, $key)use($user) {
                    $comment_count = PostComment::where('post_id', $row->id)->count();
                    if($user)
                    {
                        $existData = PostBookmarkLike::where('user_id', $user->id)->where('post_id', $row->id)->first();
                    }
                    
                    if(isset($existData) &&!empty($existData))
                    
                    {
                        $like = $existData->likes;
                        $bookmark = $existData->bookmark;
                    }
                    else
                    {
                        $like = 0;
                        $bookmark = 0;
                    }
                    if($row->image == '')
                    {
                        $row->image = env('APP_URL').'/public/front/images/logo.png';
                    }
                    $row->user_like = $like;
                    $row->bookmark = $bookmark;
                    $row->total_post_comments = $comment_count;
                    
                    return $row;
                });
            
           return response()->json(['status'=>true, 'message' => 'Posts', 'data'=>$posts]);
        }
        else
        {
            return response()->json(['status'=>false, 'message'=> 'Invalid Request', 'data'=> $validator->errors()], 401);
        }
     }


    public function reasons()
    {
        $reasons = Reason::select('id', 'reason', 'status')
                    ->where('status', 'Active')
                        ->orderBy('id', 'desc')
                        ->get();
        return response()->json(['status'=>true, 'message' => 'Reasons', 'data'=>$reasons]);
        
    }
    
    public function userReplyLike($userId, $replyId)
    {
        $exist = ReplyLike::where('user_id', $userId)->where('reply_id', $replyId)->first();
        if(!empty($exist) && $exist->likes == 1)
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public function checkTagPost($tagId)
    {
        $posts =DB::table('posttags')
        ->join('posts','posttags.post_id', '=', 'posts.id')
        ->select("posts.*", 'posttags.tag_id')
        ->where('posttags.tag_id', $tagId)
        ->where('posts.status','published')
        ->where('posts.user_delete_status','0')
        ->orderBy('id', 'desc')
        ->count();
        if($posts == 0)
        {
            return 0;
        }
        else
        {
            return 1;
        }
    }
    
    
    public function getMarqueeData()
    {
        $breaking_news_ids = Postcategory::latest('id')->where('category_id',14)->limit(10)->pluck('post_id')->toArray();
        $breakingnews = Post::latest('id')->select(['id','slug','title'])->whereIn('id',$breaking_news_ids)->where('status','published')->where('user_delete_status','0')->take(10)->get();
        
        return response()->json(['status'=>true, 'message' => 'Marquee Data', 'data'=>$breakingnews]);
    }
    
    public function getAdsData()
    {
        $data = array();
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
        
        
        
        $data['uppersidebar300x250'] = $uppersidebar300x250;
        $data['middlesidebar300x250'] = $middlesidebar300x250;
        $data['lowersidebar300x250'] = $lowersidebar300x250;
        $data['uppersidebar300x600'] = $uppersidebar300x600;
        $data['middlesidebar300x600'] = $middlesidebar300x600;
        $data['lowersidebar300x600'] = $lowersidebar300x600;
        $data['upperbanner728x90'] = $upperbanner728x90;
        $data['middlebanner728x90'] = $middlebanner728x90;
        $data['lowerbanner728x90'] = $lowerbanner728x90;
        $data['lowestbanner728x90'] = $lowestbanner728x90;
        return response()->json(['status'=>true, 'message' => 'Banner and Ads Data', 'data'=>$data]);
    }

}