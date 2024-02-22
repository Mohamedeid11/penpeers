<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsHiddenChangedAtToBookEditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('book_editions', function (Blueprint $table) {
            $table->date('is_hidden_changed_at')->nullable()->after('is_hidden');
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
            $table->dropColumn('is_hidden_changed_at');
        });
    }
}
