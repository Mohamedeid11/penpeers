<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookChaptersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_chapters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id'); # foreign
            $table->foreign('book_id')
                ->references('id')->on('books')
                ->onDelete('cascade');
            $table->unsignedBigInteger('book_edition_id'); # foreign
            $table->foreign('book_edition_id')
                ->references('id')->on('book_editions')
                ->onDelete('cascade');
            $table->string('name');
            $table->integer('order');
            $table->longText('content')->nullable();
            $table->date('deadline')->nullable();
            $table->boolean('completed')->default(false);
            $table->date('completed_at')->nullable();
            $table->unique(['book_id', 'book_edition_id', 'order']);
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
        Schema::dropIfExists('book_chapters');
    }
}
