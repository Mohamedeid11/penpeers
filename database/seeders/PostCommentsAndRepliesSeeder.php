<?php

namespace Database\Seeders;

use App\Models\PostCommentAndReply;
use Illuminate\Database\Seeder;

class PostCommentsAndRepliesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PostCommentAndReply::factory()->count(10)->create();

        $comments = PostCommentAndReply::all();
        
        foreach($comments as $comment)
        {
            PostCommentAndReply::factory()->create(['type'=>'reply','comment_id'=> $comment->id]);
        }

    }
}
