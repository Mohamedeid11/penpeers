<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookEditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_editions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id'); # foreign
            $table->foreign('book_id')
                ->references('id')->on('books')
                ->onDelete('cascade');
            $table->integer('edition_number')->default('1');
            $table->date('publication_date')->nullable();
            $table->enum('visibility', ['private', 'public', 'shared']);
            $table->boolean('is_hidden')->default(false)->comment("To hide an edition ");
            $table->date('deadline')->nullable();
            $table->double('original_price');
            $table->double('discount_price');
            $table->timestamps();
            $table->unique(['book_id', 'edition_number']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book_editions');
    }
}
