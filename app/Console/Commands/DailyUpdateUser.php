<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\CommentLike;
use App\CommentReply;
use App\Post;
use App\PostBookmarkLike;
use App\PostComment;
use App\ReplyLike;
use Carbon\Carbon;
use Log;

class DailyUpdateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:daily-update-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete User data after 90 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = Carbon::now()->subMonths(3)->format('Y-m-d'); 

        $users = User::whereNotNull('delete_date')->where('delete_date', '<=', $date)->get();
        
        if(isset($users) && count($users) > 0)
        {
            foreach ($users as $user)
            {    
                $commentLinke =CommentLike::where('user_id', $user->id)->delete();
                $commentReply = CommentReply::where('user_id', $user->id)->delete();
                $post = Post::where('user_id', $user->id)->delete();
                $postbookmarklike = PostBookmarkLike::where('user_id', $user->id)->delete();
                $postComment = PostComment::where('user_id', $user->id)->delete();
                $replyLike = ReplyLike::where('user_id', $user->id)->delete();
                
               User::where('id', $user->id)->delete();
               
               $this->info('CommentLike:'.$commentLinke);
               $this->info('CommentReply:'.$commentReply);
               $this->info('Post:'.$post);
               $this->info('PostBookmarkLike:'.$postbookmarklike);
               $this->info('PostComment:'.$postComment);
               $this->info('ReplyLike:'.$replyLike);
               Log::info("Total New deleted users: ".count($users));
            }
           
        }
        else
        {
            Log::info("Total New deleted users: ".count($users));
        }
    }
}
