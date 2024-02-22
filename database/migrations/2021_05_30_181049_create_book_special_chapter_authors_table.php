<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookSpecialChapterAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_special_chapter_authors', function (Blueprint $table) {
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
            $table->unsignedBigInteger('book_special_chapter_id'); # foreign
            $table->foreign('book_special_chapter_id')
                ->references('id')->on('book_special_chapters')
                ->onDelete('cascade');
            $table->unique(['book_id', 'book_edition_id', 'book_special_chapter_id', 'user_id'], "bk_id_bk_edition_id_bk_special_chapter_id_user_id_unique");
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
        Schema::dropIfExists('book_special_chapter_authors');
    }
}
