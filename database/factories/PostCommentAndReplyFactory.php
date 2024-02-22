<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostCommentAndReplyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'blog_id' => Blog::where('approved',1)->inRandomOrder()->first()->id ,
            'comment' =>  $this->faker->text,
            'type' => 'comment',
        ];
    }
}
