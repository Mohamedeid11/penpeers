<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookPublishRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_publish_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id'); # foreign
            $table->foreign('book_id')
                ->references('id')->on('books')
                ->onDelete('cascade');
            $table->unsignedBigInteger('book_edition_id'); # foreign
            $table->foreign('book_edition_id')
                ->references('id')->on('book_editions')
                ->onDelete('cascade');
            $table->unsignedBigInteger('user_id'); # foreign
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->date('publication_date');
            $table->boolean('approved');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book_publish_requests');
    }
}
