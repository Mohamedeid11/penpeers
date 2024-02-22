<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('display_name')->comment("Translatable");         // Translatable
            $table->boolean('default')->default(false);

            $table->unsignedBigInteger('permission_category_id'); // foreign
            $table->foreign('permission_category_id')
                ->references('id')->on('permission_categories')
                ->onDelete('cascade');
            $table->unique(['name', 'permission_category_id']);
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
        Schema::table('permissions', function (Blueprint $table) {
            Schema::dropIfExists('permissions');

        });
    }
}
