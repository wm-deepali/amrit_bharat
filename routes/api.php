<?php

use App\Http\Controllers\Api\HashtagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\NewsController;
use App\Notifications\PushNotification;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\VideoCommentController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('clear-cache', function () {
    Artisan::call('config:cache');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    return '<h1>Clear Config cleared</h1>';
});

Route::get('send-notification', [AuthController::class, 'sendPushNotification']);
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('mobile-login', [AuthController::class, 'mobLogin']);
Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('register', [AuthController::class, 'register']);
Route::post('username-exist', [AuthController::class, 'usernameExist']);
Route::post('password/email', [AuthController::class, 'resetPasswordByEmail']);
Route::post('verify/email', [AuthController::class, 'verifyEmailOtp']);
Route::post('password/mobile', [AuthController::class, 'resetPasswordByMobile']);
Route::post('verify/mobile', [AuthController::class, 'verifyMobileOtp']);
Route::post('password/reset', [AuthController::class, 'passwordReset']);
Route::post('google-login', [AuthController::class, 'glogin'])->name('glogin');


Route::get('get-marquee-data', [PostController::class, 'getMarqueeData']);
Route::get('category-with-subcategories', [PostController::class, 'getCategoryWithSubcategories']);
Route::post('add-view', [PostController::class, 'addView']);
Route::get("get/posttag", [ApiController::class, 'posttag']);
Route::get("get/intro", [ApiController::class, 'intro']);
Route::get("get/country-list", [ApiController::class, 'country']);
Route::get("get/state-list/{id}", [ApiController::class, 'state']);
Route::get("get/city-list/{id}", [ApiController::class, 'city']);

// guest routes for home page when user not logged in
Route::get('get-categories', [PostController::class, 'getCategory']);
Route::get('guest/daily-picks', [PostController::class, 'getDailyPostGuest']);
Route::get('guest/recent-news', [PostController::class, 'getRecentPostGuest']);
Route::get('guest/related-news', [PostController::class, 'getRelatedPostGuest']);

Route::get('events', [EventController::class, 'index']);
Route::get('event-categories', [EventController::class, 'getCategories']);
Route::get('videos/all', [VideoController::class, 'allVideos']); // all published videos
Route::get('banners/all', [BannerController::class, 'allBanners']); // all published videos


