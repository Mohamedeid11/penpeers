<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment("Translatable");
            $table->text('description')->comment("Translatable");
            $table->string('language');
            $table->string('slug')->nullable();
            $table->boolean('popular')->default(false);
            $table->enum('visibility', ['private', 'public', 'shared']);
            $table->tinyInteger('completed')->default(0)->comment('editing status: 0 for ongoing, 1 for completed, 2 for rediting');
            $table->date('editing_status_changed_at')->nullable();
            $table->boolean('status')->default(false)->comment('false for hidden and true for shown');
            $table->date('status_changed_at')->nullable();
            $table->boolean('label_first_edition')->default(false)->comment("Determines whether to show edition number on first edition");
            $table->boolean('receive_requests')->default(true)->comment("Determines if the book can receive requests from authors willing to participate");
            $table->string('front_cover')->nullable();
            $table->string('back_cover')->nullable();
            $table->integer('price')->nullable();
            $table->boolean('sample')->default(false)->nullable()->comment("false : from outside penpeers , true : from penpeers");

            $table->unsignedBigInteger('genre_id'); # foreign
            $table->foreign('genre_id')
                ->references('id')->on('genres')
                ->onDelete('cascade');
            $table->unsignedBigInteger('team_id')->nullable(); # foreign
            $table->foreign('team_id')
                ->references('id')->on('teams')
                ->onDelete('cascade');

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
        Schema::dropIfExists('books');
    }
}
