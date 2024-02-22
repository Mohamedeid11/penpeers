<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColsToBookEditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('book_editions', function (Blueprint $table) {

            $table->after('id', function ($table) {
                $table->string('title')->comment("Translatable")->nullable();
                $table->text('description')->comment("Translatable")->nullable();
                $table->string('language')->nullable();
                $table->string('front_cover')->nullable();
                $table->string('back_cover')->nullable();
                $table->unsignedBigInteger('genre_id')->nullable(); # foreign
                $table->foreign('genre_id')
                    ->references('id')->on('genres')
                    ->onDelete('cascade');
            });
          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('book_editions', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('description');
            $table->dropColumn('language');
            $table->dropColumn('front_cover');
            $table->dropColumn('back_cover');
            $table->dropConstrainedForeignId('genre_id');
        });
    }
}