Route::middleware('authSunctum')->group(function () {
    Route::get('get-daily-picks', [PostController::class, 'getDailyPost'])->middleware();
    Route::get('recent-news', [PostController::class, 'getRecentPost'])->middleware();
    Route::get('related-news', [PostController::class, 'getRelatedPost'])->middleware();
    Route::get('random-posts', [PostController::class, 'getPostByRandomTag'])->middleware();

    Route::get('get-posts-by-tag', [PostController::class, 'getPostByTag'])->middleware();
    Route::get('get-posts-by-category', [PostController::class, 'getPostByCategory'])->middleware();
    Route::get('get-posts-by-subcategory', [PostController::class, 'getPostBySubCategory'])->middleware();
    Route::get('search-posts', [PostController::class, 'search'])->middleware();
    Route::get('post-details', [PostController::class, 'postDetails'])->middleware();
});
Route::group(['middleware' => ['auth:sanctum']], function () {


    Route::get('get-user', [AuthController::class, 'getUser']);
    Route::post('profile-edit', [AuthController::class, 'profileUpdate']);
    Route::post('change-password', [AuthController::class, 'changePassword']);
    Route::post('help-request', [AuthController::class, 'helpRequest']);
    Route::post('close-account-request', [AuthController::class, 'closeAccountRequest']);
    Route::post('delete-account-request', [AuthController::class, 'deleteAccountRequest']);
    Route::post('update-fcm-token', [AuthController::class, 'updateFcmToken']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('get-reasons', [PostController::class, 'reasons']);
    Route::get('get-tags', [PostController::class, 'getTag']);
    Route::get('get-all-posts', [PostController::class, 'getPost']);
    Route::get('get-ads-data', [PostController::class, 'getAdsData']);

    Route::post('add-like', [PostController::class, 'addLike']);
    Route::post('remove-like', [PostController::class, 'removeLike']);

    Route::post('add-bookmark', [PostController::class, 'addBookmark']);
    Route::post('remove-bookmark', [PostController::class, 'removeBookmark']);
    Route::get('list-bookmark', [PostController::class, 'listUserBookmark']);

    Route::post('add-comment', [PostController::class, 'addComment']);
    Route::post('edit-comment', [PostController::class, 'editComment']);
    Route::post('delete-comment', [PostController::class, 'deleteComment']);
    Route::post('add-like-on-comment', [PostController::class, 'addLikeOnComment']);
    Route::post('remove-like-on-comment', [PostController::class, 'removeLikeOnComment']);
    Route::post('add-reply-on-comment', [PostController::class, 'addReplyOnComment']);
    Route::post('add-like-on-reply', [PostController::class, 'addLikeOnReply']);
    Route::post('remove-like-on-reply', [PostController::class, 'removeLikeOnReply']);
    Route::get('comment-details', [PostController::class, 'commentDetails']);
    Route::post('delete-reply', [PostController::class, 'deleteReply']);

    Route::post('post-news', [NewsController::class, 'postNews']);
    Route::post('update-news', [NewsController::class, 'updateNews']);
    Route::get('my-news-list', [NewsController::class, 'getUserPost']);
    Route::get('user-news-profile', [NewsController::class, 'userNewsProfile']);
    Route::get('get-all-comments', [NewsController::class, 'getallcomments']);
    Route::post('delete-news', [NewsController::class, 'deleteNews']);

    Route::get('user-notifications', [NewsController::class, 'getNotifications']);
    Route::get('count-notifications', [NewsController::class, 'getunReadNotificationsCount']);
    Route::get('delete-all-notifications', [NewsController::class, 'deleteAllNotifications']);
    Route::post('mark-read', [NewsController::class, 'markRead']);
    Route::get('mark-all-read', [NewsController::class, 'markAllRead']);

    Route::prefix('events')->group(function () {
        Route::get('/my-events', [EventController::class, 'myEvents']); // Auth user's events
        Route::get('/{id}', [EventController::class, 'show']);
        Route::post('/', [EventController::class, 'store']);
        Route::post('/update/{id}', [EventController::class, 'update']);
        Route::delete('/{id}', [EventController::class, 'destroy']);

        // Like routes for events
        Route::post('/add-like', [EventController::class, 'addLike']);
        Route::post('/remove-like', [EventController::class, 'removeLike']);
    });


    Route::prefix('videos')->group(function () {
        Route::get('/my', [VideoController::class, 'myVideos']);   // videos of auth user
        Route::get('/{id}', [VideoController::class, 'show']);
        Route::post('/', [VideoController::class, 'store']);
        Route::post('/update/{id}', [VideoController::class, 'update']);
        Route::delete('/{id}', [VideoController::class, 'destroy']);

        // Video like routes
        Route::post('/add-like', [VideoController::class, 'addLike']);
        Route::post('/remove-like', [VideoController::class, 'removeLike']);

        // Comment routes
        Route::post('/comment/add', [VideoCommentController::class, 'addComment']);
        Route::post('/comment/edit', [VideoCommentController::class, 'editComment']);
        Route::delete('/comment/delete', [VideoCommentController::class, 'deleteComment']);

        // Like / dislike routes on comments
        Route::post('/comment/add-like', [VideoCommentController::class, 'addLike']);
        Route::post('/comment/remove-like', [VideoCommentController::class, 'removeLike']);
    });


    Route::prefix('hashtags')->group(function () {
        Route::get('/', [HashtagController::class, 'index']); // list/search hashtags
    });

});



