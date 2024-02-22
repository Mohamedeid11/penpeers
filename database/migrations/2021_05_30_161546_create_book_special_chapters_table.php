<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookSpecialChaptersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_special_chapters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id'); # foreign
            $table->foreign('book_id')
                ->references('id')->on('books')
                ->onDelete('cascade');
            $table->unsignedBigInteger('book_edition_id'); # foreign
            $table->foreign('book_edition_id')
                ->references('id')->on('book_editions')
                ->onDelete('cascade');
            $table->unsignedBigInteger('special_chapter_id'); # foreign
            $table->foreign('special_chapter_id')
                ->references('id')->on('special_chapters')
                ->onDelete('cascade');
            $table->longText('content')->nullable();
            $table->date('deadline')->nullable();
            $table->boolean('completed')->default(false);
            $table->date('completed_at')->nullable();
            $table->unique(['book_id', 'book_edition_id', 'special_chapter_id'], "bk_id_bk_edition_id_special_chapter_id_unique");
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
        Schema::dropIfExists('book_special_chapters');
    }
}
