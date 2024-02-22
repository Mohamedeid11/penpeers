<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); # foreign
            $table->integer('status')->default(0)->comment("0 pending accepted, 1 accepted invitation , 2 rejected");
            $table->dateTime('answered_at')->nullable()->default(null);
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->unsignedBigInteger('book_id'); # foreign
            $table->foreign('book_id')
                ->references('id')->on('books')
                ->onDelete('cascade');
            $table->unsignedBigInteger('book_role_id'); # foreign
            $table->foreign('book_role_id')
                ->references('id')->on('book_roles')
                ->onDelete('cascade');
            $table->unique(['user_id', 'book_id']);
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
        Schema::dropIfExists('book_participants');
    }
}
