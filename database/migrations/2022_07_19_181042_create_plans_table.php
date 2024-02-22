<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name',55)->nullable();
            $table->enum('account_type', ['personal', 'corporate']);
            $table->enum('period', ['annually', 'biennially', 'triennially', 'quadrennially']);
            $table->integer('price');
            $table->integer('items')->default(0);
            $table->boolean('is_system')->default(false)->comment("System Plans Cannot Be Deleted Even By Admins");
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('plans');
    }
}
