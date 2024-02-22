<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_invitations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invited_by'); # foreign
            $table->string('email');
            $table->dateTime('invited_at');
            $table->dateTime('answered_at')->nullable()->default(null);
            $table->integer('status')->default(0)->comment("0 pending accepted, 1 accepted invitation , 2 rejected");
            $table->foreign('invited_by')
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
        Schema::dropIfExists('email_invitations');
    }
}
