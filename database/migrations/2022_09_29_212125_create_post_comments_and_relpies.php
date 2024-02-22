<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostCommentsAndRelpies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_comments_and_relpies', function (Blueprint $table) {
            
            $table->id();
            $table->enum('type',['comment','reply']); 

            
            $table->text('comment');

            $table->unsignedBigInteger('blog_id');
            $table->foreign('blog_id')
                    ->references('id')->on('blogs')
                    ->onDelete('cascade');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                        ->references('id')->on('users')
                        ->onDelete('cascade');
            
            $table->unsignedBigInteger('comment_id')->nullable(true);

            $table->timestamps();

           
            
        });

        Schema::table('post_comments_and_relpies',function (Blueprint $table){
            $table->foreign('comment_id')
                    ->references('id')->on('post_comments_and_relpies')
                    ->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_comments_and_relpies');
    }
}
