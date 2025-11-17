<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Posttag;
use App\Post;
use App\Tag;
use App\Category;
use App\Postcategory;
use App\Postsubcategory;
use App\Subcategory;
use App\PostBookmarkLike;
use App\PostComment;
use App\CommentReply;
use App\CommentLike;
use App\ReplyLike;
use Illuminate\Support\Str;
use Mail;
use DB;
use Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\DatabaseNotification;


class NewsController extends Controller
{
    public function postNews(Request $request)
    {
        $user =  Auth::user();
        if(isset($user) && !empty($user) && $user->delete_status == '0')
        {
            
            $userid = $user->id;
            $requestData = $request->all();
            $requestData['slug'] = Str::slug($request->slug, '-');
            $request->replace($requestData);
            $validator = Validator::make($requestData, [
                'title'=>'required',
                'slug' => 'required|max:255|unique:posts',
                'content' => 'required',
                'video' => 'nullable|max:255',
                // 'metatitle' => 'required|max:70',
                // 'metadescription' => 'required|max:160',
                // 'metakeyword' => 'required|max:255',
                'category'=>'required',
                'subcategory'=>'nullable',
                'image'=>'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                //'imagetag'=>'nullable',
                'recommended_tag' => 'required_without:new_tag', 
                'new_tag' => 'required_without:recommended_tag'
            ], 
            [  
                'recommended_tag.required_without' => 'Please enter recommended tags or add new tags.', 
                'new_tag.required_without' => 'Please add new tags or recommended tags.', 
            ] 
        );
            if ($validator->fails()) {
                return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
            }
            try {
                $data=array(
                    'user_id'=>$userid,
                    'title'=>$request->title,
                    'slug'=>$request->slug,
                    'content'=>$request->content,
                    'video'=>$request->video,
                    // 'metatitle'=>$request->metatitle,
                    // 'metadescription'=>$request->metadescription,
                    // 'metakeyword'=>$request->metakeyword,
                );
                if($request->hasFile('image')){
                    $data['image']=$request->image->store('posts');
                   // $data['imagetag']=$request->imagetag;
                    $imagehash=$request->image->hashName();
                    $path = storage_path('app/public/posts/'.$imagehash);
                    Image::make($request->image)->resize(600,600,function($constraint){
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->save($path);
                }
                else
                {
                    
                    $url = env('APP_URL').'public/front/images/logo.png';
                    $contents = $this->curl_get_file_contents($url);
                    $name = time().'logo.png';
                    
                    Storage::put('posts/'.$name, $contents);
                     $data['image'] = 'posts/'.$name;
                }
                $data['status']='pending';
                
                $post=Post::create($data);
                $postnumber='PN'.str_pad($post->id, 5, "0", STR_PAD_LEFT);
                Post::where('id',$post->id)->update([
                    'postnumber' => $postnumber,
                ]);
                foreach(explode(',',$request->category) as $category_id){
                    Postcategory::create([
                        'post_id'=>$post->id,
                        'category_id'=>$category_id,
                    ]);
                }
                if(isset($request->subcategory)){
                    foreach(explode(',',$request->subcategory) as $subcategory_id){
                        Postsubcategory::create([
                            'post_id'=>$post->id,
                            'subcategory_id'=>$subcategory_id,
                        ]);
                    }
                }
                foreach(explode(',',$request->recommended_tag) as $tag_id){
                    Posttag::create([
                        'post_id'=>$post->id,
                        'tag_id'=>$tag_id,
                    ]);
                }
    
                if(isset($request->new_tag) && $request->new_tag !='')
                {
                    $tagsVal = ltrim($request->new_tag, ',');
    
                    foreach(explode(',',$tagsVal) as $newtag){
                        $tagSlug = Str::slug($newtag, '-');
                        $existtag = Tag::where('slug', $tagSlug)->first();
                        if(empty($existtag))
                        {
                            $tagSave = Tag::create([
                                'name'=>$newtag,
                                'slug'=>$tagSlug,
                                'metatitle'=>$newtag,
                                'metadescription'=>$newtag,
                                'metakeyword'=>$newtag
                            ]);
    
                            Posttag::create([
                                'post_id'=>$post->id,
                                'tag_id'=>$tagSave->id,
                            ]);
                        }
                        
    
                    }
                }
                
                DB::commit();
                return response()->json(['status'=>true, 'message' => 'Post news successfully', 'data'=>$post]);
    
            } catch(\Exception $ex) {
                DB::rollback();
                return response()->json(['status'=>false, 'message'=> 'Error', 'data'=> $ex->getMessage()], 400);
            }
        }
        else
        {
            return response()->json(['status'=>false, 'message' => 'Your account is inactive. Contact your administrator to activate it', 'data'=>''], 401);
        }
    }

    public function updateNews(Request $request)
    {
        $user =  Auth::user();
        if(isset($user) && !empty($user) && $user->delete_status == '0')
        {
            $userid = $user->id;
            $checkUserPost = Post::where('user_id', $userid)->where('id', $request->id)->first();
            if(empty($checkUserPost))
            {
                return response()->json(['status'=>false, 'message'=> 'You are not authorized to edit this post', 'data'=> []], 401);
            }
            $requestData = $request->all();
            $requestData['slug'] = Str::slug($request->slug, '-');
            $request->replace($requestData);
            $validator = Validator::make($requestData, [
                'id'=>'required|exists:posts',
                'title'=>'required',
                "slug"=>["required",Rule::unique('posts')->ignore($request->id)],
                'content' => 'required',
                'video' => 'nullable|max:255',
                // 'metatitle' => 'required|max:70',
                // 'metadescription' => 'required|max:160',
                // 'metakeyword' => 'required|max:255',
                'category'=>'required',
                'subcategory'=>'nullable',
                'image'=>'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                //'imagetag'=>'nullable',
                'recommended_tag' => 'required_without:new_tag', 
                'new_tag' => 'required_without:recommended_tag'
            ], 
            [  
                'recommended_tag.required_without' => 'Please enter recommended tags or add new tags.', 
                'new_tag.required_without' => 'Please add new tags or recommended tags.', 
            ]);
            if ($validator->fails()) {
                return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
            }
            try {
                $post=Post::findOrFail($request->id);
                $data=array(
                    'title'=>$request->title,
                    'slug'=>$request->slug,
                    'content'=>$request->content,
                    'video'=>$request->video,
                    // 'metatitle'=>$request->metatitle,
                    // 'metadescription'=>$request->metadescription,
                    // 'metakeyword'=>$request->metakeyword,
                );
                if($request->hasFile('image')){
                    $data['image']=$request->image->store('posts');
                    $imagehash=$request->image->hashName();
                    $path = storage_path('app/public/posts/'.$imagehash);
                    Image::make($request->image)->resize(600,600,function($constraint){
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->save($path);
                }
                Post::where('id',$request->id)->update($data);
                Postcategory::where('post_id',$request->id)->delete();
                foreach(explode(',',$request->category) as $category_id){
                    Postcategory::create([
                        'post_id'=>$request->id,
                        'category_id'=>$category_id,
                    ]);
                }
                Postsubcategory::where('post_id',$request->id)->delete();
                if(isset($request->subcategory)){
                    foreach(explode(',',$request->subcategory) as $subcategory_id){
                        Postsubcategory::create([
                            'post_id'=>$request->id,
                            'subcategory_id'=>$subcategory_id,
                        ]);
                    }
                }
                Posttag::where('post_id',$request->id)->delete();
                foreach(explode(',',$request->recommended_tag) as $tag_id){
                    Posttag::create([
                        'post_id'=>$request->id,
                        'tag_id'=>$tag_id,
                    ]);
                }
                if(isset($request->new_tag) && $request->new_tag !='')
                {
                    $tagsVal = ltrim($request->new_tag, ',');
    
                    foreach(explode(',',$tagsVal) as $newtag){
                        $tagSlug = Str::slug($newtag, '-');
                        $existtag = Tag::where('slug', $tagSlug)->first();
                        if(empty($existtag))
                        {
                            $tagSave = Tag::create([
                                'name'=>$newtag,
                                'slug'=>$tagSlug,
                                'metatitle'=>$newtag,
                                'metadescription'=>$newtag,
                                'metakeyword'=>$newtag
                            ]);
    
                            Posttag::create([
                                'post_id'=>$post->id,
                                'tag_id'=>$tagSave->id,
                            ]);
                        }
                        
    
                    }
                }
                DB::commit();
                $post=Post::findOrFail($request->id);
                return response()->json(['status'=>true, 'message' => 'News updated successfully', 'data'=>$post]);
    
            } catch(\Exception $ex) {
                DB::rollback();
                return response()->json(['status'=>false, 'message'=> 'Error', 'data'=> $ex->getMessage()], 400);
            }
        }
        else
        {
            return response()->json(['status'=>false, 'message' => 'Your account is inactive. Contact your administrator to activate it', 'data'=>''], 401);
        }
    }

    public function getUserPost()
    {
        $user =  Auth::user();
        if(isset($user) && !empty($user) && $user->delete_status == '0')
        {
            $url = env('APP_URL').'/storage/app/public/';
            $posts = Post::select("posts.*", DB::raw("CONCAT('".$url."', posts.image) as image"))
                        ->where('user_id', $user->id)
                        ->orderBy('id', 'desc')
                        ->withCount('postcomments as total_post_comments')
                        ->get();
    
            
            if($user)
            {
                if(isset($posts) && count($posts) > 0)
                {
                    $posts = $posts->map(function ($row, $key)use($user) {
    
                        $categories = Postcategory::with(['category' =>function ($q){
                            $q->select("categories.id", "categories.name", "categories.slug");
                        }])->where('post_id', $row->id)->select('id', 'post_id', 'category_id')->get();
        
                        $row->categories = $categories;
                        
                        $tags = Posttag::with(['tag' =>function ($q){
                            $q->select("tags.id", "tags.name", "tags.slug");
                        }])->where('post_id', $row->id)->select('id', 'post_id', 'tag_id')->get();
        
                        $row->tags = $tags;
    
                        $existData = PostBookmarkLike::where('user_id', $user->id)->where('post_id', $row->id)->first();
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
                        if($row->image == '')
                        {
                            $row->image = env('APP_URL').'/public/front/images/logo.png';
                        }
                        $row->user_like = $like;
                        $row->bookmark = $bookmark;
                        
                        return $row;
                    });
                }
                
            }
            
            
            return response()->json(['status'=>true, 'message' => 'Posts', 'data'=>$posts]);
        }
        else
        {
            return response()->json(['status'=>false, 'message' => 'Your account is inactive. Contact your administrator to activate it', 'data'=>''], 401);
        }
    }


    public function deleteNews(Request $request)
    {
        $user =  Auth::user();
        if(isset($user) && !empty($user) && $user->delete_status == '0')
        {
            $userid = $user->id;
            $checkUserPost = Post::where('user_id', $userid)->where('id', $request->id)->first();
            if(empty($checkUserPost))
            {
                return response()->json(['status'=>false, 'message'=> 'You are not authorized to delete this post', 'data'=> []], 401);
            }
            $requestData = $request->all();
            $request->replace($requestData);
            $validator = Validator::make($requestData, [
                'id'=>'required|exists:posts',
        
            ]);
            if ($validator->fails()) {
                return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
            }
            try {
                $post=Post::findOrFail($request->id);
                $image = $post->image_name;
                if($post->delete())
                {
                    if(isset($image) && $image !='' && file_exists(storage_path('app/public/posts/'.$image))){
                        unlink(storage_path('app/public/posts/'.$image));
                    }
    
                    Postcategory::where('post_id',$request->id)->delete();
                    Postsubcategory::where('post_id',$request->id)->delete();
                    Posttag::where('post_id',$request->id)->delete();
                   
                    PostBookmarkLike::where('post_id',$request->id)->delete();
                    PostComment::where('post_id',$request->id)->delete();
                }
                return response()->json(['status'=>true, 'message' => 'News deleted successfully', 'data'=>[]]);
    
            } catch(\Exception $ex) {
                 return response()->json(['status'=>false, 'message'=> 'Error', 'data'=> $ex->getMessage()], 400);
            }
        }
        else
        {
            return response()->json(['status'=>false, 'message' => 'Your account is inactive. Contact your administrator to activate it', 'data'=>''], 401);
        }
    }

    public function userNewsProfile()
    {
        $user =  Auth::user();
        if(isset($user) && !empty($user) && $user->delete_status == '0')
        {
            $url = env('APP_URL').'/storage/app/public/';
            $userProfile = User::where('id', $user->id)
                        ->select("users.id", "users.name", DB::raw("CONCAT('".$url."', users.image) as profile_pic"))
                        ->withCount('posts as news')
                        ->withCount('userComment as comments')
                        ->withCount('userLike as likes')
                        ->first();
    
            
            return response()->json(['status'=>true, 'message' => 'User News Profile', 'data'=>$userProfile]);
        }
        else
        {
            return response()->json(['status'=>false, 'message' => 'Your account is inactive. Contact your administrator to activate it', 'data'=>''], 401);
        }
    }


    public function markRead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'notification_id' => 'required|exists:notifications,id',
           ]);

        if ($validator->fails()) {
            return response()->json(['status'=>false, 'message'=> 'Invalid Post Request', 'data'=> $validator->errors()], 401);
        }
        $notificationId = $request->notification_id;
        $user =  Auth::user();
        if(isset($user) && !empty($user) && $user->delete_status == '0')
        {
            $userUnreadNotification = Auth::user()
                                ->unreadNotifications
                                ->where('id', $notificationId)
                                ->first();
    
            if($userUnreadNotification) {
                $userUnreadNotification->markAsRead();
                
            }
            return response()->json(['status'=>true, 'message' => 'Notification mark as read', 'data'=>[]]);
        }
        else
        {
            return response()->json(['status'=>false, 'message' => 'Your account is inactive. Contact your administrator to activate it', 'data'=>''], 401);
        }
        
    }

    public function markAllRead(Request $request)
    {
        $user =  Auth::user();
        if(isset($user) && !empty($user) && $user->delete_status == '0')
        {
            Auth::user()->unreadnotifications->map(function($n) {
                $n->markAsRead();
            });
    
            return response()->json(['status'=>true, 'message' => 'Notifications mark as read', 'data'=>[]]);
        }
        else
        {
            return response()->json(['status'=>false, 'message' => 'Your account is inactive. Contact your administrator to activate it', 'data'=>''], 401);
        }
        
    }

    public function getNotifications()
    {
        $user =  Auth::user();
        if(isset($user) && !empty($user) && $user->delete_status == '0')
        {
            $notifications = $user->notifications()
                                   ->orderBy('created_at', 'desc')
                                   ->select('id', 'msg_text', 'profile_pic', 'read_at', 'created_at')
                                   ->get();
           
            $unread = Auth::user()->unreadnotifications->count();
    
            return response()->json(['status'=>true, 'message' => 'Notifications List', 'Unread Notifications' => $unread, 'data'=> $notifications]);
        }
        else
        {
            return response()->json(['status'=>false, 'message' => 'Your account is inactive. Contact your administrator to activate it', 'data'=>''], 401);
        }
    }
    
    public function getunReadNotificationsCount()
    {
        $user =  Auth::user();
        if(isset($user) && !empty($user) && $user->delete_status == '0')
        {
            
            $unread = Auth::user()->unreadnotifications->count();
    
            return response()->json(['status'=>true, 'message' => 'Notifications Count', 'data'=> $unread]);
        }
        else
        {
            return response()->json(['status'=>false, 'message' => 'Your account is inactive. Contact your administrator to activate it', 'data'=>''], 401);
        }
    }

    public function deleteAllNotifications()
    {
        $user =  Auth::user();
        if(isset($user) && !empty($user) && $user->delete_status == '0')
        {
            $notifications = $user->notifications()
                                   ->delete();
           
            return response()->json(['status'=>true, 'message' => 'Notifications deleted successfully.', 'data'=> []]);
        }
        else
        {
            return response()->json(['status'=>false, 'message' => 'Your account is inactive. Contact your administrator to activate it', 'data'=>''], 401);
        }
    }


    public function getallcomments(Request $request)
    {
        $requestData = $request->query();
        $request->replace($requestData);
        $user =  Auth::user();
        if(isset($user) && !empty($user) && $user->delete_status == '0')
        {
            $url = env('APP_URL').'/storage/app/public/';
            $filter=Null;
            $keyword=Null;
            if($request->has('keyword')){
                $keyword=$request->query('keyword');
    
                $comments = PostComment::with(['post' => function($query)use($url){
                    $query->select("posts.id", "posts.title");
                }])->has('post')
                ->where('user_id', $user->id)
                ->where('comment', 'like', '%'.$keyword.'%')->get();
            } 
            elseif($request->has('filter')){
                $filter=$request->query('filter');
            }
            else{
                $comments = PostComment::with(['post' => function($query)use($url){
                    $query->select("posts.id", "posts.title");
                }])->has('post')
                ->where('user_id', $user->id)
                ->get();
            }
                return response()->json(['status'=>true, 'message' => 'Comments', 'data'=>$comments]);
        }
        else
        {
            return response()->json(['status'=>false, 'message' => 'Your account is inactive. Contact your administrator to activate it', 'data'=>''], 401);
        }
        
    }
    function curl_get_file_contents($URL)
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $URL);
        $contents = curl_exec($c);
        curl_close($c);
    
        if ($contents) return $contents;
        else return FALSE;
    }
}