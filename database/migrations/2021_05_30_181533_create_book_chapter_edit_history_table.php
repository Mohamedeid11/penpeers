<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookChapterEditHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_chapter_edit_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_chapter_id'); # foreign
            $table->foreign('book_chapter_id')
                ->references('id')->on('book_chapters')
                ->onDelete('cascade');
            $table->integer('history_number');
            $table->text('content');
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
        Schema::dropIfExists('book_chapter_edit_history');
    }
}
