<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookSpecialChapterEditHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_special_chapter_edit_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_special_chapter_id'); # foreign
            $table->foreign('book_special_chapter_id', 'spec_chap_edit_history_special_chap_foreign')
                ->references('id')->on('book_special_chapters')
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
        Schema::dropIfExists('book_special_chapter_edit_history');
    }
}
