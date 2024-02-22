<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTracingEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracing_emails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id')->nullable(); # foreign
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');

            $table->string('subject');

            $table->unsignedBigInteger('sender_id')->nullable(); # foreign
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('receiver_id')->nullable(); # foreign
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('tracing_emails');
    }
}
